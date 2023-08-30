<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory {

  protected $model = Client::class;

  public function definition() {
    $genRand = rand(0, 1);
    $sex = $genRand === 0 ? 'female' : 'male';
    $city = $this->faker->city();
    return [
      'fullname' => $this->faker->name($sex),
      'email' => $this->faker->unique()->safeEmail(),
      'gender' => $genRand,
      'address' => $this->faker->streetAddress(),
      'city' => $city,
      'state' => $city,
      'phone' => $this->faker->phoneNumber(),
    ];
  }
}
