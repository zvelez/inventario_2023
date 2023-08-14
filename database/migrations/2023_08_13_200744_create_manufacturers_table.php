<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturersTable extends Migration {
    
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('manufacturers', function (Blueprint $table) {
      $table->id();
      $table->string('fullname');
      $table->string('email');
      $table->tinyInteger('gender')->default(1);
      $table->string('address')->nullable();
      $table->string('phone')->nullable();
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('manufacturers');
  }
}
