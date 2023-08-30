<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFactory extends Factory {

  protected $model = Work::class;

  public function definition() {
    $status = [
      'Iniciado',
      'Producción',
      'Revisión',
      'Entregable',
      'Entregado',
    ];
    $statusRand = rand(0, sizeof($status) - 1);

    $clientsLength = Client::count();
    $clientsRand = rand(1, $clientsLength);

    $now = Carbon::now();
    $dayRand = rand(-15, 20);
    $deadline = $dayRand < 0 ? $now->subtract('days', $dayRand) : $now->addDays($dayRand);
    return [
      'deadline' => $deadline,
      'status' => $status[$statusRand],
      'client_id' => $clientsRand,
    ];
  }
}
