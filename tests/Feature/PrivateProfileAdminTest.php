<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateProfileAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['user_type' => 'sugar_daddy']);
        $this->user->profileDetail()->create(['is_private' => false]);
    }

    /**
     * Test admin can see the private profile toggle in user detail
     */
    public function test_admin_can_see_private_profile_toggle()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.moderation.users.show', $this->user));

        $response->assertStatus(200);
        $response->assertSee('Hacer Perfil Privado');
    }

    /**
     * Test admin can toggle private profile status
     */
    public function test_admin_can_toggle_private_profile_status()
    {
        // Toggle to private
        $response = $this->actingAs($this->admin)
            ->post(route('admin.moderation.users.toggle-private', $this->user));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Perfil marcado como privado exitosamente.');

        $this->user->load('profileDetail');
        $this->assertTrue($this->user->profileDetail->is_private);

        // Toggle back to public
        $response = $this->actingAs($this->admin)
            ->post(route('admin.moderation.users.toggle-private', $this->user));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Perfil marcado como público exitosamente.');

        $this->user->load('profileDetail');
        $this->assertFalse($this->user->profileDetail->is_private);
    }

    /**
     * Test non-admin cannot access the toggle route
     */
    public function test_non_admin_cannot_toggle_private_profile()
    {
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->post(route('admin.moderation.users.toggle-private', $this->user));

        $response->assertStatus(403); // Or redirect if using middleware correctly

        $this->user->load('profileDetail');
        $this->assertFalse($this->user->profileDetail->is_private);
    }
}
