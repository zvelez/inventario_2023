<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('deliveries', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('work_id')->unsigned();
      $table->boolean('in')->default(false);
      $table->string('code');
      $table->decimal('amount')->nullable();
      $table->date('deliverydate')->nullable();
      $table->date('estimatedate')->nullable();
      $table->string('responsible');
      $table->string('contact')->nullable();
      $table->string('dnicontact')->nullable();
      $table->text('observations')->nullable();
      $table->unsignedBigInteger('user_id')->unsigned();
      $table->foreign('work_id')->references('id')->on('works');
      $table->foreign('user_id')->references('id')->on('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('deliveries');
  }
}
