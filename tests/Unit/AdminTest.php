<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_modelo_admin_solo_devuelve_administradores(): void
    {
        // Creamos 2 usuarios normales y 1 admin en la tabla users
        User::factory()->count(2)->create(['role' => 'user']);
        User::factory()->create(['role' => 'admin', 'name' => 'El Jefe']);

        // Si consultamos a través de Admin::all(), solo debería devolver 1
        $admins = Admin::all();

        $this->assertCount(1, $admins);
        $this->assertEquals('El Jefe', $admins->first()->name);
    }

    public function test_admin_puede_banear_usuario(): void
    {
        // Creamos un admin usando el modelo Admin
        $userAdmin = User::factory()->create(['role' => 'admin']);

        // Lo recuperamos de la base de datos usando el modelo Admin
        $admin = Admin::find($userAdmin->id);

        $user = User::factory()->create(['is_banned' => false]);

        $this->assertFalse($user->is_banned);

        // El admin banea al usuario
        $admin->banUser($user);

        // Comprobamos en base de datos si se actualizó
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_banned' => true
        ]);
    }
}
