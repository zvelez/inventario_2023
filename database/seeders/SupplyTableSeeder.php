<?php

namespace Database\Seeders;

use App\Models\Supply;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplyTableSeeder extends Seeder {
  
  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Supply::truncate();
    DB::statement("SET foreign_key_checks=1");
    $rand = rand(200,300);
    Supply::factory()->count($rand)->create();
  }
}
