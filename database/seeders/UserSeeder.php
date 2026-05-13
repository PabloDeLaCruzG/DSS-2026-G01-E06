<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfessionalProfile;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario principal (el que usa el login automático)
        User::firstOrCreate(
            ['email' => 'user@gamelink.com'],
            [
                'name' => 'Usuario Panel',
                'role' => 'user',
                'password' => Hash::make('123456'),
            ]
        );

        // Admin de prueba
        User::factory()->create([
            'name' => 'Admin GameLink',
            'email' => 'admin@gamelink.com',
            'role' => 'admin',
            'department' =>'Gerencia',
        ]);

        // Usuario normal adicional (con email distinto)
        User::factory()->create([
            'name' => 'Usuario Normal',
            'email' => 'user2@gamelink.com', // 👈 cambiado para evitar duplicado
            'role' => 'user',
        ]);

        // 10 usuarios normales con direcciones
        User::factory(10)
            ->has(Address::factory()->count(2))
            ->create();

        // 5 usuarios profesionales
        User::factory(5)
            ->has(ProfessionalProfile::factory())
            ->has(Address::factory()->count(1))
            ->create();
    }
}