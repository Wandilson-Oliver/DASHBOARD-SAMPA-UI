<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enum\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'wandilson.oliver@gmail.com',
            'status' => 1,
            'password' => Hash::make('senha123'), // nunca coloque senha em texto puro no prod
        ]);

        User::create([
            'name' => 'Lia',
            'email' => 'lia@gmail.com',
            'status' => 0,
            'password' => Hash::make('12345678'), // nunca coloque senha em texto puro no prod
        ]);
    }
}
