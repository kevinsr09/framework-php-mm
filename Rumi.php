<?php

require_once "./vendor/autoload.php";

use Rumi\Database\Drivers\DatabaseDriver;
use Rumi\Database\Drivers\PdoDriver;
use Rumi\Database\Migrations\Migrator;

$driver = singleton(DatabaseDriver::class, PdoDriver::class);
$driver->connect('mysql', '127.0.0.1', 3306, 'mastermind', 'root', 'root');

$migrator = new Migrator(
  __DIR__ . '/database/migrations',
 __DIR__ . '/resourses/templates',
  $driver
);

if(strtolower($argv[1]) == 'make:migration'){
  $migrator->make($argv[2]);
}elseif(strtolower($argv[1]) == 'migrate'){
  $migrator->migrate();
}elseif(strtolower($argv[1]) == 'rollback'){
  
  $steps = null;
  if(isset($argv[2]) && $argv[2] == '--steps' && isset($argv[3])){
    $steps = intval($argv[3]);
  }
  

  $migrator->rollback($steps);
}else{
  echo "Invalid option\n";
}