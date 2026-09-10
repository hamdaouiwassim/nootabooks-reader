<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:400'],
            'author' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'نص الاقتباس مطلوب.',
            'text.max' => 'نص الاقتباس طويل جدًا (الحد الأقصى 400 حرف).',
            'author.max' => 'اسم الكاتب طويل جدًا.',
        ];
    }
}
