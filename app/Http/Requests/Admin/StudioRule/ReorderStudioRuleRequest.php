<?php

namespace App\Http\Requests\Admin\StudioRule;

use App\Http\Requests\Admin\ReorderRequest;

class ReorderStudioRuleRequest extends ReorderRequest
{
    protected function table(): string
    {
        return 'studio_rules';
    }
}

