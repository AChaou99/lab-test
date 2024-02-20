<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_success()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/projects');

        $response->assertStatus(200); 
        $response->assertSessionHasNoErrors();
    }
}
