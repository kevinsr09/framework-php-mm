<?php

use Rumi\Database\Drivers\DatabaseDriver;

function DB(string $class = DatabaseDriver::class): DatabaseDriver{

  return app($class);
}