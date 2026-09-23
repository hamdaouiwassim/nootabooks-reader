<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdvertisementRequest;
use App\Http\Requests\Admin\UpdateAdvertisementRequest;
use App\Models\Advertisement;
use App\Services\Image\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    /**
     * Per-field max dimensions — admins upload an independent image for each
     * device tier (not one source auto-resized into variants), but each
     * upload still gets scaled down to a sane cap and converted to WebP via
     * ImageOptimizer, same as every other image upload in this app.
     */
    private const array DIMENSIONS = [
        'creative' => [1200, 630],
        'creative_tablet' => [800, 420],
        'creative_mobile' => [480, 252],
    ];

    public function index(): View
    {
        return view('admin.advertisements.index', [
            'activeNav' => 'advertisements',
            'advertisements' => Advertisement::orderBy('id', 'desc')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.advertisements.create', ['activeNav' => 'advertisements']);
    }

    public function store(StoreAdvertisementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $data['creative_path'] = $this->storeDeviceCreative($request->file('creative'), 'creative', $data['type'], null);

        foreach (['creative_tablet' => 'creative_path_tablet', 'creative_mobile' => 'creative_path_mobile'] as $field => $column) {
            if ($request->hasFile($field)) {
                $data[$column] = $this->storeDeviceCreative($request->file($field), $field, $data['type'], null);
            }
        }

        unset($data['creative'], $data['creative_tablet'], $data['creative_mobile']);

        Advertisement::create($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'تم إضافة الإعلان بنجاح');
    }

    public function edit(Advertisement $advertisement): View
    {
        return view('admin.advertisements.edit', ['activeNav' => 'advertisements', 'advertisement' => $advertisement]);
    }

    public function update(UpdateAdvertisementRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('creative')) {
            $data['creative_path'] = $this->storeDeviceCreative($request->file('creative'), 'creative', $data['type'], $advertisement->creative_path);
        }

        foreach (['creative_tablet' => 'creative_path_tablet', 'creative_mobile' => 'creative_path_mobile'] as $field => $column) {
            if ($request->hasFile($field)) {
                $data[$column] = $this->storeDeviceCreative($request->file($field), $field, $data['type'], $advertisement->{$column});
            }
        }

        unset($data['creative'], $data['creative_tablet'], $data['creative_mobile']);

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'تم حفظ التعديلات بنجاح');
    }

    public function updateActive(Advertisement $advertisement): RedirectResponse
    {
        $advertisement->update(['is_active' => ! $advertisement->is_active]);

        $status = $advertisement->is_active ? 'تم تفعيل الإعلان' : 'تم إيقاف الإعلان';

        return back()->with('status', $status);
    }

    public function destroy(Advertisement $advertisement): RedirectResponse
    {
        foreach ([$advertisement->creative_path, $advertisement->creative_path_tablet, $advertisement->creative_path_mobile] as $value) {
            if ($relativePath = $this->relativeStoragePath($value)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('status', 'تم حذف الإعلان بنجاح');
    }

    /**
     * animated_banner bypasses ImageOptimizer entirely and is stored raw —
     * Intervention Image's GD driver only reads/re-encodes the first frame
     * of an animated source, so resizing would mean re-encoding, which is
     * exactly what would destroy the animation.
     */
    private function storeDeviceCreative(UploadedFile $file, string $field, string $type, ?string $oldValue): string
    {
        if ($relativePath = $this->relativeStoragePath($oldValue)) {
            Storage::disk('public')->delete($relativePath);
        }

        if ($type === 'animated_banner') {
            $path = $file->store('ads', 'public');
        } else {
            [$maxWidth, $maxHeight] = self::DIMENSIONS[$field];
            $path = app(ImageOptimizer::class)->optimize($file, 'ads', $maxWidth, $maxHeight, 82);
        }

        return force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$path;
    }

    private function relativeStoragePath(?string $storedValue): ?string
    {
        if (! $storedValue) {
            return null;
        }

        if (str_starts_with($storedValue, 'storage/')) {
            return substr($storedValue, strlen('storage/'));
        }

        $marker = '/storage/';
        $position = strpos($storedValue, $marker);

        return $position !== false ? substr($storedValue, $position + strlen($marker)) : null;
    }
}
