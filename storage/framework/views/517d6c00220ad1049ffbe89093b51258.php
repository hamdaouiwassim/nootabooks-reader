<?php $__env->startSection('title', 'تعديل الكتاب: '.$book->title.' - مكتبتي'); ?>

<?php
  $pageTitle = 'تعديل الكتاب';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'تعديل', 'url' => null],
  ];
  $selectedFormats = old('formats', $book->formats ?? []);
?>

<?php $__env->startSection('content'); ?>

<div class="admin-page-head">
  <div>
    <h1>تعديل الكتاب: <?php echo e($book->title); ?></h1>
    <p>حدّث بيانات الكتاب ثم اضغط حفظ التعديلات</p>
  </div>
  <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form id="bookForm" method="POST" action="<?php echo e(route('admin.books.update', $book)); ?>" enctype="multipart/form-data" novalidate>
  <?php echo csrf_field(); ?>
  <?php echo method_field('PUT'); ?>
  <div class="admin-form-layout">

    <!-- ---- Main fields ---- -->
    <div>
      <div class="admin-form-section">
        <h3>معلومات الكتاب</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="bookTitle">عنوان الكتاب</label>
            <input type="text" id="bookTitle" name="title" class="admin-input" value="<?php echo e(old('title', $book->title)); ?>" required>
          </div>
          <div class="admin-form-field">
            <label for="bookWriter">المؤلف</label>
            <select id="bookWriter" name="writer_id" class="admin-select">
              <option value="">بدون مؤلف محدد</option>
              <?php $__currentLoopData = $writers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($writer->id); ?>" <?php if(old('writer_id', $book->writer_id) == $writer->id): echo 'selected'; endif; ?>><?php echo e($writer->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookCategory">التصنيف</label>
            <select id="bookCategory" name="category_id" class="admin-select" required>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php if(old('category_id', $book->category_id) == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookLanguage">اللغة</label>
            <select id="bookLanguage" name="language" class="admin-select">
              <?php $__currentLoopData = ['العربية', 'الإنجليزية', 'مترجم إلى العربية']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(old('language', $book->language) === $language): echo 'selected'; endif; ?>><?php echo e($language); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookYear">سنة النشر</label>
            <input type="number" id="bookYear" name="published_year" class="admin-input" value="<?php echo e(old('published_year', $book->published_year)); ?>" min="1900" max="2100">
          </div>
          <div class="admin-form-field">
            <label for="bookPages">عدد الصفحات</label>
            <input type="number" id="bookPages" name="pages_count" class="admin-input" value="<?php echo e(old('pages_count', $book->pages_count)); ?>" min="1">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف PDF</span>
          </div>
          <div class="admin-form-field full">
            <label for="bookDescShort">نبذة مختصرة</label>
            <input type="text" id="bookDescShort" name="description_short" class="admin-input" value="<?php echo e(old('description_short', $book->description_short)); ?>">
          </div>
          <div class="admin-form-field full">
            <label for="bookDesc">وصف الكتاب</label>
            <textarea id="bookDesc" name="description" class="admin-textarea" rows="5"><?php echo e(old('description', $book->description)); ?></textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>الملف والصيغ</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field">
            <label for="bookFileSize">حجم الملف (ميجابايت)</label>
            <input type="number" id="bookFileSize" name="file_size_mb" class="admin-input" value="<?php echo e(old('file_size_mb', $book->file_size_mb)); ?>" min="0" step="0.01">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف الكتاب</span>
          </div>
          <div class="admin-form-field">
            <label for="bookTags">الوسوم (مفصولة بفاصلة)</label>
            <input type="text" id="bookTags" name="tags" class="admin-input" value="<?php echo e(old('tags', implode('، ', $book->tags ?? []))); ?>">
          </div>
          <div class="admin-form-field full">
            <label>الصيغ المتوفرة</label>
            <div class="admin-toggle-row" style="gap:20px;">
              <?php $__currentLoopData = ['PDF', 'EPUB', 'MOBI']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label style="display:flex; align-items:center; gap:6px; font-weight:600; font-size:13px;">
                  <input type="checkbox" name="formats[]" value="<?php echo e($format); ?>" <?php if(in_array($format, $selectedFormats)): echo 'checked'; endif; ?>> <?php echo e($format); ?>

                </label>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
          <div class="admin-form-field full">
            <label for="bookFile">ملف الكتاب (PDF، EPUB أو MOBI)</label>
            <input type="file" id="bookFile" name="book_file" class="admin-input" accept=".pdf,.epub,.mobi">
            <?php if($book->file_path): ?>
              <div class="admin-file-current">
                <i class="fa-solid fa-file-arrow-down"></i>
                <a href="<?php echo e($book->file_url); ?>" target="_blank" rel="noopener">عرض الملف الحالي</a>
              </div>
            <?php endif; ?>
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
      </div>

      <div class="admin-form-section">
        <h3>إحصائيات</h3>
        <div class="admin-stats-grid" style="grid-template-columns: repeat(3, 1fr);">
          <div class="admin-stat-card">
            <div><strong><?php echo e(number_format($book->downloads_count)); ?></strong><span>تحميل</span></div>
          </div>
          <div class="admin-stat-card">
            <div><strong><?php echo e(number_format($book->rating_average, 1)); ?></strong><span>متوسط التقييم</span></div>
          </div>
          <div class="admin-stat-card">
            <div><strong><?php echo e(number_format($book->rating_count)); ?></strong><span>عدد التقييمات</span></div>
          </div>
        </div>
      </div>

    </div>

    <!-- ---- Cover sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>صورة الغلاف</h3>
        <label class="admin-cover-upload <?php if($book->cover_image): ?> has-image <?php endif; ?>" id="coverUpload">
          <i class="fa-solid fa-image"></i>
          <span>اضغط لرفع صورة الغلاف<br>(JPG أو PNG، نسبة 2:3)</span>
          <img id="coverPreview" <?php if($book->cover_image): ?> src="<?php echo e($book->cover_image_url); ?>" <?php else: ?> hidden <?php endif; ?> alt="معاينة الغلاف">
        </label>
        <input type="file" id="coverInput" name="cover_image" accept="image/*">
      </div>
    </div>

  </div>

  <div class="admin-form-actions">
    <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

<div class="admin-form-section" style="border-color:#f3c7c1; background:#fdecea; margin-top:22px;">
  <h3 style="color:#c0392b;"><i class="fa-solid fa-triangle-exclamation"></i> منطقة الخطر</h3>
  <p style="font-size:13px; color:#7a3129; line-height:1.8; margin-bottom:16px;">حذف هذا الكتاب إجراء نهائي ولا يمكن التراجع عنه. سيتم إزالته فورًا من المنصة ومن مكتبات جميع المستخدمين.</p>
  <form method="POST" action="<?php echo e(route('admin.books.destroy', $book)); ?>" data-confirm-delete data-item-title="<?php echo e($book->title); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="submit" class="btn btn-danger">
      <i class="fa-solid fa-trash"></i> حذف هذا الكتاب نهائيًا
    </button>
  </form>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا الكتاب؟</h3>
    <p>سيتم حذف الكتاب بشكل نهائي من المنصة، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/book-form.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/books/edit.blade.php ENDPATH**/ ?>