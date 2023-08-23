<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAssignedsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('product_assigneds', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('product_id')->unsigned();
      $table->unsignedBigInteger('supply_id')->unsigned();
      $table->string('amount');
      $table->foreign('product_id')->references('id')->on('products');
      $table->foreign('supply_id')->references('id')->on('supplies');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('product_assigneds');
  }
}
