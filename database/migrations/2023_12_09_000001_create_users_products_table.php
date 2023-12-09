<?php

use Rumi\Database\DB;
use Rumi\Database\Migrations\Migration;

return new class() implements Migration {
  public function up(): void {
      DB::statement('CREATE TABLE IF NOT EXISTS users_products (id INT AUTO_INCREMENT PRIMARY KEY)');
  }

  public function down(): void {
      DB::statement('DROP TABLE IF EXISTS users_products');
  }

};