<?php

namespace Tests\Unit\Services\Media;

use App\Services\Media\PhotoStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoStorageTest extends TestCase
{
    private PhotoStorage $storage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = new PhotoStorage();
    }

    public function test_it_stores_a_photo_on_the_given_disk(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('photo.jpg');

        $path = $this->storage->store($photo, 'public', 'photos');

        Storage::disk('public')->assertExists($path);
    }

    public function test_it_deletes_a_photo_from_the_given_disk(): void
    {
        Storage::fake('public');

        $path = 'photos/to-delete.jpg';
        Storage::disk('public')->put($path, 'content');

        $this->storage->delete($path, 'public');

        Storage::disk('public')->assertMissing($path);
    }

    public function test_it_ignores_deleting_when_path_is_null(): void
    {
        Storage::fake('public');

        $this->storage->delete(null, 'public');

        Storage::disk('public')->assertDirectoryEmpty('');
    }
}
