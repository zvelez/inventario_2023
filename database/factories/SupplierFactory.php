<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory {

  protected $model = Supplier::class;
  
  public function definition() {
    return [
      'name' => $this->faker->company(),
      'email' => $this->faker->companyEmail(),
      'contact' => $this->faker->name(),
      'address' => $this->faker->streetAddress(),
      'phone' => $this->faker->phoneNumber(),
    ];
  }
}
