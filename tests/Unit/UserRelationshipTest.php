<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\ProfessionalProfile;

class UserRelationshipTest extends TestCase
{
    use RefreshDatabase; // Resetea la BD en cada test

    public function test_un_usuario_tiene_muchas_direcciones(): void
    {
        // Creamos 1 usuario con 3 direcciones usando nuestros factories
        $user = User::factory()->has(Address::factory()->count(3))->create();

        // Comprobamos que la relación devuelve 3 elementos
        $this->assertCount(3, $user->addresses);
        $this->assertInstanceOf(Address::class, $user->addresses->first());
    }

    public function test_una_direccion_pertenece_a_un_usuario(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        // Comprobamos la relación inversa
        $this->assertEquals($user->id, $address->user->id);
        $this->assertInstanceOf(User::class, $address->user);
    }

    public function test_un_usuario_tiene_un_perfil_profesional(): void
    {
        $user = User::factory()->has(ProfessionalProfile::factory())->create();

        // Comprobamos que el perfil existe y es del tipo correcto
        $this->assertNotNull($user->professionalProfile);
        $this->assertInstanceOf(ProfessionalProfile::class, $user->professionalProfile);
    }

    public function test_un_perfil_profesional_pertenece_a_un_usuario(): void
    {
        $user = User::factory()->create();
        $profile = ProfessionalProfile::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $profile->user->id);
    }
}
