<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Chrystian Ruan',
            'username' => 'chrys.admin',
            'password' => bcrypt('ebd@chrystian2003'), // password
            'remember_token' => Str::random(10),
            'id_nivel' => 1,
            'status' => 0,
        ]);
    }
}
