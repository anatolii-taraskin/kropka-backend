<?php

namespace Tests\Unit\Services;

use App\Models\StudioInfo;
use App\Services\StudioInfoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioInfoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StudioInfoRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new StudioInfoRepository(new StudioInfo());
    }

    public function test_all_returns_property_value_pairs(): void
    {
        StudioInfo::create(['property' => 'phone', 'value' => '+123']);
        StudioInfo::create(['property' => 'name_ru', 'value' => 'Имя']);

        $this->assertSame([
            'phone' => '+123',
            'name_ru' => 'Имя',
        ], $this->repository->all());
    }

    public function test_save_updates_known_properties_with_fallback_to_empty_string(): void
    {
        $this->repository->save([
            'phone' => '+123',
            'name_ru' => null,
        ], ['phone', 'name_ru', 'name_en']);

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
