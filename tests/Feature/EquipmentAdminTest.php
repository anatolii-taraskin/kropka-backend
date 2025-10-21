<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EquipmentAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_equipment_without_sort_field(): void
    {
        Storage::fake('public');

        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)
            ->from('/admin/equipment')
            ->post('/admin/equipment', [
                'name_ru' => 'Новое оборудование',
                'name_en' => 'New Equipment',
                'description_ru' => 'Описание',
                'description_en' => 'Description',
                'is_active' => '1',
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertRedirect('/admin/equipment');
        $response->assertSessionHas('status', 'equipment-created');

        $this->assertDatabaseHas('equipment', [
            'name_ru' => 'Новое оборудование',
            'name_en' => 'New Equipment',
            'description_ru' => 'Описание',
            'description_en' => 'Description',
            'is_active' => true,
            'sort' => 1,
        ]);
    }

    public function test_admin_can_reorder_equipment_tiles(): void
    {
        $admin = $this->createAdminUser();

        $first = Equipment::create([
            'name_ru' => 'Первое',
            'name_en' => 'First',
            'description_ru' => null,
            'description_en' => null,
            'photo_path' => null,
            'is_active' => true,
            'sort' => 1,
        ]);

        $second = Equipment::create([
            'name_ru' => 'Второе',
            'name_en' => 'Second',
            'description_ru' => null,
            'description_en' => null,
            'photo_path' => null,
            'is_active' => true,
            'sort' => 2,
        ]);

        $third = Equipment::create([
            'name_ru' => 'Третье',
            'name_en' => 'Third',
            'description_ru' => null,
            'description_en' => null,
            'photo_path' => null,
            'is_active' => true,
            'sort' => 3,
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/equipment/reorder', [
            'order' => [$second->id, $third->id, $first->id],
        ]);

        $response
            ->assertOk()
            ->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('equipment', [
            'id' => $second->id,
            'sort' => 1,
        ]);

        $this->assertDatabaseHas('equipment', [
            'id' => $third->id,
            'sort' => 2,
        ]);

        $this->assertDatabaseHas('equipment', [
            'id' => $first->id,
            'sort' => 3,
        ]);
    }

    private function createAdminUser(): User
    {
        $adminRole = Role::factory()->create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        return $admin;
    }
}
