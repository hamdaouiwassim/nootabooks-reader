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
            'is_coming_soon' => ['nullable', 'boolean'],
            'status' => ['required', 'in:published,draft'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الكتاب مطلوب.',
            'title.max' => 'عنوان الكتاب طويل جدًا (الحد الأقصى 255 حرفًا).',
            'writer_id.exists' => 'المؤلف المحدد غير موجود.',
            'category_id.required' => 'يجب اختيار تصنيف للكتاب.',
            'category_id.exists' => 'التصنيف المحدد غير موجود.',
            'language.required' => 'يجب تحديد لغة الكتاب.',
            'published_year.integer' => 'سنة النشر يجب أن تكون رقمًا صحيحًا.',
            'published_year.min' => 'سنة النشر غير صحيحة.',
            'published_year.max' => 'سنة النشر غير صحيحة.',
            'pages_count.integer' => 'عدد الصفحات يجب أن يكون رقمًا صحيحًا.',
            'pages_count.min' => 'عدد الصفحات يجب أن يكون أكبر من صفر.',
            'file_size_mb.numeric' => 'حجم الملف يجب أن يكون رقمًا.',
            'description_short.max' => 'النبذة المختصرة طويلة جدًا (الحد الأقصى 1000 حرف).',
            'formats.array' => 'صيغ الكتاب غير صحيحة.',
            'formats.*.in' => 'إحدى الصيغ المختارة غير مدعومة.',
            'tags.max' => 'الوسوم طويلة جدًا (الحد الأقصى 1000 حرف).',
            'cover_image.image' => 'يجب أن تكون صورة الغلاف ملف صورة (JPG أو PNG).',
            'cover_image.max' => 'حجم صورة الغلاف يجب ألا يتجاوز 4 ميجابايت.',
            'cover_image.uploaded' => 'فشل رفع صورة الغلاف، تأكد أن حجمها لا يتجاوز الحد المسموح به من الخادم.',
            'book_file.file' => 'يجب رفع ملف صالح للكتاب.',
            'book_file.mimes' => 'صيغة ملف الكتاب غير مدعومة، يجب أن يكون بصيغة PDF أو EPUB أو MOBI.',
            'book_file.max' => 'حجم ملف الكتاب يجب ألا يتجاوز 100 ميجابايت.',
            'book_file.uploaded' => 'فشل رفع ملف الكتاب، تأكد أن حجمه لا يتجاوز الحد المسموح به من الخادم وحاول مرة أخرى.',
            'status.required' => 'يجب تحديد حالة الكتاب.',
            'status.in' => 'حالة الكتاب غير صحيحة.',
        ];
    }
}
