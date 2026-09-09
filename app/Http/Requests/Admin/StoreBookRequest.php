<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'writer_id' => ['nullable', 'integer', 'exists:writers,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'language' => ['required', 'string', 'max:100'],
            'published_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'pages_count' => ['nullable', 'integer', 'min:1'],
            'file_size_mb' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'description_short' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'formats' => ['nullable', 'array'],
            'formats.*' => ['string', 'in:PDF,EPUB,MOBI'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'book_file' => ['nullable', 'file', 'mimes:pdf,epub,mobi', 'max:102400'],
        ];
    }
}
