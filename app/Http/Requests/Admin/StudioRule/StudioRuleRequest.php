<?php

namespace App\Http\Requests\Admin\StudioRule;

use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Foundation\Http\FormRequest;

abstract class StudioRuleRequest extends FormRequest
{
    use SanitizesAttributes;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value_ru' => ['required', 'string', 'max:2000'],
            'value_en' => ['required', 'string', 'max:2000'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'value_ru' => __('admin.studio_rules.fields.value_ru'),
            'value_en' => __('admin.studio_rules.fields.value_en'),
            'is_active' => __('admin.studio_rules.fields.is_active'),
        ];
    }

    public function sanitized(): array
    {
        $input = $this->validated();

        return [
            'value_ru' => $this->sanitizeString($input['value_ru'] ?? ''),
            'value_en' => $this->sanitizeString($input['value_en'] ?? ''),
            'is_active' => $this->sanitizeBoolean($input['is_active'] ?? null, true),
        ];
    }
}

