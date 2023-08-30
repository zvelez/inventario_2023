<?php

namespace Database\Factories;

use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory {

  protected $model = Product::class;

  public function definition() {
    $worksLength = Work::count();
    $worksRand = rand(1, $worksLength);

    $manufacturersLength = Manufacturer::count();
    $manufacturersRand = rand(1, $manufacturersLength);

    $nameRand = rand(1, 5);
    return [
      'manufacturer_id' => $manufacturersRand,
      'work_id' => $worksRand,
      'code' => $this->faker->bothify('??????-########'),
      'name' => $nameRand,
      'amount' => $this->faker->randomFloat(3, 40, 280),
      'unitprice' => $this->faker->randomFloat(2, 45, 140),
    ];
  }
}
