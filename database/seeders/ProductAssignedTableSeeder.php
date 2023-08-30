<?php

namespace Database\Seeders;

use App\Models\ProductAssigned;
use Illuminate\Database\Seeder;

class ProductAssignedTableSeeder extends Seeder {

  public function run() {
    ProductAssigned::factory()->count(50)->create();
  }
}
