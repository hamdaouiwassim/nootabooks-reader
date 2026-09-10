<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWriterRequest;
use App\Http\Requests\Admin\UpdateWriterRequest;
use App\Models\Writer;
use App\Services\Image\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WriterController extends Controller
{
    public function index(Request $request): View
    {
        $writers = Writer::query()
            ->withCount('books')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.writers.index', [
            'activeNav' => 'writers',
            'writers' => $writers,
            'totalWriters' => Writer::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.writers.create', ['activeNav' => 'writers']);
    }

    public function store(StoreWriterRequest $request): RedirectResponse
    {
        $writer = Writer::create($this->prepareData($request));

        return redirect()->route('admin.writers.edit', $writer)->with('status', 'تم حفظ المؤلف بنجاح');
    }

    public function edit(Writer $writer): View
    {
        return view('admin.writers.edit', [
            'activeNav' => 'writers',
            'writer' => $writer->loadCount('books'),
            'books' => $writer->books()->with('category')->orderBy('title')->get(),
        ]);
    }

    public function update(UpdateWriterRequest $request, Writer $writer): RedirectResponse
    {
        $writer->update($this->prepareData($request, $writer));

        return redirect()->route('admin.writers.edit', $writer)->with('status', 'تم تحديث بيانات المؤلف بنجاح');
    }

    public function destroy(Writer $writer): RedirectResponse
    {
        if ($relativePath = $this->relativeStoragePath($writer->photo)) {
            Storage::disk('public')->delete($relativePath);
            Storage::disk('public')->delete(preg_replace('/(\.\w+)$/', '-sm$1', $relativePath));
        }

        $writer->delete();

        return redirect()->route('admin.writers.index')->with('status', 'تم حذف المؤلف بنجاح. أصبحت كتبه بدون مؤلف محدد.');
    }

    private function prepareData(Request $request, ?Writer $writer = null): array
    {
        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('photo')) {
            if ($relativePath = $this->relativeStoragePath($writer?->photo)) {
                Storage::disk('public')->delete($relativePath);
                Storage::disk('public')->delete(preg_replace('/(\.\w+)$/', '-sm$1', $relativePath));
            }

            $paths = app(ImageOptimizer::class)->optimizeResponsive(
                $request->file('photo'),
                'writers',
                ['' => [600, 600], '-sm' => [300, 300]],
                85,
            );
            $data['photo'] = force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$paths[''];
        } else {
            unset($data['photo']);
        }

        return $data;
    }

    /**
     * Extract the disk-relative path from a stored photo value, whether it's
     * a full URL (current format) or a bare "storage/..." path (legacy rows
     * saved before photos were stored as absolute URLs).
     */
    private function relativeStoragePath(?string $photo): ?string
    {
        if (! $photo) {
            return null;
        }

        if (str_starts_with($photo, 'storage/')) {
            return substr($photo, strlen('storage/'));
        }

        $marker = '/storage/';
        $position = strpos($photo, $marker);

        return $position !== false ? substr($photo, $position + strlen($marker)) : null;
    }
}
