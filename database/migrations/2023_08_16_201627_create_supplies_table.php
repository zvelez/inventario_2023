<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('supplies', function (Blueprint $table) {
      $table->id();
      $table->string('code')->nullable();
      $table->string('description');
      $table->string('brand');
      $table->string('unit');
      $table->decimal('amount', 21, 3);
      $table->decimal('unitprice', 16, 2);
      $table->text('deliverynote')->nullable();
      $table->unsignedBigInteger('orderentry_id')->unsigned();
      $table->foreign('orderentry_id')->references('id')->on('orderentries');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('supplies');
  }
}
