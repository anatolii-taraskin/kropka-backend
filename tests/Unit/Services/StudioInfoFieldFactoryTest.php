<?php

namespace Tests\Unit\Services;

use App\Services\StudioInfoFieldFactory;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class StudioInfoFieldFactoryTest extends TestCase
{
    private StudioInfoFieldFactory $factory;

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

        $this->factory = new StudioInfoFieldFactory();
    }

    public function test_field_groups_include_shared_and_localized_definitions(): void
    {
        $groups = $this->factory->fieldGroups([
            'phone' => '+123',
            'name_ru' => 'Имя',
            'name_en' => 'Name',
        ]);

        $this->assertArrayHasKey('shared', $groups);
        $this->assertSame('+123', $groups['shared'][0]['value']);
        $this->assertSame('Phone', $groups['shared'][0]['label']);

        $this->assertArrayHasKey('ru', $groups);
        $this->assertSame('Имя', $groups['ru'][0]['value']);
        $this->assertSame('Name RU', $groups['ru'][0]['label']);

        $this->assertArrayHasKey('en', $groups);
        $this->assertSame('Name', $groups['en'][0]['value']);
    }

    public function test_validation_rules_are_generated_for_each_property(): void
    {
        $rules = $this->factory->validationRules();

        $this->assertSame([
            'studio_infos' => ['required', 'array'],
            'studio_infos.phone' => ['required', 'string'],
            'studio_infos.name_ru' => ['required', 'string'],
            'studio_infos.name_en' => ['required', 'string'],
        ], $rules);
    }

    public function test_validation_attributes_are_localized(): void
    {
        $attributes = $this->factory->validationAttributes();

        $this->assertSame([
            'studio_infos.phone' => 'Phone',
            'studio_infos.name_ru' => 'Name RU',
            'studio_infos.name_en' => 'Name EN',
        ], $attributes);
    }
}
