<?php

use Rumi\Database\DB;
use Rumi\Database\Migrations\Migration;

return new class() implements Migration {
  public function up(): void {
      DB::statement('ALTER TABLE IF EXISTS users_products (id INT AUTO_INCREMENT PRIMARY KEY)');
  }

  public function down(): void {
      DB::statement('ALTER TABLE IF EXISTS users_products (id INT AUTO_INCREMENT PRIMARY KEY)');
  }

};