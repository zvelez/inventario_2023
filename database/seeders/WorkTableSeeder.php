<?php

namespace Database\Seeders;

use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkTableSeeder extends Seeder {

  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Work::truncate();
    DB::statement("SET foreign_key_checks=1");
    $rand = rand(12,16);
    Work::factory()->count($rand)->create();
  }
}
