<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierTableSeeder extends Seeder {
  
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Supplier::truncate();
    DB::statement("SET foreign_key_checks=1");
    $rand = rand(12,20);
    Supplier::factory()->count($rand)->create();
  }
}
