<?php

namespace App\Actions\Admin\Teacher;

use App\Http\Requests\Admin\Teacher\TeacherRequest;
use App\Models\Teacher;
use App\Services\SortOrderService;
use Illuminate\Support\Facades\Storage;

class TeacherManager
{
    public function __construct(private readonly SortOrderService $sortOrderService)
    {
    }

    public function create(TeacherRequest $request): Teacher
    {
        $data = $request->sanitized();
        $data['sort'] = $this->sortOrderService->nextSortValue(Teacher::class);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        return Teacher::create($data);
    }

    public function update(TeacherRequest $request, Teacher $teacher): Teacher
    {
        $data = $request->sanitized();

        if ($request->hasFile('photo')) {
            $newPhotoPath = $request->file('photo')->store('teachers', 'public');
            $this->deletePhoto($teacher->photo_path);
            $data['photo_path'] = $newPhotoPath;
        }

        $teacher->update($data);

        return $teacher;
    }

    public function delete(Teacher $teacher): void
    {
        $this->deletePhoto($teacher->photo_path);

        $teacher->delete();
    }

    private function deletePhoto(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}

