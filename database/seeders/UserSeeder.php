<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfessionalProfile;
use App\Models\Address;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un Super Admin de prueba
        User::factory()->create([
            'name' => 'Admin GameLink',
            'email' => 'admin@gamelink.com',
            'role' => 'admin',
            'department' => 'Gerencia',
        ]);

        // Crear un usuario normal de prueba
        User::factory()->create([
            'name' => 'Usuario Normal',
            'email' => 'user@gamelink.com',
            'role' => 'user',
        ]);

        // Crear 10 Usuarios normales, cada uno con 2 direcciones aleatorias
        User::factory(10)
            ->has(Address::factory()->count(2))
            ->create();

        // Crear 5 Usuarios Profesionales (Tienen Perfil Pro y 1 dirección de la tienda)
        User::factory(5)
            ->has(ProfessionalProfile::factory()) // Le crea el perfil
            ->has(Address::factory()->count(1))   // Le crea la dirección
            ->create();
    }
}
