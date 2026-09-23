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
        }

        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('status', 'تم حذف الإعلان بنجاح');
    }

    /**
     * Static ad types (image_banner/image_text) go through the shared
     * ImageOptimizer pipeline like every other image upload in this app.
     * animated_banner deliberately bypasses it — ImageOptimizer uses
     * Intervention Image's GD driver, which only reads/re-encodes the first
     * frame of an animated source, so routing a GIF through it would
     * silently flatten the animation. The animated type is stored raw.
     */
    private function storeCreative(UploadedFile $file, string $type, ?string $oldValue): string
    {
        if ($relativePath = $this->relativeStoragePath($oldValue)) {
            Storage::disk('public')->delete($relativePath);
        }

        $path = $type === 'animated_banner'
            ? $file->store('ads', 'public')
            : app(ImageOptimizer::class)->optimize($file, 'ads', 1200, 630, 82);

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
