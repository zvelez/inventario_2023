<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('manufacturer_id')->unsigned();
      $table->unsignedBigInteger('work_id')->unsigned();
      $table->string('code');
      $table->string('name');
      $table->decimal('amount');
      $table->decimal('unitprice');
      $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
      $table->foreign('work_id')->references('id')->on('works');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('products');
  }
}
