<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_is_seeded_with_credentials(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => config('admin.email'),
            'name' => config('admin.name'),
        ]);

        $admin = User::where('email', config('admin.email'))->first();

        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check(config('admin.password'), $admin->password));

        $adminRole = Role::where('name', 'admin')->first();

        $this->assertNotNull($adminRole);
        $this->assertDatabaseHas('role_user', [
            'role_id' => $adminRole->id,
            'user_id' => $admin->id,
        ]);
    }
}
