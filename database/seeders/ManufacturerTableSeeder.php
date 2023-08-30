<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManufacturerTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Manufacturer::truncate();
    DB::statement("SET foreign_key_checks=1");
    $rand = rand(8,16);
    Manufacturer::factory()->count($rand)->create();
  }
}
