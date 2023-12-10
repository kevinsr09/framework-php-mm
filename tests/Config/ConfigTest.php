<?php 

namespace Rumi\Tests\Config;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Rumi\App;
use Rumi\Config\Config;

class ConfigTest extends TestCase{

  protected function setUp():void{

    Config::$config = [];
  } 
  public function test_load(){

    App::$rootDirectory = __DIR__ . '/../..';
    Dotenv::createImmutable(__DIR__ . '/../..')->load();


    Config::load(__DIR__ . '/../../config');
    $this->assertEquals('Rumos', Config::get('app.name', 'Rumi'));
    $this->assertEquals('Rumi', Config::get('app.nick', 'Rumi'));
    
  }

  public function test_config(){
    
    Config::$config = [
      'name' => 'Rumi',
      'surname' => [
        'firts' => 'Rumi2',
        'last' => [
          'nick' => 'Rumi3',
        ]
      ],
    ];


    $this->assertEquals(null, Config::get('name.last'));
    $this->assertEquals(1, Config::get('name.last', 1));
    $this->assertEquals('Rumi', Config::get('name'));
    $this->assertEquals('Rumi2', Config::get('surname.firts'));
    $this->assertEquals(['nick'=>'Rumi3'], Config::get('surname.last'));
    $this->assertEquals('Rumi3', Config::get('surname.last.nick'));
    $this->assertEquals(null, Config::get('surname.last.nick2'));
    
    
  }
}