<?php

namespace App\Http\Requests\Admin\Teacher;

use App\Models\Teacher;

class UpdateTeacherRequest extends TeacherRequest
{
    protected $errorBag = 'updateTeacher';

    protected function prepareForValidation(): void
    {
        /** @var Teacher|null $teacher */
        $teacher = $this->route('teacher');

        if ($teacher) {
            $this->errorBag = 'updateTeacher' . $teacher->getKey();
        }
    }
}

