<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::insert([
            'full_name'         => 'Sugar & Daddie',
            'email_address'     => 'admin@gmail.com',
            'password'          => Hash::make('Admin@123'),
        ]);
    }
}
