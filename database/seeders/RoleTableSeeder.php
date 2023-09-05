<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder {

  public function run() {
    DB::statement("SET foreign_key_checks=0");
    Role::truncate();
    DB::statement("SET foreign_key_checks=1");
    $role = Role::find(1);
    if(empty($role)) {
      Role::create([
        'name' => 'Administrador de Sistema',
        'code' => 'ADMSYS',
      ]);
    }
    Role::create([
      'name' => 'Administrador',
      'code' => 'ADMIN',
    ]);
    Role::create([
      'name' => 'Almacen',
      'code' => 'ALMAC',
    ]);
    Role::create([
      'name' => 'Producción',
      'code' => 'PROD',
    ]);
    Role::create([
      'name' => 'Despacho',
      'code' => 'DESP',
    ]);
    Role::create([
      'name' => 'Calidad',
      'code' => 'CALID',
    ]);
  }
}
