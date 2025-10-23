<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\Price;
use App\Models\StudioInfo;
use App\Models\StudioRule;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_studio_endpoint_returns_localized_data(): void
    {
        StudioInfo::create(['property' => 'name_ru', 'value' => 'Имя']);
        StudioInfo::create(['property' => 'name_en', 'value' => 'Name']);
        StudioInfo::create(['property' => 'phone', 'value' => '+123']);

        $response = $this->getJson('/api/v1/studio?lang=en');

        $response->assertOk()
            ->assertJsonFragment(['locale' => 'en'])
            ->assertJsonPath('data.name', 'Name')
            ->assertJsonPath('data.phone', '+123');
    }

    public function test_prices_endpoint_returns_active_prices_in_requested_locale(): void
    {
        Price::create([
            'name_ru' => 'Цена RU',
            'name_en' => 'Price EN',
            'col1_ru' => 'RU 1',
            'col1_en' => 'EN 1',
            'col2_ru' => 'RU 2',
            'col2_en' => 'EN 2',
            'col3_ru' => null,
            'col3_en' => null,
            'is_active' => true,
            'sort' => 1,
        ]);

        Price::create([
            'name_ru' => 'Inactive RU',
            'name_en' => 'Inactive EN',
            'col1_ru' => 'RU',
            'col1_en' => 'EN',
            'col2_ru' => null,
            'col2_en' => null,
            'col3_ru' => null,
            'col3_en' => null,
            'is_active' => false,
            'sort' => 2,
        ]);

        $response = $this->getJson('/api/v1/prices?lang=en');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Price EN')
            ->assertJsonPath('data.0.columns.0.value', 'EN 1')
            ->assertJsonPath('data.0.columns.1.value', 'EN 2');
    }

    public function test_equipment_endpoint_returns_active_equipment_in_requested_locale(): void
    {
        $item = Equipment::create([
            'name_ru' => 'Усилитель',
            'name_en' => 'Amplifier',
            'description_ru' => 'Описание RU',
            'description_en' => 'Description EN',
            'photo_path' => 'equipment/photo.jpg',
            'is_active' => true,
            'sort' => 1,
        ]);

        Equipment::create([
            'name_ru' => 'Неактивное',
            'name_en' => 'Inactive',
            'description_ru' => 'RU',
            'description_en' => 'EN',
            'photo_path' => null,
            'is_active' => false,
            'sort' => 2,
        ]);

        $response = $this->getJson('/api/v1/equipment?lang=en');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Amplifier')
            ->assertJsonPath('data.0.description', 'Description EN')
            ->assertJsonPath('data.0.photo_url', $item->photoUrl());
    }

    public function test_teachers_endpoint_returns_active_teachers_in_requested_locale(): void
    {
        $teacher = Teacher::create([
            'name_ru' => 'Анна',
            'name_en' => 'Anna',
            'description_ru' => 'Преподаватель',
            'description_en' => 'Teacher',
            'telegram_url' => 'https://t.me/example',
            'photo_path' => 'teachers/anna.jpg',
            'is_active' => true,
            'sort' => 1,
        ]);

        Teacher::create([
            'name_ru' => 'Неактивный',
            'name_en' => 'Inactive',
            'description_ru' => 'RU',
            'description_en' => 'EN',
            'telegram_url' => null,
            'photo_path' => null,
            'is_active' => false,
            'sort' => 2,
        ]);

        $response = $this->getJson('/api/v1/teachers?lang=en');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Anna')
            ->assertJsonPath('data.0.description', 'Teacher')
            ->assertJsonPath('data.0.telegram_url', 'https://t.me/example')
            ->assertJsonPath('data.0.photo_url', $teacher->photoUrl());
    }

    public function test_rules_endpoint_returns_active_rules_in_requested_locale(): void
    {
        StudioRule::create([
            'property' => 'rule_1',
            'value_ru' => 'Правило',
            'value_en' => 'Rule',
            'is_active' => true,
            'sort' => 1,
        ]);

        StudioRule::create([
            'property' => 'rule_2',
            'value_ru' => 'Неактивное',
            'value_en' => 'Inactive',
            'is_active' => false,
            'sort' => 2,
        ]);

        $response = $this->getJson('/api/v1/rules?lang=en');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.property', 'rule_1')
            ->assertJsonPath('data.0.text', 'Rule');
    }
}
