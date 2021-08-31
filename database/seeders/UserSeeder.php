<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pass = Hash::make(12345678);
        DB::table('users')->insert([
            'name' => 'User 01',
            'email' => 'user01@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => $pass,
            'verified_token' => $pass,
            'address' => 'Ha Noi',
            'phone' => '0123456789102',
            'status' => 1,
            'total_file' => 0,
            'base_path' => '/files/user/',
            'role' => 1,
            'remember_token' => 'asdwqdibuidig',
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => $pass,
            'verified_token' => $pass,
            'address' => 'Ha Noi',
            'phone' => '0123456789103',
            'status' => 1,
            'total_file' => 0,
            'base_path' => '/files/user/',
            'role' => 0,
            'remember_token' => 'asdwqdibuidig',
        ]);
    }
}
