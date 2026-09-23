<?php

namespace App\Http\Requests\Admin;

use App\Models\Advertisement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys(Advertisement::TYPES))],
            'zone' => ['required', Rule::in(array_keys(Advertisement::ZONES))],
            'creative' => [
                'nullable', 'file', 'max:8192',
                function ($attribute, $value, $fail) {
                    $mimes = $this->input('type') === 'animated_banner' ? ['gif', 'webp'] : ['jpg', 'jpeg', 'png', 'webp'];
                    if ($value && ! in_array(strtolower($value->getClientOriginalExtension()), $mimes, true)) {
                        $fail('صيغة الملف غير مدعومة لهذا النوع من الإعلانات.');
                    }
                },
            ],
            'target_url' => ['required', 'url', 'max:2048'],
            'heading' => ['nullable', 'required_if:type,image_text', 'string', 'max:255'],
            'body_text' => ['nullable', 'required_if:type,image_text', 'string', 'max:500'],
            'alt_text' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الإعلان مطلوب.',
            'type.required' => 'يجب تحديد نوع الإعلان.',
            'type.in' => 'نوع الإعلان غير صحيح.',
            'zone.required' => 'يجب تحديد مكان عرض الإعلان.',
            'zone.in' => 'مكان العرض المحدد غير صحيح.',
            'creative.max' => 'حجم ملف الإعلان يجب ألا يتجاوز 8 ميجابايت.',
            'target_url.required' => 'رابط الوجهة مطلوب.',
            'target_url.url' => 'رابط الوجهة غير صحيح.',
            'heading.required_if' => 'يجب كتابة عنوان الإعلان لهذا النوع.',
            'body_text.required_if' => 'يجب كتابة نص الإعلان لهذا النوع.',
            'alt_text.required' => 'النص البديل للصورة مطلوب (لتحسين محركات البحث وإمكانية الوصول).',
            'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البداية أو يساويه.',
        ];
    }
}
