<?php

namespace Database\Factories;

use App\Models\Orderentry;
use App\Models\Supply;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplyFactory extends Factory {

  protected $model = Supply::class;

  public function definition() {
    $codesBase = ['CODE 128', 'EAN-13', 'EAN-8', 'EAN-5', 'EAN-2', 'CODE 39', 'ITF', 'ITF-14', 'MSI10', 'MSI11', 'MSI1010', 'MSI1110'];
    $codeRand = rand(0, sizeof($codesBase) - 1);
    $brandRand = rand(1, 3);
    
    $ordersLength = Orderentry::count();
    $ordersRand = rand(1, $ordersLength);
    $unit = $this->faker->randomElements(['k','m','t','l','s'], 2);
    return [
      'code' => $this->faker->bothify($codesBase[$codeRand].'##'),
      'description' => $this->faker->words(8, true),
      'brand' => $this->faker->words($brandRand, true),
      'unit' => $unit[0].$unit[1],
      'amount' => $this->faker->randomFloat(3, 1000, 10000),
      'unitprice' => $this->faker->randomFloat(2, 5, 250),
      'deliverynote' => $this->faker->sentences(20, true),
      'orderentry_id' => $ordersRand,
    ];
  }
}
