<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StudioInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioInfoAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_studio_info_edit_page(): void
    {
        $admin = $this->createAdminUser();

        StudioInfo::create([
            'property' => 'name',
            'value' => 'My Test Studio',
        ]);

        $response = $this->actingAs($admin)->get('/admin/studio-infos');

        $response
            ->assertOk()
            ->assertSee(__('admin.studio_infos.title'))
            ->assertSee('value="My Test Studio"', false);
    }

    public function test_admin_can_update_studio_information(): void
    {
        $admin = $this->createAdminUser();

        $payload = [
            'studio_infos' => [
                'name' => 'Updated Studio',
                'phone' => '+1234567890',
                'address' => '123 Main Street',
                'email' => 'studio@example.com',
                'instagram_url' => 'https://instagram.com/example',
                'facebook_url' => 'https://facebook.com/example',
                'telegram_channel_url' => '',
                'telegram_admin_url' => '',
            ],
        ];

        $response = $this->actingAs($admin)->from('/admin/studio-infos')->put('/admin/studio-infos', $payload);

        $response->assertRedirect('/admin/studio-infos');
        $response->assertSessionHas('status', 'studio-infos-updated');

        foreach ($payload['studio_infos'] as $property => $value) {
            $this->assertDatabaseHas('studio_infos', [
                'property' => $property,
                'value' => $value === '' ? '' : $value,
            ]);
        }
    }

    public function test_non_admin_cannot_access_studio_info_editor(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/studio-infos');

        $response->assertForbidden();
    }

    public function test_urls_must_be_valid_when_provided(): void
    {
        $admin = $this->createAdminUser();

        $payload = [
            'studio_infos' => [
                'name' => 'Studio',
                'phone' => '+1234567890',
                'address' => '123 Main Street',
                'email' => 'studio@example.com',
                'instagram_url' => 'not-a-url',
                'facebook_url' => '',
                'telegram_channel_url' => '',
                'telegram_admin_url' => '',
            ],
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/studio-infos')
            ->put('/admin/studio-infos', $payload);

        $response->assertRedirect('/admin/studio-infos');
        $response->assertSessionHasErrors(['studio_infos.instagram_url']);
    }

    private function createAdminUser(): User
    {
        $adminRole = Role::factory()->create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        return $admin;
    }
}
