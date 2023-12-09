<?php

namespace Rumi\Database\Migrations;

interface Migration{
  
  public function up();
  public function down();
}