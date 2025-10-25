<?php

namespace App\Config\Media;

class EquipmentPhotoEntityConfig implements PhotoEntityConfig
{
    public function disk(): string
    {
        return 'public';
    }

    public function directory(): string
    {
        return 'equipment';
    }

    public function sortField(): string
    {
        return 'sort';
    }
}
