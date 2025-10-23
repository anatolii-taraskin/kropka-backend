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

    public function test_admin_can_view_studio_rules_index_page(): void
    {
        $admin = $this->createAdminUser();

        StudioRule::create([
            'property' => 'rule_01',
            'value_ru' => 'Existing rule text RU',
            'value_en' => 'Existing rule text EN',
            'is_active' => true,
            'sort' => 1,
        ]);

        app()->setLocale('ru');
        session()->put('preferred_locale.admin', 'ru');

        $response = $this->actingAs($admin)->get('/admin/studio-rules');

        $response
            ->assertOk()
            ->assertSee(__('admin.studio_rules.title'))
            ->assertSee('Existing rule text RU')
            ->assertDontSee('Existing rule text EN');

        app()->setLocale('en');
        session()->put('preferred_locale.admin', 'en');

        $response = $this->actingAs($admin)->get('/admin/studio-rules');

        $response
            ->assertOk()
            ->assertSee('Existing rule text EN')
            ->assertDontSee('Existing rule text RU');

        session()->put('preferred_locale.admin', 'ru');
        app()->setLocale('ru');
    }

    public function test_admin_can_create_studio_rule(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)
            ->from('/admin/studio-rules')
            ->post('/admin/studio-rules', [
                'value_ru' => 'New rule text RU',
                'value_en' => 'New rule text EN',
                'is_active' => '1',
            ]);

        $response->assertRedirect('/admin/studio-rules');
        $response->assertSessionHas('status', 'studio-rule-created');

        $this->assertDatabaseHas('studio_rules', [
            'value_ru' => 'New rule text RU',
            'value_en' => 'New rule text EN',
            'is_active' => true,
            'sort' => 1,
        ]);
    }

    public function test_admin_can_update_studio_rule(): void
    {
        $admin = $this->createAdminUser();

        $rule = StudioRule::create([
            'property' => 'rule_01',
            'value_ru' => 'Original text RU',
            'value_en' => 'Original text EN',
            'is_active' => true,
            'sort' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->from("/admin/studio-rules/{$rule->id}/edit")
            ->put("/admin/studio-rules/{$rule->id}", [
                'value_ru' => 'Updated text RU',
                'value_en' => 'Updated text EN',
                'is_active' => '0',
            ]);

        $response->assertRedirect('/admin/studio-rules');
        $response->assertSessionHas('status', 'studio-rule-updated');

        $this->assertDatabaseHas('studio_rules', [
            'id' => $rule->id,
            'value_ru' => 'Updated text RU',
            'value_en' => 'Updated text EN',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_studio_rule(): void
    {
        $admin = $this->createAdminUser();

        $rule = StudioRule::create([
            'property' => 'rule_01',
            'value_ru' => 'Rule to delete RU',
            'value_en' => 'Rule to delete EN',
            'is_active' => true,
            'sort' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->from('/admin/studio-rules')
            ->delete("/admin/studio-rules/{$rule->id}");

        $response->assertRedirect('/admin/studio-rules');
        $response->assertSessionHas('status', 'studio-rule-deleted');

        $this->assertDatabaseMissing('studio_rules', [
            'id' => $rule->id,
        ]);
    }

    public function test_admin_can_reorder_studio_rules(): void
    {
        $admin = $this->createAdminUser();

        $first = StudioRule::create([
            'property' => 'rule_01',
            'value_ru' => 'First RU',
            'value_en' => 'First EN',
            'is_active' => true,
            'sort' => 1,
        ]);

        $second = StudioRule::create([
            'property' => 'rule_02',
            'value_ru' => 'Second RU',
            'value_en' => 'Second EN',
            'is_active' => true,
            'sort' => 2,
        ]);

        $third = StudioRule::create([
            'property' => 'rule_03',
            'value_ru' => 'Third RU',
            'value_en' => 'Third EN',
            'is_active' => true,
            'sort' => 3,
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/studio-rules/reorder', [
            'order' => [$second->id, $third->id, $first->id],
        ]);

        $response
            ->assertOk()
            ->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('studio_rules', [
            'id' => $second->id,
            'sort' => 1,
        ]);

        $this->assertDatabaseHas('studio_rules', [
            'id' => $third->id,
            'sort' => 2,
        ]);

        $this->assertDatabaseHas('studio_rules', [
            'id' => $first->id,
            'sort' => 3,
        ]);
    }

    public function test_non_admin_cannot_access_studio_rules_index(): void
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
