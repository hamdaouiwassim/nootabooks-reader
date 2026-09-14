<?php

/**
 * Local browser UI for the cover-optimizer — pick a photo with a file picker
 * (or drag & drop) instead of typing a path on the command line, then
 * download the 3 generated sizes. Same resize logic as optimize-cover.php
 * (see scripts/lib/cover-variants.php) — this is just a friendlier front end
 * for it, still standalone/local, no Laravel app or database involved.
 *
 * Run:
 *   php -S localhost:8000 scripts/optimize-cover-server.php
 * Then open http://localhost:8000 in your browser.
 */

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/lib/cover-variants.php';

$storageDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'nootabook-cover-optimizer';
if (! is_dir($storageDir) && ! mkdir($storageDir, 0755, true) && ! is_dir($storageDir)) {
    http_response_code(500);
    exit('Could not create a temp storage directory.');
}

// Serving a previously generated file: ?download=<token>&size=lg|md|sm
if (isset($_GET['download'], $_GET['size'])) {
    $token = basename((string) $_GET['download']);
    $size = (string) $_GET['size'];

    if (! array_key_exists($size, COVER_VARIANTS)) {
        http_response_code(400);
        exit('Invalid size.');
    }

    $matches = glob($storageDir.DIRECTORY_SEPARATOR.$token.DIRECTORY_SEPARATOR."*-{$size}.webp");

    if (! $matches) {
        http_response_code(404);
        exit('File not found — it may have expired, try generating again.');
    }

    $path = $matches[0];
    header('Content-Type: image/webp');
    header('Content-Disposition: attachment; filename="'.basename($path).'"');
    header('Content-Length: '.filesize($path));
    readfile($path);
    exit;
}

$error = null;
$results = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cover'])) {
    $file = $_FILES['cover'];

    if ($file['error'] !== UPLOAD_ERR_OK || ! is_uploaded_file($file['tmp_name'])) {
        $error = 'رفع الملف فشل، حاول مرة أخرى.';
    } else {
        $ext = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));

        if (! in_array($ext, COVER_EXTENSIONS, true)) {
            $error = 'صيغة غير مدعومة. استخدم jpg أو jpeg أو png أو webp.';
        } else {
            $token = bin2hex(random_bytes(8));
            $outputDir = $storageDir.DIRECTORY_SEPARATOR.$token;
            mkdir($outputDir, 0755, true);

            $baseName = pathinfo((string) $file['name'], PATHINFO_FILENAME);
            $baseName = preg_replace('/[^A-Za-z0-9_-]+/', '-', $baseName) ?: 'cover';

            $sourcePath = $outputDir.DIRECTORY_SEPARATOR.'source.'.$ext;
            move_uploaded_file($file['tmp_name'], $sourcePath);

            try {
                $results = [
                    'token' => $token,
                    'files' => generateCoverVariants($sourcePath, $baseName, $outputDir),
                ];
            } catch (\Throwable $e) {
                $error = 'تعذّرت معالجة هذه الصورة — تأكد أن الملف صورة صالحة غير تالفة. ('.$e->getMessage().')';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>مولّد أحجام غلاف الكتاب</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; background: #f4f5f7; margin: 0; padding: 40px 16px; display: flex; justify-content: center; }
  .card { background: #fff; border-radius: 12px; padding: 32px; max-width: 480px; width: 100%; box-shadow: 0 4px 20px rgba(0,0,0,.06); }
  h1 { font-size: 20px; margin: 0 0 8px; }
  p.hint { color: #666; font-size: 14px; margin: 0 0 24px; line-height: 1.6; }
  .drop { display: block; border: 2px dashed #c9ccd3; border-radius: 10px; padding: 32px 16px; text-align: center; cursor: pointer; transition: .15s; }
  .drop:hover, .drop.drag { border-color: #3d5afe; background: #f5f7ff; }
  .drop input { display: none; }
  .drop .filename { display: block; margin-top: 8px; font-weight: 600; color: #3d5afe; word-break: break-all; }
  button { margin-top: 20px; width: 100%; padding: 12px; border: none; border-radius: 8px; background: #3d5afe; color: #fff; font-size: 15px; font-weight: 700; cursor: pointer; }
  button:hover { background: #2f45cc; }
  .error { background: #fdeaea; color: #c0392b; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
  .success { background: #eafaf0; color: #1f8a4c; padding: 12px; border-radius: 8px; margin-top: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
  .results { margin-top: 8px; }
  .results h2 { font-size: 15px; margin: 24px 0 4px; }
  .result-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-top: 1px solid #eee; }
  .result-row .meta { font-size: 13px; color: #555; }
  .result-row a { background: #eef0ff; color: #3d5afe; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 14px; }
  .result-row a:hover { background: #dde1ff; }
</style>
</head>
<body>
<div class="card">
  <h1>مولّد أحجام غلاف الكتاب</h1>
  <p class="hint">اختر صورة الغلاف الأصلية، وسيتم توليد 3 نسخ بصيغة WebP بنفس مقاسات نموذج الكتاب في لوحة التحكم: كبير (800×1200)، متوسط (600×900)، صغير (300×450).</p>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" id="uploadForm">
    <label class="drop" id="dropZone">
      <span id="dropLabel">اسحب الصورة هنا أو اضغط للاختيار</span>
      <span class="filename" id="fileName"></span>
      <input type="file" name="cover" accept=".jpg,.jpeg,.png,.webp" id="fileInput" required>
    </label>
    <button type="submit">توليد الأحجام</button>
  </form>

  <?php if ($results): ?>
    <div class="success">✓ تم توليد الأحجام الثلاثة بنجاح</div>
    <div class="results">
      <h2>الملفات جاهزة:</h2>
      <?php foreach ($results['files'] as $size => $info): ?>
        <div class="result-row">
          <div class="meta"><?= htmlspecialchars(COVER_VARIANT_LABELS[$size]) ?> — <?= $info['width'] ?>×<?= $info['height'] ?></div>
          <a href="?download=<?= urlencode($results['token']) ?>&size=<?= urlencode($size) ?>">تنزيل</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<script>
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const fileName = document.getElementById('fileName');
  const dropLabel = document.getElementById('dropLabel');

  function showFile(name) {
    fileName.textContent = name;
    dropLabel.style.display = 'none';
  }

  fileInput.addEventListener('change', () => {
    if (fileInput.files[0]) showFile(fileInput.files[0].name);
  });

  ['dragover', 'dragleave', 'drop'].forEach((evt) => {
    dropZone.addEventListener(evt, (e) => {
      e.preventDefault();
      dropZone.classList.toggle('drag', evt === 'dragover');
    });
  });

  dropZone.addEventListener('drop', (e) => {
    if (e.dataTransfer.files[0]) {
      fileInput.files = e.dataTransfer.files;
      showFile(e.dataTransfer.files[0].name);
    }
  });
</script>
</body>
</html>
