<?php

namespace App\Http\Requests\Admin\Equipment;

use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Foundation\Http\FormRequest;

abstract class EquipmentRequest extends FormRequest
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
            'description_ru' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name_ru' => __('admin.equipment.fields.name_ru'),
            'name_en' => __('admin.equipment.fields.name_en'),
            'description_ru' => __('admin.equipment.fields.description_ru'),
            'description_en' => __('admin.equipment.fields.description_en'),
            'photo' => __('admin.equipment.fields.photo'),
            'is_active' => __('admin.equipment.fields.is_active'),
        ];
    }

    public function sanitized(): array
    {
        $input = $this->validated();

        return [
            'name_ru' => $this->sanitizeString($input['name_ru'] ?? ''),
            'name_en' => $this->sanitizeString($input['name_en'] ?? ''),
            'description_ru' => $this->sanitizeNullableString($input['description_ru'] ?? null),
            'description_en' => $this->sanitizeNullableString($input['description_en'] ?? null),
            'is_active' => $this->sanitizeBoolean($input['is_active'] ?? null),
        ];
    }
}

