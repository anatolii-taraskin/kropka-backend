<?php

namespace App\Http\Requests\Admin\Price;

use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Foundation\Http\FormRequest;

abstract class PriceRequest extends FormRequest
{
    use SanitizesAttributes;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ru' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'col1_ru' => ['nullable', 'string', 'max:255'],
            'col1_en' => ['nullable', 'string', 'max:255'],
            'col2_ru' => ['nullable', 'string', 'max:255'],
            'col2_en' => ['nullable', 'string', 'max:255'],
            'col3_ru' => ['nullable', 'string', 'max:255'],
            'col3_en' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name_ru' => __('admin.prices.fields.name_ru'),
            'name_en' => __('admin.prices.fields.name_en'),
            'col1_ru' => __('admin.prices.fields.col1_ru'),
            'col1_en' => __('admin.prices.fields.col1_en'),
            'col2_ru' => __('admin.prices.fields.col2_ru'),
            'col2_en' => __('admin.prices.fields.col2_en'),
            'col3_ru' => __('admin.prices.fields.col3_ru'),
            'col3_en' => __('admin.prices.fields.col3_en'),
            'is_active' => __('admin.prices.fields.is_active'),
        ];
    }

    public function sanitized(): array
    {
        $input = $this->validated();

        return [
            'name_ru' => $this->sanitizeString($input['name_ru'] ?? ''),
            'name_en' => $this->sanitizeString($input['name_en'] ?? ''),
            'col1_ru' => $this->sanitizeNullableString($input['col1_ru'] ?? null),
            'col1_en' => $this->sanitizeNullableString($input['col1_en'] ?? null),
            'col2_ru' => $this->sanitizeNullableString($input['col2_ru'] ?? null),
            'col2_en' => $this->sanitizeNullableString($input['col2_en'] ?? null),
            'col3_ru' => $this->sanitizeNullableString($input['col3_ru'] ?? null),
            'col3_en' => $this->sanitizeNullableString($input['col3_en'] ?? null),
            'is_active' => $this->sanitizeBoolean($input['is_active'] ?? null),
        ];
    }
}

