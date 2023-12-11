<?php


namespace Rumi\Tests\Database;

use PDOException;
use PHPUnit\Framework\TestCase;
use Rumi\Database\Drivers\DatabaseDriver;
use Rumi\Database\Drivers\PdoDriver;
use Rumi\Database\Model;

class MockModel extends Model{

  protected bool $timestamps = true;
  
}
class ModelTest extends TestCase{

  protected DatabaseDriver $driver;
  protected function setUp():void{
    $this->driver = new PdoDriver();
    Model::setDriver($this->driver);
    try{
      $this->driver->connect('mysql', '127.0.0.1', 3306, 'db_test', 'root', 'root');
    }catch(PDOException $e){
      print_r($e->getMessage());
    }

  }

  protected function tearDown():void{

    $this->driver->statement("DROP DATABASE IF EXISTS db_test");
    $this->driver->statement("CREATE DATABASE db_test");

  }


  public function createTable(string $name, array $columns, bool $createAT){
    $this->driver->statement(
        "CREATE TABLE IF NOT EXISTS {$name} (
        id INT AUTO_INCREMENT PRIMARY KEY, "
      . implode(", ", array_map(fn($column) => "{$column} VARCHAR(255)", $columns))
      . ($createAT ? ", created_at TIMESTAMP, updated_at TIMESTAMP NULL" : "") 
      . ")");
  }

  
  public function test_basic_create_table_and_insert_user(){
    
    $this->createTable("mock_models", ["name", "email"], true);
    
    $driver = new MockModel();
    $driver->name = "test";
    $driver->email = "test";
    $driver->save();    
    $expected = [
      "id" => 1,
      "name" => "test",
      "email" => "test",
      "created_at" => date("Y-m-d H:i:s"),
      "updated_at" => null
    ];


    $this->assertEquals($expected, $this->driver->statement("SELECT * FROM mock_models WHERE id = 1")[0]);
    $this->assertEquals($expected, $driver->find(1));

    $this->assertTrue(count($this->driver->statement("SELECT * FROM mock_models")) == 1);
  }
}