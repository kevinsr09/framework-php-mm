<?php

namespace Rumi\Database\Migrations;

use PDOException;
use Rumi\Database\Drivers\DatabaseDriver;

class Migrator{


  
  public function __construct(
    protected string $migrationsDirectory,
    protected string $templateDirectory,
    protected DatabaseDriver $driver
  ){
    
  }

  public function createTableMigrationsIFNotExists(): void {

    $this->driver->statement("CREATE TABLE IF NOT EXISTS migrations (
      id INT AUTO_INCREMENT PRIMARY KEY,
      migration VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP NULL
    )");
  }

  public function make(string $option): void {
    
    $option = snake_case($option);
    $template = file_get_contents($this->templateDirectory . "/migration.php");
    if(preg_match("/create_.*_table/", $option)){

      $nameTable = preg_replace_callback("/create_(.*)_table/", fn($matches) => $matches[1], $option);

      $template = str_replace('$UP', 
        "CREATE TABLE IF NOT EXISTS {$nameTable} (
            id INT AUTO_INCREMENT PRIMARY KEY
          )", $template);

      $template = str_replace('$DOWN', "DROP TABLE IF EXISTS {$nameTable}", $template);

    }elseif(preg_match("/.*(from|to).*_table/", $option)){
      $nameTable = preg_replace_callback("/.*(from|to)_(.*)_table/", fn($matches) => $matches[2], $option);
      $template = preg_replace('/\$UP|\$DOWN/', 
        "ALTER TABLE {$nameTable} ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY", $template);

    } else {
      $template = preg_replace_callback('/DB::statement.*/', fn($matches) => "// {$matches[0]}", $template);

    }

    $date = date("Y_m_d");
    $id = 0;

    foreach(scandir($this->migrationsDirectory) as $file){
      if(preg_match("/{$date}.*/", $file)){
        $id++;
      }
    }

    $fileName = sprintf("%s_%06d_%s.php",$date, $id, $option);
    file_put_contents($this->migrationsDirectory . "/{$fileName}", $template);
  }
  public function migrate(): void {
    $this->createTableMigrationsIFNotExists();
    $migrationsDB = $this->driver->statement("SELECT * FROM migrations");
    $migrations = glob("$this->migrationsDirectory/*.php");
    if(count($migrationsDB) >= count($migrations)){
      $this->log("all migrations already executed");
      return;
    }

    $migrations = array_slice($migrations, count($migrationsDB));

    foreach($migrations as $file){

      $name = basename($file);
      $migration = require_once $file;
      try{
        $migration->up();
        $this->driver->statement("INSERT INTO migrations (migration) VALUES (?)", [
          $name
        ]);
        $this->log("migration: $name successful");

      }catch(PDOException $e){
        $this->log("migration: $name failed");
        $this->log($e->getMessage());
      }
    }
  }

  public function rollback(int $steps = null): void {

    $this->createTableMigrationsIFNotExists();
    $migrationsPending = $this->driver->statement("SELECT * FROM migrations"); 
    $migrations = glob("$this->migrationsDirectory/*.php");
    
    if(count($migrationsPending) == 0){
      $this->log("all migrations already executed");
      return;
    }
    
    if($steps == null){
      $steps = count($migrationsPending);
    }
    
    $migrations = array_slice(array_reverse($migrations), -count($migrationsPending));
    

    foreach($migrations as $file){

      $name = basename($file);
      $migration = require_once $file;
      try{

        $migration->down();
        $this->driver->statement("DELETE FROM migrations WHERE migration = ?", [
          $name
        ]);
        $this->log("migration: $name successful");

        if(--$steps == 0){
          break;
        }
      
      }catch(PDOException $e){
        $this->log("migration: $name failed");
        $this->log($e->getMessage());
      }

    }
  }

  
  public function log(string $message): void {
    print("$message" . PHP_EOL);
  }
}