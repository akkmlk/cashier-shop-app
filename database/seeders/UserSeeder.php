<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Luminas Valentine',
                'username' => 'admin',
                'email' => 'akmalyunus12@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Petugas Jamal',
                'username' => 'petugas',
                'email' => 'hahaha51515161@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'petugas',
            ],
            // [
            //     'name' => 'Super Admin',
            //     'username' => 'superadmin',
            //     'password' => bcrypt('password'),
            //     'role' => 'superadmin',
            // ],
        ];

        foreach ($users as $value) {
            User::create($value);
        }
    }
}
