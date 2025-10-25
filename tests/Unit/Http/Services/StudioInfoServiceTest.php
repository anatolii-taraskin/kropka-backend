<?php

namespace Tests\Unit\Http\Services;

use App\Http\Services\FieldDefinition;
use App\Http\Services\StudioInfoService;
use App\Models\StudioInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class StudioInfoServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudioInfoService $service;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('studio-info.locales', ['ru', 'en']);
        config()->set('studio-info.shared', [
            'phone' => [
                'rules' => ['required', 'string'],
                'type' => 'text',
                'required' => true,
            ],
        ]);
        config()->set('studio-info.localized', [
            'name' => [
                'rules' => ['required', 'string'],
                'type' => 'text',
                'required' => true,
            ],
        ]);

        Lang::addLines([
            'admin.studio_infos.fields.phone' => 'Phone',
            'admin.studio_infos.fields.name_ru' => 'Name RU',
            'admin.studio_infos.fields.name_en' => 'Name EN',
        ], app()->getLocale());

        $this->service = new StudioInfoService(new StudioInfo());
    }

    public function test_field_groups_include_shared_and_localized_definitions(): void
    {
        StudioInfo::create(['property' => 'phone', 'value' => '+123']);
        StudioInfo::create(['property' => 'name_ru', 'value' => 'Имя']);
        StudioInfo::create(['property' => 'name_en', 'value' => 'Name']);

        $groups = $this->service->fieldGroups();

        $this->assertArrayHasKey('shared', $groups);
        $this->assertInstanceOf(FieldDefinition::class, $groups['shared'][0]);
        $this->assertSame('+123', $groups['shared'][0]->value());
        $this->assertSame('Phone', $groups['shared'][0]->label());

        $this->assertArrayHasKey('ru', $groups);
        $this->assertInstanceOf(FieldDefinition::class, $groups['ru'][0]);
        $this->assertSame('Имя', $groups['ru'][0]->value());
        $this->assertSame('Name RU', $groups['ru'][0]->label());

        $this->assertArrayHasKey('en', $groups);
        $this->assertInstanceOf(FieldDefinition::class, $groups['en'][0]);
        $this->assertSame('Name', $groups['en'][0]->value());
    }

    public function test_validation_rules_are_generated_for_each_property(): void
    {
        $rules = $this->service->validationRules();

        $this->assertSame([
            'studio_infos' => ['required', 'array'],
            'studio_infos.phone' => ['required', 'string'],
            'studio_infos.name_ru' => ['required', 'string'],
            'studio_infos.name_en' => ['required', 'string'],
        ], $rules);
    }

    public function test_save_updates_known_properties_with_fallback_to_empty_string(): void
    {
        StudioInfo::create(['property' => 'phone', 'value' => '+000']);

        $this->service->save([
            'phone' => '+123',
            'name_ru' => null,
        ]);

        $this->assertDatabaseHas('studio_infos', [
            'property' => 'phone',
            'value' => '+123',
        ]);

        $this->assertDatabaseHas('studio_infos', [
            'property' => 'name_ru',
            'value' => '',
        ]);

        $this->assertDatabaseHas('studio_infos', [
            'property' => 'name_en',
            'value' => '',
        ]);
    }
}
