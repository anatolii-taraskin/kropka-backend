<?php

namespace App\Http\Requests\Admin;

use App\Services\StudioInfoService;
use Illuminate\Foundation\Http\FormRequest;

class StudioInfoRequest extends FormRequest
{
    public function __construct(private readonly StudioInfoService $studioInfoService)
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
        return $this->studioInfoService->validationRules();
    }

    public function attributes(): array
    {
        return $this->studioInfoService->validationAttributes();
    }

    public function studioInfos(): array
    {
        return $this->validated()['studio_infos'] ?? [];
    }
}

