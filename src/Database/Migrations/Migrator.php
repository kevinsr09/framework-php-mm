<?php

namespace Rumi\Database\Migrations;
class Migrator{


  
  public function __construct(
    protected string $migrationsDirectory,
    protected string $templateDirectory
  ){
    
  }
  public function make(string $option): void {
    
    $option = snake_case($option);
    $template = file_get_contents($this->templateDirectory . "/migration.php");
    if(preg_match("/create_.*_table/", $option)){

      $nameTable = preg_replace_callback("/create_(.*)_table/", fn($matches) => $matches[1], $option);

      $template = str_replace('$UP', "CREATE TABLE IF NOT EXISTS {$nameTable} (id INT AUTO_INCREMENT PRIMARY KEY)", $template);

      $template = str_replace('$DOWN', "DROP TABLE IF EXISTS {$nameTable}", $template);

    }elseif(preg_match("/.*(from|to).*_table/", $option)){
      $nameTable = preg_replace_callback("/.*(from|to)_(.*)_table/", fn($matches) => $matches[2], $option);
      $template = preg_replace('/\$UP|\$DOWN/', "ALTER TABLE IF EXISTS {$nameTable} (id INT AUTO_INCREMENT PRIMARY KEY)", $template);

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
  
}