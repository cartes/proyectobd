<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_sugar_daddy_can_update_profile(): void
    {
        $user = User::factory()->create(['user_type' => 'sugar_daddy']);
        $this->actingAs($user);

        $updateData = [
            'city' => 'New York',
            'bio' => 'Successful entrepreneur looking for a partner.',
            'income_range' => '100k_250k',
        ];

        $response = $this->put(route('profile.update'), $updateData);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'city' => 'New York',
            'bio' => 'Successful entrepreneur looking for a partner.',
        ]);
        $this->assertDatabaseHas('profile_details', [
            'user_id' => $user->id,
            'income_range' => '100k_250k',
        ]);
    }

    public function test_authenticated_sugar_baby_can_update_profile(): void
    {
        $user = User::factory()->create(['user_type' => 'sugar_baby']);
        $this->actingAs($user);

        $updateData = [
            'city' => 'Los Angeles',
            'bio' => 'Ambitious student seeking a mentor.',
            'personal_style' => 'elegante',
        ];

        $response = $this->put(route('profile.update'), $updateData);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'city' => 'Los Angeles',
            'bio' => 'Ambitious student seeking a mentor.',
        ]);
        $this->assertDatabaseHas('profile_details', [
            'user_id' => $user->id,
            'personal_style' => 'elegante',
        ]);
    }

    public function test_unauthenticated_user_cannot_update_profile(): void
    {
        $response = $this->put(route('profile.update'), []);

        $response->assertRedirect(route('login'));
    }

    public function test_profile_update_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $updateData = [
            'body_type' => 'invalid_body_type',
        ];

        $response = $this->put(route('profile.update'), $updateData);

        $response->assertSessionHasErrors('body_type');
    }
}
