<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderentriesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('orderentries', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->string('solicitantarea');
      $table->string('solicitantmanager');
      $table->date('solicitantdate');
      $table->string('deliveryaddress');
      $table->date('deliverydate')->nullable();
      $table->date('estimateddeliverydate')->nullable();
      $table->string('deliveryreceptionist');
      $table->text('deliverynote')->nullable();
      $table->string('deliveryref')->nullable();
      $table->string('deliverydoc')->nullable();
      $table->unsignedBigInteger('supplier_id')->unsigned();
      $table->foreign('supplier_id')->references('id')->on('suppliers');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('orderentries');
  }
}
