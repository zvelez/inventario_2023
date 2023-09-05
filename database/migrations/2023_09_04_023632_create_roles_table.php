<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration {
  
  public function up() {
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('code');
      $table->timestamps();
    });
    Role::create([
      'name' => 'Administrador de Sistema',
      'code' => 'ADMSYS',
    ]);
    Schema::table('users', function (Blueprint $table) {
      $table->unsignedBigInteger('role_id')->unsigned()->before('created_at')->default(1);
      $table->foreign('role_id')->references('id')->on('roles');
    });
  }

  public function down() {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('role_id');
    });
    Schema::dropIfExists('roles');
  }
}
