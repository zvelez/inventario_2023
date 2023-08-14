<?php

namespace Database\Seeders;

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
    DB::table('users')->truncate();
    DB::table('users')->insert([
      'fullname' => 'Rolando Monzon',
      'email' => 'me@rho1and0.net',
      'password' => Hash::make('me@rho1and0.net'),
      //'password' => Hash::make(Str::random(10)),
      'status' => 1,
    ]);
    DB::table('users')->insert([
      'fullname' => 'Roberto Velez',
      'email' => 'rvelez@gmail.com',
      'password' => Hash::make(Str::random(10)),
      'status' => 1,
    ]);
  }
}
