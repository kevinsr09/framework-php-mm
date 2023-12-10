<?php

use Rumi\Database\DB;
use Rumi\Database\Migrations\Migration;

return new class() implements Migration {
  public function up(): void {
      DB::statement('ALTER TABLE users ADD COLUMN name VARCHAR(255) NOT NULL');
  }

  public function down(): void {
      DB::statement('ALTER TABLE users DROP COLUMN name');
  }

};