<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_panel(): void
    {
        $adminRole = Role::factory()->create(['name' => 'admin']);

        $admin = User::factory()->create([
            'email' => config('admin.email'),
            'password' => bcrypt('password'),
        ]);

        $admin->roles()->attach($adminRole);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk()->assertSee('Admin Panel');
    }

    public function test_non_admin_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }
}
