<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  
  public function run() {
    $this->call([
      UserSeeder::class,
      ClientTableSeeder::class,
      SupplierTableSeeder::class,
      ManufacturerTableSeeder::class,
      OrderentryTableSeeder::class,
      SupplyTableSeeder::class,
      WorkTableSeeder::class,
      ProductTableSeeder::class,
      ProductAssignedTableSeeder::class,
      DeliveryTableSeeder::class,
    ]);
  }
}
