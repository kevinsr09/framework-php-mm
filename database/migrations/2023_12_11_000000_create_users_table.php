<?php

use Rumi\Database\DB;
use Rumi\Database\Migrations\Migration;

return new class() implements Migration {
  public function up(): void {
      DB::statement('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            email VARCHAR(255),
            password VARCHAR(255),
            created_at DATETIME,
            updated_at DATETIME NULL
          )');
  }

  public function down(): void {
      DB::statement('DROP TABLE IF EXISTS users');
  }

};