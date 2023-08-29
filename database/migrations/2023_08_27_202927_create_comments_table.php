<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('comments', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('work_id')->unsigned();
      $table->unsignedBigInteger('user_id')->unsigned();
      $table->text('comment');
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
    Schema::dropIfExists('comments');
  }
}
