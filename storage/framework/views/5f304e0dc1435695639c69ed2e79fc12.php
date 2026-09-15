<?php $__env->startSection('title', 'إضافة كتاب جديد - مكتبتي'); ?>

<?php
  $pageTitle = 'إضافة كتاب جديد';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'إضافة كتاب', 'url' => null],
  ];
?>

<?php $__env->startSection('content'); ?>

<div class="admin-page-head">
  <div>
    <h1>إضافة كتاب جديد</h1>
    <p>أدخل بيانات الكتاب ثم اضغط نشر لإضافته إلى المنصة</p>
  </div>
  <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form id="bookForm" method="POST" action="<?php echo e(route('admin.books.store')); ?>" enctype="multipart/form-data" novalidate>
  <?php echo csrf_field(); ?>
  <div class="admin-form-layout">

    <!-- ---- Main fields ---- -->
    <div>
      <div class="admin-form-section">
        <h3>معلومات الكتاب</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="bookTitle">عنوان الكتاب</label>
            <input type="text" id="bookTitle" name="title" class="admin-input" value="<?php echo e(old('title')); ?>" placeholder="مثال: أرض زيكولا" required>
          </div>
          <div class="admin-form-field full">
            <label for="bookTitleEn">العنوان بالإنجليزية (اختياري)</label>
            <input type="text" id="bookTitleEn" name="title_en" class="admin-input" value="<?php echo e(old('title_en')); ?>" placeholder="Example: Zikola Land" dir="ltr">
          </div>
          <div class="admin-form-field">
            <label for="bookWriterSearch">المؤلف</label>
            <?php $oldWriterName = $writers->firstWhere('id', (int) old('writer_id'))?->name; ?>
            <div class="admin-combobox" id="writerCombobox">
              <input type="text" id="bookWriterSearch" class="admin-input" placeholder="ابحث عن مؤلف ..." autocomplete="off" value="<?php echo e($oldWriterName); ?>">
              <input type="hidden" name="writer_id" id="bookWriterId" value="<?php echo e(old('writer_id')); ?>">
              <div class="admin-combobox-list" id="writerComboboxList" hidden>
                <div class="admin-combobox-option" data-id="" data-name="بدون مؤلف محدد">بدون مؤلف محدد</div>
                <?php $__currentLoopData = $writers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="admin-combobox-option" data-id="<?php echo e($writer->id); ?>" data-name="<?php echo e($writer->name); ?>"><?php echo e($writer->name); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          </div>
          <div class="admin-form-field">
            <label for="bookCategory">التصنيف الأساسي</label>
            <select id="bookCategory" name="category_id" class="admin-select" required>
              <option value="" disabled selected>اختر التصنيف</option>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php if(old('category_id') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="admin-form-field full">
            <label>تصنيفات إضافية (اختياري)</label>
            <div class="admin-checkbox-grid">
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="admin-checkbox-chip">
                  <input type="checkbox" name="categories[]" value="<?php echo e($category->id); ?>" <?php if(in_array($category->id, old('categories', $selectedCategoryIds))): echo 'checked'; endif; ?>>
                  <span><?php echo e($category->name); ?></span>
                </label>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <span class="hint">يمكن ربط الكتاب بأكثر من تصنيف، إلى جانب التصنيف الأساسي أعلاه</span>
          </div>
          <div class="admin-form-field">
            <label for="bookSeriesName">السلسلة (اختياري)</label>
            <input type="text" id="bookSeriesName" name="series_name" list="seriesDatalist" class="admin-input" value="<?php echo e(old('series_name')); ?>" placeholder="مثال: ثلاثية الأرض">
            <datalist id="seriesDatalist">
              <?php $__currentLoopData = $allSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($series->name); ?>">
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </datalist>
            <span class="hint">اكتب اسم سلسلة موجودة لربط الكتاب بها، أو اسمًا جديدًا لإنشاء سلسلة جديدة</span>
          </div>
          <div class="admin-form-field">
            <label for="bookSeriesOrder">رقم الجزء</label>
            <input type="number" id="bookSeriesOrder" name="series_order" class="admin-input" value="<?php echo e(old('series_order')); ?>" min="1" placeholder="مثال: 1">
          </div>
          <div class="admin-form-field">
            <label for="bookLanguage">اللغة</label>
            <select id="bookLanguage" name="language" class="admin-select">
              <?php $__currentLoopData = ['العربية', 'الإنجليزية', 'مترجم إلى العربية']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(old('language', 'العربية') === $language): echo 'selected'; endif; ?>><?php echo e($language); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookStatus">حالة النشر</label>
            <select id="bookStatus" name="status" class="admin-select">
              <option value="published" <?php if(old('status', 'published') === 'published'): echo 'selected'; endif; ?>>منشور (ظاهر للجميع)</option>
              <option value="draft" <?php if(old('status') === 'draft'): echo 'selected'; endif; ?>>مخفي (غير ظاهر على المنصة)</option>
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookYear">سنة النشر</label>
            <input type="number" id="bookYear" name="published_year" class="admin-input" value="<?php echo e(old('published_year')); ?>" placeholder="2024" min="1900" max="2100">
          </div>
          <div class="admin-form-field">
            <label for="bookPages">عدد الصفحات</label>
            <input type="number" id="bookPages" name="pages_count" class="admin-input" value="<?php echo e(old('pages_count')); ?>" placeholder="300" min="1">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف PDF</span>
          </div>
          <div class="admin-form-field full">
            <label for="bookDescShort">نبذة مختصرة</label>
            <input type="text" id="bookDescShort" name="description_short" class="admin-input" value="<?php echo e(old('description_short')); ?>" placeholder="جملة أو جملتان تظهر في قوائم الكتب">
          </div>
          <div class="admin-form-field full">
            <label for="bookDesc">وصف الكتاب</label>
            <textarea id="bookDesc" name="description" class="admin-textarea" rows="5" placeholder="اكتب وصفًا مفصلًا عن الكتاب ..."><?php echo e(old('description')); ?></textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>إعدادات السيو (اختياري)</h3>
        <p style="font-size:11px; color:var(--text-gray); margin-bottom:14px; line-height:1.7;">اتركها فارغة ليتم توليد عنوان ووصف مناسبين لمحركات البحث تلقائيًا من عنوان الكتاب ونبذته.</p>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="bookSeoTitle">عنوان السيو</label>
            <input type="text" id="bookSeoTitle" name="seo_title" class="admin-input" value="<?php echo e(old('seo_title')); ?>" placeholder="مثال: تحميل رواية <?php echo e(old('title') ?: 'العنوان'); ?> PDF وقراءتها أونلاين | نوته بوك">
          </div>
          <div class="admin-form-field full">
            <label for="bookSeoDescription">وصف السيو</label>
            <textarea id="bookSeoDescription" name="seo_description" class="admin-textarea" rows="3" maxlength="500" placeholder="وصف قصير يظهر في نتائج البحث (بحد أقصى 500 حرف)"><?php echo e(old('seo_description')); ?></textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>ملف الكتاب</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field">
            <label for="bookFileSize">حجم الملف (ميجابايت)</label>
            <input type="number" id="bookFileSize" name="file_size_mb" class="admin-input" value="<?php echo e(old('file_size_mb')); ?>" placeholder="2.1" min="0" step="0.01">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف الكتاب أدناه</span>
          </div>
          <div class="admin-form-field">
            <label for="bookTags">الوسوم (مفصولة بفاصلة)</label>
            <input type="text" id="bookTags" name="tags" class="admin-input" value="<?php echo e(old('tags')); ?>" placeholder="إثارة، غموض">
          </div>
          <div class="admin-form-field full">
            <label for="bookFile">ملف الكتاب (PDF)</label>
            <input type="file" id="bookFile" name="book_file" class="admin-input" accept=".pdf">
            <span class="admin-file-name" id="bookFileName"></span>
            <?php $__errorArgs = ['book_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="admin-field-error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>
        <div class="admin-toggle-row" style="margin-top:16px;">
          <div>
            <strong>كتاب قريبًا</strong>
            <p>يظهر الكتاب بدون إمكانية القراءة أو التحميل مع إشارة "قريبًا"</p>
          </div>
          <label class="admin-switch">
            <input type="checkbox" name="is_coming_soon" value="1" <?php if(old('is_coming_soon')): echo 'checked'; endif; ?>>
            <span class="admin-switch-slider"></span>
          </label>
        </div>
        <div class="admin-toggle-row" style="margin-top:16px;">
          <div>
            <strong>منع التحميل</strong>
            <p>يبقى الكتاب ظاهرًا في البحث والقوائم وصفحته، لكن زر التحميل يظهر معطلاً بعبارة "غير متاح للتحميل"</p>
          </div>
          <label class="admin-switch">
            <input type="checkbox" name="download_disabled" value="1" <?php if(old('download_disabled')): echo 'checked'; endif; ?>>
            <span class="admin-switch-slider"></span>
          </label>
        </div>
      </div>
    </div>

    <!-- ---- Cover sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>غلاف الكتاب</h3>
        <p style="font-size:11px; color:var(--text-gray); margin-bottom:14px; line-height:1.7;">يتم ضغط الصورة تلقائيًا إلى 300×450 عند الرفع وتُستخدم بهذا الحجم في كل صفحات الموقع.</p>

        <div>
          <label for="coverInput" class="admin-cover-caption">غلاف الكتاب</label>
          <label class="admin-cover-upload" id="coverUpload">
            <i class="fa-solid fa-image"></i>
            <span>اضغط لرفع غلاف الكتاب<br>(JPG أو PNG، نسبة 2:3)</span>
            <img id="coverPreview" alt="معاينة الغلاف" hidden>
          </label>
          <input type="file" id="coverInput" name="cover_image" accept="image/*">
          <?php $__errorArgs = ['cover_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="admin-field-error"><?php echo e($message); ?></span>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
      </div>
    </div>

  </div>

  <div class="admin-form-actions">
    <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-paper-plane"></i> نشر الكتاب</button>
  </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset_min('assets/js/book-form.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/books/create.blade.php ENDPATH**/ ?>