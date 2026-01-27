<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mutual_like_creates_a_match(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // User A likes User B
        $userA->likes()->attach($userB);

        // User B likes User A
        $userB->likes()->attach($userA);

        $this->assertTrue($userA->hasMatchWith($userB));
        $this->assertTrue($userB->hasMatchWith($userA));
    }
}
