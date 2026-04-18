<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Jefe Departamento',
            'email' => 'jefe@admin.com',
            'password' => Hash::make('123456'),
            'role' => 'jefe_departamento'
        ]);

        User::create([
            'name' => 'Invitado',
            'email' => 'invitado@admin.com',
            'password' => Hash::make('123456'),
            'role' => 'invitado'
        ]);
    }
}
