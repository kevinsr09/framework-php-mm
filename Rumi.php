<?php

require_once "./vendor/autoload.php";

use Rumi\Database\Drivers\DatabaseDriver;
use Rumi\Database\Drivers\PdoDriver;
use Rumi\Database\Migrations\Migrator;

$driver = singleton(DatabaseDriver::class, PdoDriver::class);
$driver->connect('mysql', '127.0.0.1', 3308, 'mastermind', 'root', 'root');

$migrator = new Migrator(
  __DIR__ . '/database/migrations',
 __DIR__ . '/templates',
  $driver
);

if(strtolower($argv[1]) == 'make:migration'){
  $migrator->make($argv[2]);
}elseif(strtolower($argv[1]) == 'migrate'){
  $migrator->migrate();
}