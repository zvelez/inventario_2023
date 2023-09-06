<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartingordersTable extends Migration {

  public function up() {
    Schema::create('startingorders', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('work_id')->unsigned();
      $table->unsignedBigInteger('user_id')->unsigned();
      $table->text('comment')->nullable();
      $table->timestamp('dispatchdate');
      $table->string('receivedby');
      $table->string('document')->nullable();
      $table->foreign('work_id')->references('id')->on('works');
      $table->foreign('user_id')->references('id')->on('users');
      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('startingorders');
  }
}
