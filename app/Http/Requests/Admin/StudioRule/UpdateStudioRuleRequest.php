<?php

namespace App\Http\Requests\Admin\StudioRule;

use App\Models\StudioRule;

class UpdateStudioRuleRequest extends StudioRuleRequest
{
    protected $errorBag = 'updateStudioRule';

    protected function prepareForValidation(): void
    {
        /** @var StudioRule|null $studioRule */
        $studioRule = $this->route('studioRule');

        if ($studioRule) {
            $this->errorBag = 'updateStudioRule' . $studioRule->getKey();
        }
    }
}

