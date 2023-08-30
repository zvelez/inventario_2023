<?php

namespace Database\Factories;

use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManufacturerFactory extends Factory {

  protected $model = Manufacturer::class;

  public function definition() {
    $typeRand = rand(0, 1);
    return [
      'fullname' => $typeRand===0 ? $this->faker->name() : $this->faker->company(),
      'email' => $this->faker->unique()->safeEmail(),
      'contact' => $this->faker->name(),
      'address' => $this->faker->streetAddress(),
      'description' => $this->faker->paragraph(2),
      'phone' => $this->faker->phoneNumber(),
    ];
  }
}
