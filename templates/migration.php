<?php

use Rumi\Database\DB;
use Rumi\Database\Migrations\Migration;

return new class() implements Migration {
  public function up(): void {
      DB::statement('$UP');
  }

  public function down(): void {
      DB::statement('$DOWN');
  }

};