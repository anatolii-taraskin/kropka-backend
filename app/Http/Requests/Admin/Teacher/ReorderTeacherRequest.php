<?php

namespace App\Http\Requests\Admin\Teacher;

use App\Http\Requests\Admin\ReorderRequest;

class ReorderTeacherRequest extends ReorderRequest
{
    protected function table(): string
    {
        return 'teachers';
    }
}

