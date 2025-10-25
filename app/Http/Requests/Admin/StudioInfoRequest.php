<?php

namespace App\Http\Requests\Admin;

use App\Services\StudioInfoFieldFactory;
use Illuminate\Foundation\Http\FormRequest;

class StudioInfoRequest extends FormRequest
{
    public function __construct(private readonly StudioInfoFieldFactory $fieldFactory)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $studioInfos = $this->input('studio_infos');

        if (! is_array($studioInfos)) {
            return;
        }

        foreach ($studioInfos as $key => $value) {
            if ($value === '') {
                $studioInfos[$key] = null;
            }
        }

        $this->merge(['studio_infos' => $studioInfos]);
    }

    public function rules(): array
    {
        return $this->fieldFactory->validationRules();
    }

    public function attributes(): array
    {
        return $this->fieldFactory->validationAttributes();
    }

    public function studioInfos(): array
    {
        return $this->validated()['studio_infos'] ?? [];
    }
}

