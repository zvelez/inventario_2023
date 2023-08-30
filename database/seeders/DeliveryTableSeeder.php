<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\ProductAssigned;
use App\Models\Supply;
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Seeder;

class DeliveryTableSeeder extends Seeder {

  public function run() {
    $faker = app(Generator::class);
    $assigned = ProductAssigned::with(['supply', 'product', 'product.manufacturer', 'product.work'])->get();
    foreach($assigned as $row) {
      
      $userRand = rand(2, 4);
      $repRand = rand(1, 3);
      $percentages = $this->calcPer($repRand, $row);
      $dates = $this->calcOutDates($repRand, $row);
      for($i=0; $i<$repRand; $i++) {
        $supply = Supply::find($row->supply_id);
        Delivery::create([
          'work_id' => $row->product->work_id,
          'in' => 0,
          'code' => $supply->code,
          'amount' => $percentages[$i],
          'deliverydate' => $dates[$i],
          'estimatedate' => NULL,
          'responsible' => $faker->name(),
          'contact' => $faker->name(),
          'dnicontact' => $faker->numerify('########'),
          'observations' => '',
          'user_id' => $userRand,
        ]);
      }
      
      $repRand = rand(0,2);
      $percentages = $this->calcPer($repRand, $row);
      $dates = $this->calcInDates($repRand, $row);
      for($i=0; $i<$repRand; $i++) {
        $product = Product::find($row->product_id);
        Delivery::create([
          'work_id' => $row->product->work_id,
          'in' => 1,
          'code' => $product->code,
          'amount' => $percentages[$i],
          'deliverydate' => $dates[$i],
          'estimatedate' => NULL,
          'responsible' => $faker->name(),
          'contact' => $faker->name(),
          'dnicontact' => $faker->numerify('########'),
          'observations' => '',
          'user_id' => $userRand,
        ]);
      }
    }
  }

  private function calcPer($parts, $row) {
    $faker = app(Generator::class);
    $res = [];
    switch($parts) {
      case 2: {
        $firstVal = $faker->randomFloat(3, ($row->amount / 2), $row->amount);
        $res[] = $firstVal;
        $res[] = $row->amount - $firstVal;
        $res = $faker->shuffle($res);
        break;
      }
      case 3: {
        $firstVal = $faker->randomFloat(3, ($row->amount / 3), $row->amount);
        $secondVal = $faker->randomFloat(3, ($row->amount / 3), $row->amount);
        $res[] = $firstVal;
        $res[] = $secondVal;
        $res[] = $row->amount - $firstVal - $secondVal;
        $res = $faker->shuffle($res);
        break;
      }
      default: {
        $res[] = $row->amount;
        break;
      }
    }
    return $res;
  }

  private function calcOutDates($parts, $row) {
    $res = [];
    $deadlineTime = $row->product->work->deadline;
    $startDate = rand(0, 45);
    switch($parts) {
      case 2: {
        $firstDate = rand(0, 10);
        $secondDate = rand(0, 7);
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $startDate - $firstDate)->format('Y-m-d') : NULL;
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $startDate - $firstDate - $secondDate)->format('Y-m-d') : NULL;
        break;
      }
      case 3: {
        $firstDate = rand(0, 10);
        $secondDate = rand(0, 7);
        $thirdDate = rand(0, 4);
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $startDate - $firstDate)->format('Y-m-d') : NULL;
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $startDate - $firstDate - $secondDate)->format('Y-m-d') : NULL;
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $startDate - $firstDate - $secondDate - $thirdDate)->format('Y-m-d') : NULL;
        break;
      }
      default: {
        $firstDate = rand(0, 10);
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $startDate)->format('Y-m-d') : NULL;
        break;
      }
    }
    return $res;
  }

  private function calcInDates($parts, $row) {
    $res = [];
    $deadlineTime = $row->product->work->deadline;
    switch($parts) {
      case 2: {
        $firstDate = rand(0, 10);
        $secondDate = rand($firstDate, 10);
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $firstDate)->format('Y-m-d') : NULL;
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $secondDate)->format('Y-m-d') : NULL;
        break;
      }
      default: {
        $firstDate = rand(0, 10);
        $res[] = rand(0, 1) == 1 ? Carbon::createFromFormat('Y-m-d', $deadlineTime)->subtract('days', $firstDate)->format('Y-m-d') : NULL;
        break;
      }
    }
    return $res;
  }
}
