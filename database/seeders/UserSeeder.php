<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 👑 ADMIN
        $adminUser = User::updateOrCreate(
            ['email' => 'wandilson.oliver@gmail.com'],
            [
                'name'     => 'Administrador',
                'status'   => 1,
                'password' => Hash::make('senha123'),
            ]
        );

        $adminRole = Role::where('name', 'admin')->firstOrFail();

        $adminUser->roles()->syncWithoutDetaching($adminRole->id);

        // 👤 USUÁRIO COMUM
        User::updateOrCreate(
            ['email' => 'lia@gmail.com'],
            [
                'name'     => 'Lia',
                'status'   => 0,
                'password' => Hash::make('12345678'),
            ]
        );
    }
}
