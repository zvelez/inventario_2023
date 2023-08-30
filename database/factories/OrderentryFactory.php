<?php

namespace Database\Factories;

use App\Models\Orderentry;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderentryFactory extends Factory {

  protected $model = Orderentry::class;
  
  public function definition() {
    $noteRand = rand(8, 24);
    $solicitantdays = rand(20, 36);
    $estimatedays = rand(8, $solicitantdays);
    $registerdays = rand($solicitantdays - 8, $solicitantdays);
    $isDelivery = $this->faker->boolean();
    $deliverydays = $isDelivery ? rand($estimatedays-2, $estimatedays + 3) : NULL;

    $now = Carbon::now();
    $solicitantdate = $now->copy()->subtract('days', $solicitantdays)->toDateString();
    $estimatedays = $now->copy()->subtract('days', $deliverydays)->toDateString();
    $registerdate = $now->copy()->subtract('days', $registerdays);
    $deliverydate = $isDelivery ? $now->copy()->subtract('days', $deliverydays)->toDateString() : NULL;
    
    $supplierLength = Supplier::count();
    $supplierRand = rand(1, $supplierLength);
    return [
      'date' => $registerdate->toDateString(),
      'solicitantarea' => $this->faker->words(2, true),
      'solicitantmanager' => $this->faker->words(3, true),
      'solicitantdate' => $solicitantdate,
      'deliveryaddress' => $this->faker->streetAddress(),
      'deliverydate' => $deliverydate,
      'deliveryreceptionist' => $this->faker->name(),
      'estimateddeliverydate' => $estimatedays,
      'deliverynote' => $this->faker->words($noteRand, true),
      'deliveryref' => $this->faker->words(3, true),
      'deliverydoc' => 'documents/order-entries/volclqMyDt7TcueEuqMWZeOzY8YiY2DtsgtpjMdw.png',
      'supplier_id' => $supplierRand,
      'created_at' => $registerdate,
      'updated_at' => $registerdate,
    ];
  }
}
