<?php

namespace App\Http\Requests\Admin\Teacher;

use App\Support\Concerns\SanitizesAttributes;
use Illuminate\Foundation\Http\FormRequest;

abstract class TeacherRequest extends FormRequest
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
            'telegram_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name_ru' => __('admin.teachers.fields.name_ru'),
            'name_en' => __('admin.teachers.fields.name_en'),
            'description_ru' => __('admin.teachers.fields.description_ru'),
            'description_en' => __('admin.teachers.fields.description_en'),
            'telegram_url' => __('admin.teachers.fields.telegram_url'),
            'photo' => __('admin.teachers.fields.photo'),
            'is_active' => __('admin.teachers.fields.is_active'),
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
            'telegram_url' => $this->sanitizeNullableString($input['telegram_url'] ?? null),
            'is_active' => $this->sanitizeBoolean($input['is_active'] ?? null),
        ];
    }
}

