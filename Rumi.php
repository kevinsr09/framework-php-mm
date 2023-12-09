<?php

use Rumi\Database\Migrations\Migrator;

require_once "./vendor/autoload.php";

if(strtolower($argv[1]) == 'make:migration'){
  $migrator = new Migrator(__DIR__ . '/database/migrations', __DIR__ . '/templates');
  $migrator->make($argv[2]);
}