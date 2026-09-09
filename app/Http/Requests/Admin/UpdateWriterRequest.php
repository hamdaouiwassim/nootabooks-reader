<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWriterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'genre_tag' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'rating_average' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'joined_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'is_featured' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
