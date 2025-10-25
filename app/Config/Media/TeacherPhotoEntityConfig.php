<?php

namespace App\Config\Media;

class TeacherPhotoEntityConfig implements PhotoEntityConfig
{
    public function disk(): string
    {
        return 'public';
    }

    public function directory(): string
    {
        return 'teachers';
    }

    public function sortField(): string
    {
        return 'sort';
    }
}
