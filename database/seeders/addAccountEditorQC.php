<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class addAccountEditorQC extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pass = Hash::make(12345678);
        // Editor
        DB::table('users')->insert([
            'name' => 'Editor01',
            'email' => 'editor01@gmail.com',
            'password' => $pass,
            'address' => 'Ha Noi',
            'phone' => '123456789',
            'status' => 1,
            'total_file' => 0,
            'role' => 2,
        ]);
        // QC
        DB::table('users')->insert([
            'name' => 'QC01',
            'email' => 'qc01@gmail.com',
            'password' => $pass,
            'address' => 'Ha Noi',
            'phone' => '1234567890',
            'status' => 1,
            'total_file' => 0,
            'role' => 3,
        ]);
    }
}
