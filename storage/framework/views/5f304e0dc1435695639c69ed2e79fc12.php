<?php $__env->startSection('title', 'إضافة كتاب جديد - مكتبتي'); ?>

<?php
  $pageTitle = 'إضافة كتاب جديد';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'إضافة كتاب', 'url' => null],
  ];
  $selectedFormats = old('formats', []);
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
          <div class="admin-form-field">
            <label for="bookWriter">المؤلف</label>
            <select id="bookWriter" name="writer_id" class="admin-select">
              <option value="">بدون مؤلف محدد</option>
              <?php $__currentLoopData = $writers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($writer->id); ?>" <?php if(old('writer_id') == $writer->id): echo 'selected'; endif; ?>><?php echo e($writer->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookCategory">التصنيف</label>
            <select id="bookCategory" name="category_id" class="admin-select" required>
              <option value="" disabled selected>اختر التصنيف</option>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php if(old('category_id') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
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
        <h3>الملف والصيغ</h3>
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
    </div>

    <!-- ---- Cover sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>صورة الغلاف</h3>
        <label class="admin-cover-upload" id="coverUpload">
          <i class="fa-solid fa-image"></i>
          <span>اضغط لرفع صورة الغلاف<br>(JPG أو PNG، نسبة 2:3)</span>
          <img id="coverPreview" alt="معاينة الغلاف" hidden>
        </label>
        <input type="file" id="coverInput" name="cover_image" accept="image/*">
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
<script src="<?php echo e(asset_min('assets/js/book-form.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/books/create.blade.php ENDPATH**/ ?>