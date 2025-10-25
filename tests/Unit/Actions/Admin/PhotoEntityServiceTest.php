<?php

namespace Tests\Unit\Actions\Admin;

use App\Actions\Admin\PhotoEntityService;
use App\Models\Equipment;
use App\Models\Teacher;
use App\Services\SortOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PhotoEntityServiceTest extends TestCase
{
    use RefreshDatabase;

    private PhotoEntityService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new PhotoEntityService(new SortOrderService());
    }

    /**
     * @return array<string, array{class-string, string, string, array<string, mixed>, array<string, mixed>}>
     */
    public static function photoEntityProvider(): array
    {
        return [
            'equipment' => [
                Equipment::class,
                'equipment',
                'equipment',
                [
                    'name_ru' => 'Беговая дорожка',
                    'name_en' => 'Treadmill',
                    'description_ru' => 'Описание',
                    'description_en' => 'Description',
                    'is_active' => true,
                ],
                [
                    'name_ru' => 'Беговая дорожка 2',
                    'name_en' => 'Treadmill 2',
                    'description_ru' => 'Новое описание',
                    'description_en' => 'New description',
                    'is_active' => false,
                ],
            ],
            'teacher' => [
                Teacher::class,
                'teachers',
                'teachers',
                [
                    'name_ru' => 'Алексей',
                    'name_en' => 'Alexey',
                    'description_ru' => 'Инструктор',
                    'description_en' => 'Instructor',
                    'telegram_url' => 'https://t.me/alexey',
                    'is_active' => true,
                ],
                [
                    'name_ru' => 'Мария',
                    'name_en' => 'Maria',
                    'description_ru' => 'Старший инструктор',
                    'description_en' => 'Senior instructor',
                    'telegram_url' => 'https://t.me/maria',
                    'is_active' => false,
                ],
            ],
        ];
    }

    #[DataProvider('photoEntityProvider')]
    public function test_it_creates_entities_with_photos_and_sorted_positions(
        string $modelClass,
        string $table,
        string $directory,
        array $createData,
        array $updateData
    ): void {
        Storage::fake('public');

        $modelClass::query()->create(array_merge($createData, [
            'photo_path' => null,
            'sort' => 1,
        ]));

        $photo = UploadedFile::fake()->image('photo.jpg');

        $entity = $this->service->create($modelClass, $createData, $photo, 'public', $directory);

        $this->assertInstanceOf($modelClass, $entity);
        $this->assertSame(2, $entity->sort);
        $this->assertNotNull($entity->photo_path);
        Storage::disk('public')->assertExists($entity->photo_path);

        $this->assertDatabaseHas($table, [
            'id' => $entity->id,
            'photo_path' => $entity->photo_path,
            'sort' => 2,
        ]);
    }

    #[DataProvider('photoEntityProvider')]
    public function test_it_updates_entities_replacing_photos(
        string $modelClass,
        string $table,
        string $directory,
        array $createData,
        array $updateData
    ): void {
        Storage::fake('public');

        $oldPhotoPath = $directory.'/old-photo.jpg';
        Storage::disk('public')->put($oldPhotoPath, 'old content');

        $entity = $modelClass::query()->create(array_merge($createData, [
            'photo_path' => $oldPhotoPath,
            'sort' => 1,
        ]));

        $newPhoto = UploadedFile::fake()->image('new-photo.jpg');

        $result = $this->service->update($entity, $updateData, $newPhoto, 'public', $directory);

        $this->assertSame($entity->id, $result->id);
        foreach ($updateData as $attribute => $value) {
            $this->assertEquals($value, $result->getAttribute($attribute));
        }

        $this->assertNotNull($result->photo_path);
        $this->assertNotSame($oldPhotoPath, $result->photo_path);
        Storage::disk('public')->assertMissing($oldPhotoPath);
        Storage::disk('public')->assertExists($result->photo_path);

        $this->assertDatabaseHas($table, [
            'id' => $entity->id,
            'photo_path' => $result->photo_path,
        ]);
    }

    #[DataProvider('photoEntityProvider')]
    public function test_it_deletes_entities_and_photos(
        string $modelClass,
        string $table,
        string $directory,
        array $createData,
        array $updateData
    ): void {
        Storage::fake('public');

        $photoPath = $directory.'/delete-photo.jpg';
        Storage::disk('public')->put($photoPath, 'delete content');

        $entity = $modelClass::query()->create(array_merge($createData, [
            'photo_path' => $photoPath,
            'sort' => 1,
        ]));

        $this->service->delete($entity, 'public');

        Storage::disk('public')->assertMissing($photoPath);

        $this->assertDatabaseMissing($table, [
            'id' => $entity->id,
        ]);
    }
}
