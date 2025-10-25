<?php

namespace App\Actions\Admin\Teacher;

use App\Actions\Admin\PhotoEntityService;
use App\Http\Requests\Admin\Teacher\TeacherRequest;
use App\Models\Teacher;

class TeacherManager
{
    public function __construct(private readonly PhotoEntityService $photoEntityService)
    {
    }

    public function create(TeacherRequest $request): Teacher
    {
        $data = $request->sanitized();

        /** @var Teacher $teacher */
        $teacher = $this->photoEntityService->create(
            Teacher::class,
            $data,
            $request->file('photo'),
            'public',
            'teachers'
        );

        return $teacher;
    }

    public function update(TeacherRequest $request, Teacher $teacher): Teacher
    {
        $data = $request->sanitized();

        /** @var Teacher $teacher */
        $teacher = $this->photoEntityService->update(
            $teacher,
            $data,
            $request->file('photo'),
            'public',
            'teachers'
        );

        return $teacher;
    }

    public function delete(Teacher $teacher): void
    {
        $this->photoEntityService->delete($teacher, 'public');
    }
}
