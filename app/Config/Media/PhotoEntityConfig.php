<?php

namespace App\Config\Media;

interface PhotoEntityConfig
{
    public function disk(): string;

    public function directory(): string;

    public function sortField(): string;
}
