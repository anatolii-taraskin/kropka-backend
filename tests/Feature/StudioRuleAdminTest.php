<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StudioRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioRuleAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_studio_rules_edit_page(): void
    {
        $admin = $this->createAdminUser();

        StudioRule::create([
            'property' => 'rule_01',
            'value' => 'Existing rule text',
        ]);

        $response = $this->actingAs($admin)->get('/admin/studio-rules');

        $response
            ->assertOk()
            ->assertSee(__('admin.studio_rules.title'))
            ->assertSee('Existing rule text');
    }

    public function test_admin_can_update_studio_rules(): void
    {
        $admin = $this->createAdminUser();

        $payload = [
            'studio_rules' => [
                'rule_01' => 'Rule 1 text',
                'rule_02' => 'Rule 2 text',
                'rule_03' => 'Rule 3 text',
                'rule_04' => 'Rule 4 text',
                'rule_05' => 'Rule 5 text',
                'rule_06' => 'Rule 6 text',
                'rule_07' => 'Rule 7 text',
                'rule_08' => 'Rule 8 text',
                'rule_09' => 'Rule 9 text',
                'rule_10' => 'Rule 10 text',
            ],
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/studio-rules')
            ->put('/admin/studio-rules', $payload);

        $response->assertRedirect('/admin/studio-rules');
        $response->assertSessionHas('status', 'studio-rules-updated');

        foreach ($payload['studio_rules'] as $property => $value) {
            $this->assertDatabaseHas('studio_rules', [
                'property' => $property,
                'value' => $value,
            ]);
        }
    }

    public function test_non_admin_cannot_access_studio_rules_editor(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/studio-rules');

        $response->assertForbidden();
    }

    private function createAdminUser(): User
    {
        $adminRole = Role::factory()->create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        return $admin;
    }
}
