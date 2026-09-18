@php $faqRows = old('faqs', isset($book) ? $book->faqs->map(fn ($faq) => ['id' => $faq->id, 'question' => $faq->question, 'answer' => $faq->answer, 'is_active' => $faq->is_active])->all() : []); @endphp
<div class="admin-form-section">
  <h3>الأسئلة الشائعة</h3>
  <p style="font-size:11px; color:var(--text-gray); margin-bottom:14px; line-height:1.7;">تُعرض هذه الأسئلة في صفحة الكتاب، وتُستخدم أيضًا لتوليد بيانات FAQPage المنظّمة (JSON-LD) لتحسين ظهور الكتاب في نتائج البحث. الترتيب هنا هو ترتيب الظهور في الصفحة.</p>

  <div id="faqRows">
    @foreach ($faqRows as $index => $faqRow)
      <div class="faq-row" data-faq-row>
        <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faqRow['id'] ?? '' }}">
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label>السؤال</label>
            <input type="text" name="faqs[{{ $index }}][question]" class="admin-input" value="{{ $faqRow['question'] ?? '' }}">
          </div>
          <div class="admin-form-field full">
            <label>الإجابة</label>
            <textarea name="faqs[{{ $index }}][answer]" class="admin-textarea" rows="3">{{ $faqRow['answer'] ?? '' }}</textarea>
          </div>
        </div>
        <div class="admin-toggle-row">
          <label class="checkbox-row"><input type="checkbox" name="faqs[{{ $index }}][is_active]" value="1" @checked(! empty($faqRow['is_active']))> <span>مفعّل ويظهر في صفحة الكتاب</span></label>
          <div class="admin-row-actions">
            <button type="button" class="admin-icon-btn" data-faq-move-up title="نقل لأعلى" aria-label="move up"><i class="fa-solid fa-arrow-up"></i></button>
            <button type="button" class="admin-icon-btn" data-faq-move-down title="نقل لأسفل" aria-label="move down"><i class="fa-solid fa-arrow-down"></i></button>
            <button type="button" class="admin-icon-btn danger" data-faq-remove title="حذف" aria-label="remove"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <button type="button" class="btn btn-outline" id="addFaqRow"><i class="fa-solid fa-plus"></i> إضافة سؤال</button>

  <template id="faqRowTemplate">
    <div class="faq-row" data-faq-row>
      <input type="hidden" name="faqs[__INDEX__][id]" value="">
      <div class="admin-form-grid">
        <div class="admin-form-field full">
          <label>السؤال</label>
          <input type="text" name="faqs[__INDEX__][question]" class="admin-input">
        </div>
        <div class="admin-form-field full">
          <label>الإجابة</label>
          <textarea name="faqs[__INDEX__][answer]" class="admin-textarea" rows="3"></textarea>
        </div>
      </div>
      <div class="admin-toggle-row">
        <label class="checkbox-row"><input type="checkbox" name="faqs[__INDEX__][is_active]" value="1" checked> <span>مفعّل ويظهر في صفحة الكتاب</span></label>
        <div class="admin-row-actions">
          <button type="button" class="admin-icon-btn" data-faq-move-up title="نقل لأعلى" aria-label="move up"><i class="fa-solid fa-arrow-up"></i></button>
          <button type="button" class="admin-icon-btn" data-faq-move-down title="نقل لأسفل" aria-label="move down"><i class="fa-solid fa-arrow-down"></i></button>
          <button type="button" class="admin-icon-btn danger" data-faq-remove title="حذف" aria-label="remove"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>
    </div>
  </template>
</div>
