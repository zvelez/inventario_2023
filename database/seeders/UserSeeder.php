<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use DB;
use Hash;
use Str;

class UserSeeder extends Seeder {
    
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::statement("SET foreign_key_checks=0");
    User::truncate();
    DB::statement("SET foreign_key_checks=1");
    DB::table('users')->insert([
      'fullname' => 'Roberto Velez',
      'email' => 'rvelez@gmail.com',
      'password' => Hash::make('rvelez@gmail.com'),
      'status' => 1,
      'role_id' => 1,
    ]);
    DB::table('administrador')->insert([
      'fullname' => 'Admin',
      'email' => 'admin@kaytumpi.com',
      'password' => Hash::make('admin@kaytumpi.com'),
      'status' => 1,
      'role_id' => 2,
    ]);
    DB::table('users')->insert([
      'fullname' => 'Usuario 1',
      'email' => 'user1@kaytumpi.com',
      'password' => Hash::make('user1@kaytumpi.com'),
      'status' => 1,
      'role_id' => 3,
    ]);
    DB::table('users')->insert([
      'fullname' => 'Usuario 2',
      'email' => 'user2@kaytumpi.com',
      'password' => Hash::make('user2@kaytumpi.com'),
      'status' => 1,
      'role_id' => 4,
    ]);
    DB::table('users')->insert([
      'fullname' => 'Usuario 3',
      'email' => 'user3@kaytumpi.com',
      'password' => Hash::make('user3@kaytumpi.com'),
      'status' => 5,
    ]);
    DB::table('users')->insert([
      'fullname' => 'Usuario 4',
      'email' => 'user4@kaytumpi.com',
      'password' => Hash::make('user4@kaytumpi.com'),
      'status' => 6,
    ]);
  }
}
