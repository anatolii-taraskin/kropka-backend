<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

abstract class ReorderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'distinct', 'exists:' . $this->table() . ',id'],
        ];
    }

    public function ids(): array
    {
        return $this->validated()['order'];
    }

    abstract protected function table(): string;
}

