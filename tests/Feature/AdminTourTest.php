<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Travel;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTourTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cant_create_tour()
    {
        $travel = Travel::factory()->create();
        $response = $this->postJson('api/v1/admin/travels/' . $travel->id . '/tours');

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cant_add_tour()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));
        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson('api/v1/admin/travels/' . $travel->id . '/tours');

        $response->assertStatus(403);
    }

    public function test_admin_user_can_add_tour_successfully()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson('api/v1/admin/travels/' . $travel->id . '/tours', [
            'name' => 'Tour name'
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('api/v1/admin/travels/' . $travel->id . '/tours', [
            'name' => 'Tours test name',
            'starting_date' => now()->toDateString(),
            'ending_date' => now()->addDay()->toDateString(),
            'price' => 123.45
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Tours test name']);
    }
}
