<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientTableSeeder extends Seeder {
  
  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Client::truncate();
    DB::statement("SET foreign_key_checks=1");
    $rand = rand(7,10);
    Client::factory()->count($rand)->create();
  }
}
