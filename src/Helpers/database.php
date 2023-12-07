<?php

use Rumi\Database\Drivers\DatabaseDriver;

function DB(): DatabaseDriver{

  return app()->database;
}