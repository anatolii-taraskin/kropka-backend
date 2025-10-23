<?php

namespace Tests\Feature;

use App\Models\Price;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_prices_page(): void
    {
        $admin = $this->createAdminUser();

        Price::create([
            'name_ru' => 'Существующий блок',
            'name_en' => 'Existing Tile',
            'col1_ru' => 'Строка 1',
            'col1_en' => 'Line 1',
            'col2_ru' => 'Строка 2',
            'col2_en' => 'Line 2',
            'col3_ru' => 'Строка 3',
            'col3_en' => 'Line 3',
            'is_active' => true,
            'sort' => 1,
        ]);

        $response = $this->actingAs($admin)->get('/admin/prices');

        $response
            ->assertOk()
            ->assertSee(__('admin.prices.title'))
            ->assertSee('Существующий блок')
            ->assertSee('Строка 1')
            ->assertDontSee('Existing Tile')
            ->assertDontSee('Line 1');
    }

    public function test_admin_can_view_prices_page_in_english_locale(): void
    {
        $admin = $this->createAdminUser();

        Price::create([
            'name_ru' => 'Существующий блок',
            'name_en' => 'Existing Tile',
            'col1_ru' => 'Строка 1',
            'col1_en' => 'Line 1',
            'col2_ru' => 'Строка 2',
            'col2_en' => 'Line 2',
            'col3_ru' => 'Строка 3',
            'col3_en' => 'Line 3',
            'is_active' => true,
            'sort' => 1,
        ]);

        app()->setLocale('en');

        $response = $this->actingAs($admin)->get('/admin/prices');

        $response
            ->assertOk()
            ->assertSee(__('admin.prices.title'))
            ->assertSee('Existing Tile')
            ->assertSee('Line 1')
            ->assertDontSee('Существующий блок')
            ->assertDontSee('Строка 1');
    }

    public function test_admin_can_create_price_tile(): void
    {
        $admin = $this->createAdminUser();

        $payload = [
            'name_ru' => 'Новый блок',
            'name_en' => 'New Tile',
            'col1_ru' => 'Первая строка',
            'col1_en' => 'First line',
            'col2_ru' => 'Вторая строка',
            'col2_en' => 'Second line',
            'col3_ru' => 'Третья строка',
            'col3_en' => 'Third line',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/prices')
            ->post('/admin/prices', $payload);

        $response->assertRedirect('/admin/prices');
        $response->assertSessionHas('status', 'price-created');

        $this->assertDatabaseHas('prices', [
            'name_ru' => 'Новый блок',
            'name_en' => 'New Tile',
            'col1_ru' => 'Первая строка',
            'col1_en' => 'First line',
            'col2_ru' => 'Вторая строка',
            'col2_en' => 'Second line',
            'col3_ru' => 'Третья строка',
            'col3_en' => 'Third line',
            'is_active' => true,
            'sort' => 1,
        ]);
    }

    public function test_admin_can_update_price_tile(): void
    {
        $admin = $this->createAdminUser();

        $price = Price::create([
            'name_ru' => 'Исходный блок',
            'name_en' => 'Initial Tile',
            'col1_ru' => 'Строка 1',
            'col1_en' => 'Line 1',
            'col2_ru' => 'Строка 2',
            'col2_en' => 'Line 2',
            'col3_ru' => null,
            'col3_en' => null,
            'is_active' => true,
            'sort' => 3,
        ]);

        $payload = [
            'name_ru' => 'Обновлённый блок',
            'name_en' => 'Updated Tile',
            'col1_ru' => 'Обновлённая строка 1',
            'col1_en' => 'Updated Line 1',
            'col2_ru' => '',
            'col2_en' => '',
            'col3_ru' => 'Обновлённая строка 3',
            'col3_en' => 'Updated Line 3',
            'is_active' => '0',
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/prices')
            ->put("/admin/prices/{$price->id}", $payload);

        $response->assertRedirect('/admin/prices');
        $response->assertSessionHas('status', 'price-updated');

        $this->assertDatabaseHas('prices', [
            'id' => $price->id,
            'name_ru' => 'Обновлённый блок',
            'name_en' => 'Updated Tile',
            'col1_ru' => 'Обновлённая строка 1',
            'col1_en' => 'Updated Line 1',
            'col2_ru' => null,
            'col2_en' => null,
            'col3_ru' => 'Обновлённая строка 3',
            'col3_en' => 'Updated Line 3',
            'is_active' => false,
            'sort' => 3,
        ]);
    }

    public function test_admin_can_reorder_price_tiles(): void
    {
        $admin = $this->createAdminUser();

        $first = Price::create([
            'name_ru' => 'Первый',
            'name_en' => 'First',
            'col1_ru' => null,
            'col1_en' => null,
            'col2_ru' => null,
            'col2_en' => null,
            'col3_ru' => null,
            'col3_en' => null,
            'is_active' => true,
            'sort' => 1,
        ]);

        $second = Price::create([
            'name_ru' => 'Второй',
            'name_en' => 'Second',
            'col1_ru' => null,
            'col1_en' => null,
            'col2_ru' => null,
            'col2_en' => null,
            'col3_ru' => null,
            'col3_en' => null,
            'is_active' => true,
            'sort' => 2,
        ]);

        $third = Price::create([
            'name_ru' => 'Третий',
            'name_en' => 'Third',
            'col1_ru' => null,
            'col1_en' => null,
            'col2_ru' => null,
            'col2_en' => null,
            'col3_ru' => null,
            'col3_en' => null,
            'is_active' => true,
            'sort' => 3,
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/prices/reorder', [
            'order' => [$third->id, $first->id, $second->id],
        ]);

        $response
            ->assertOk()
            ->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('prices', [
            'id' => $third->id,
            'sort' => 1,
        ]);

        $this->assertDatabaseHas('prices', [
            'id' => $first->id,
            'sort' => 2,
        ]);

        $this->assertDatabaseHas('prices', [
            'id' => $second->id,
            'sort' => 3,
        ]);
    }

    public function test_admin_can_delete_price_tile(): void
    {
        $admin = $this->createAdminUser();

        $price = Price::create([
            'name_ru' => 'Блок для удаления',
            'name_en' => 'Tile to Delete',
            'col1_ru' => 'Строка 1',
            'col1_en' => 'Line 1',
            'col2_ru' => null,
            'col2_en' => null,
            'col3_ru' => null,
            'col3_en' => null,
            'is_active' => true,
            'sort' => 2,
        ]);

        $response = $this->actingAs($admin)
            ->from('/admin/prices')
            ->delete("/admin/prices/{$price->id}");

        $response->assertRedirect('/admin/prices');
        $response->assertSessionHas('status', 'price-deleted');

        $this->assertDatabaseMissing('prices', [
            'id' => $price->id,
        ]);
    }

    public function test_validation_errors_are_returned_for_invalid_data(): void
    {
        $admin = $this->createAdminUser();

        $payload = [
            'name_ru' => '',
            'name_en' => '',
            'col1_ru' => str_repeat('a', 260),
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/prices')
            ->post('/admin/prices', $payload);

        $response->assertRedirect('/admin/prices');
        $response->assertSessionHasErrors(['name_ru', 'name_en', 'col1_ru'], null, 'createPrice');
    }

    public function test_non_admin_cannot_access_prices_manager(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/prices');

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
