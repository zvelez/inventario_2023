<?php

namespace Database\Seeders;

use App\Models\Orderentry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderentryTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Orderentry::truncate();
    DB::statement("SET foreign_key_checks=1");
    $rand = rand(20,30);
    Orderentry::factory()->count($rand)->create();
  }
}
