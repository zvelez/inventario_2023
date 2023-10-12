<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductphotosTable extends Migration {
  
  public function up() {
      Schema::create('productphotos', function (Blueprint $table) {
        $table->id();
        $table->string('photourl');
        $table->string('description')->nullable();
        $table->unsignedBigInteger('product_id')->unsigned();
        $table->timestamps();
        
        $table->foreign('product_id')->references('id')->on('products');
      });
  }

  public function down() {
      Schema::dropIfExists('productphotos');
  }
}
