<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProfessionalProfile;

class UserLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_comprobar_si_es_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_comprobar_si_puede_vender(): void
    {
        $goodUser = User::factory()->create(['is_banned' => false]);
        $bannedUser = User::factory()->create(['is_banned' => true]);

        $this->assertTrue($goodUser->canSell());
        $this->assertFalse($bannedUser->canSell());
    }

    public function test_comprobar_si_es_profesional_verificado(): void
    {
        // Usuario sin perfil
        $normalUser = User::factory()->create();

        // Usuario con perfil pero NO verificado
        $pendingPro = User::factory()->has(ProfessionalProfile::factory()->state(['is_verified' => false]))->create();

        // Usuario con perfil SI verificado
        $verifiedPro = User::factory()->has(ProfessionalProfile::factory()->state(['is_verified' => true]))->create();

        $this->assertFalse($normalUser->isProfessional());
        $this->assertFalse($pendingPro->isProfessional());
        $this->assertTrue($verifiedPro->isProfessional());
    }
}
