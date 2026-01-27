<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoverySystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($userA);

        $response = $this->post(route('discover.like', $userB));

        $response->assertSessionHas('success', 'Like enviado ❤️');
        $this->assertDatabaseHas('likes', [
            'user_id' => $userA->id,
            'liked_user_id' => $userB->id,
        ]);
    }

    public function test_user_cannot_like_themselves(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('discover.like', $user));

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'liked_user_id' => $user->id,
        ]);
    }

    public function test_user_can_unlike_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $userA->likes()->attach($userB);

        $this->actingAs($userA);

        $response = $this->delete(route('discover.unlike', $userB));

        $response->assertSessionHas('success', 'Like eliminado');
        $this->assertDatabaseMissing('likes', [
            'user_id' => $userA->id,
            'liked_user_id' => $userB->id,
        ]);
    }

    public function test_liking_a_user_who_liked_back_creates_a_match(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // User B likes user A first
        $userB->likes()->attach($userA);

        $this->actingAs($userA);

        // Now, user A likes user B, which should create a match
        $response = $this->post(route('discover.like', $userB));

        $response->assertSessionHas('success', '¡Es un Match! 🎉💕');
    }
}
