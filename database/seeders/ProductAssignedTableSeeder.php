<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAssigned;
use App\Models\Supply;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAssignedTableSeeder extends Seeder {

  public function run() {
    $faker = app(Generator::class);

    DB::statement("SET foreign_key_checks=0");
    ProductAssigned::truncate();
    DB::statement("SET foreign_key_checks=1");

    $products = Product::get();
    $suppLength = Supply::count();
    foreach($products as $prod) {
      
      $rand = rand(1, 4);
      for($i=0; $i<$rand; $i++) {
        $suppId = rand(1, $suppLength);
        $supply = Supply::find($suppId);
        $totalAmount = Supply::where('code', '=', $supply->code)->sum('amount');

        $assigned = new ProductAssigned();
        $assigned->product_id = $prod->id;
        $assigned->supply_id = $suppId;
        $assigned->amount = $faker->randomFloat(3, 5, ($totalAmount / 5));
        $assigned->save();
      }
    }
  }
}
