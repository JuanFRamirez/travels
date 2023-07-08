<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTravelTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cant_create_travel()
    {
        $travel = Travel::factory()->create();

        $response = $this->postJson('api/v1/admin/travels', [
            'name' => $travel->name,
            'is_public' => $travel->is_public,
            'description' => $travel->description,
            'number_of_days' => $travel->number_of_days
        ]);

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cant_add_travel()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));
        $response = $this->actingAs($user)->postJson('api/v1/admin/travels');

        $response->assertStatus(403);
    }

    public function test_admin_user_can_add_travel_successfully()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));

        $response = $this->actingAs($user)->postJson('api/v1/admin/travels', [
            'name' => 'Travel name'
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('api/v1/admin/travels', [
            'name' => 'Travel name',
            'description' => 'description',
            'is_public' => true,
            'number_of_days' => 5
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Travel name']);
    }

    public function test_user_update_travel_successfully()
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));
        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->putJson('api/v1/admin/travels/' . $travel->id, [
            'name' => 'Cool travel to DR',
            'description' => 'Cool new travel',
            'number_of_days' => 8,
            'is_public' => true
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Cool travel to DR']);
    }
}
