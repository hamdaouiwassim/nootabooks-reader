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
        $data['creative_path'] = $this->storeCreative($request->file('creative'), $data['type'], null);
        unset($data['creative']);

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
            $data['creative_path'] = $this->storeCreative($request->file('creative'), $data['type'], $advertisement->creative_path);
        }
        unset($data['creative']);

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
        if ($relativePath = $this->relativeStoragePath($advertisement->creative_path)) {
            Storage::disk('public')->delete($relativePath);
            Storage::disk('public')->delete(preg_replace('/(\.\w+)$/', '-md$1', $relativePath));
            Storage::disk('public')->delete(preg_replace('/(\.\w+)$/', '-sm$1', $relativePath));
        }

        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('status', 'تم حذف الإعلان بنجاح');
    }

    /**
     * Static ad types (image_banner/image_text) go through
     * ImageOptimizer::optimizeResponsive() — same "-md"/"-sm" sibling-file
     * convention as Writer::photo (see WriterController) — generating a
     * desktop/tablet/mobile variant of the same upload so ad-slot.blade.php
     * can serve the right size per viewport via <picture>.
     *
     * animated_banner deliberately bypasses ImageOptimizer entirely and
     * gets no size variants — Intervention Image's GD driver only reads/
     * re-encodes the first frame of an animated source, so resizing would
     * mean re-encoding, which is exactly what would destroy the animation.
     * It's stored raw and shown at one size everywhere.
     */
    private function storeCreative(UploadedFile $file, string $type, ?string $oldValue): string
    {
        if ($relativePath = $this->relativeStoragePath($oldValue)) {
            Storage::disk('public')->delete($relativePath);
            Storage::disk('public')->delete(preg_replace('/(\.\w+)$/', '-md$1', $relativePath));
            Storage::disk('public')->delete(preg_replace('/(\.\w+)$/', '-sm$1', $relativePath));
        }

        if ($type === 'animated_banner') {
            $path = $file->store('ads', 'public');
        } else {
            // '' (1200x630) desktop, '-md' (800x420) tablet, '-sm' (480x252)
            // mobile — same 1.9:1 banner aspect ratio at each size.
            $paths = app(ImageOptimizer::class)->optimizeResponsive(
                $file,
                'ads',
                ['' => [1200, 630], '-md' => [800, 420], '-sm' => [480, 252]],
                82,
            );
            $path = $paths[''];
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
