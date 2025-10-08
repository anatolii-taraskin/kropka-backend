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
            'name' => 'Existing Tile',
            'col1' => 'Line 1',
            'col2' => 'Line 2',
            'col3' => 'Line 3',
            'is_active' => true,
            'sort' => 1,
        ]);

        $response = $this->actingAs($admin)->get('/admin/prices');

        $response
            ->assertOk()
            ->assertSee(__('admin.prices.title'))
            ->assertSee('Existing Tile');
    }

    public function test_admin_can_create_price_tile(): void
    {
        $admin = $this->createAdminUser();

        $payload = [
            'name' => 'New Tile',
            'col1' => 'First line',
            'col2' => 'Second line',
            'col3' => 'Third line',
            'is_active' => '1',
            'sort' => 5,
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/prices')
            ->post('/admin/prices', $payload);

        $response->assertRedirect('/admin/prices');
        $response->assertSessionHas('status', 'price-created');

        $this->assertDatabaseHas('prices', [
            'name' => 'New Tile',
            'col1' => 'First line',
            'col2' => 'Second line',
            'col3' => 'Third line',
            'is_active' => true,
            'sort' => 5,
        ]);
    }

    public function test_admin_can_update_price_tile(): void
    {
        $admin = $this->createAdminUser();

        $price = Price::create([
            'name' => 'Initial Tile',
            'col1' => 'Line 1',
            'col2' => 'Line 2',
            'col3' => null,
            'is_active' => true,
            'sort' => 3,
        ]);

        $payload = [
            'name' => 'Updated Tile',
            'col1' => 'Updated Line 1',
            'col2' => '',
            'col3' => 'Updated Line 3',
            'is_active' => '0',
            'sort' => 7,
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/prices')
            ->put("/admin/prices/{$price->id}", $payload);

        $response->assertRedirect('/admin/prices');
        $response->assertSessionHas('status', 'price-updated');

        $this->assertDatabaseHas('prices', [
            'id' => $price->id,
            'name' => 'Updated Tile',
            'col1' => 'Updated Line 1',
            'col2' => null,
            'col3' => 'Updated Line 3',
            'is_active' => false,
            'sort' => 7,
        ]);
    }

    public function test_admin_can_delete_price_tile(): void
    {
        $admin = $this->createAdminUser();

        $price = Price::create([
            'name' => 'Tile to Delete',
            'col1' => 'Line 1',
            'col2' => null,
            'col3' => null,
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
            'name' => '',
            'col1' => str_repeat('a', 260),
            'sort' => -1,
        ];

        $response = $this->actingAs($admin)
            ->from('/admin/prices')
            ->post('/admin/prices', $payload);

        $response->assertRedirect('/admin/prices');
        $response->assertSessionHasErrors(['name', 'col1', 'sort'], null, 'createPrice');
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
