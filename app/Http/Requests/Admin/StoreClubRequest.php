<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:2000'],
            'rules' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم النادي مطلوب.',
            'name.max' => 'اسم النادي طويل جدًا (الحد الأقصى 255 حرفًا).',
            'description.required' => 'وصف النادي مطلوب.',
            'description.max' => 'وصف النادي طويل جدًا (الحد الأقصى 2000 حرف).',
            'rules.max' => 'قوانين النادي طويلة جدًا (الحد الأقصى 2000 حرف).',
        ];
    }
}
