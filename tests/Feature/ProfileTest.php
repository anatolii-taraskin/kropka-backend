<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user)
            ->get('/admin/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_cannot_delete_their_account(): void
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user)
            ->delete('/admin/profile', [
                'password' => 'password',
            ]);

        $response->assertStatus(405);

        $this->assertAuthenticated();
        $this->assertNotNull($user->fresh());
    }

    private function createAdminUser(): User
    {
        $user = User::factory()->create();
        $adminRole = Role::factory()->create(['name' => 'admin']);
        $user->roles()->attach($adminRole);

        return $user;
    }
}
