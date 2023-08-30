<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder {

  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Product::truncate();
    DB::statement("SET foreign_key_checks=1");
    
    $works = Work::count();
    $rand = rand($works,($works*4));
    Product::factory()->count($rand)->create();
  }
}
