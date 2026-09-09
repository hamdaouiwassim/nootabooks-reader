<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('books')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', [
            'activeNav' => 'categories',
            'categories' => $categories,
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());
        $category->loadCount('books');

        return response()->json($category, 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());
        $category->loadCount('books');

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse|RedirectResponse
    {
        if ($category->books()->exists()) {
            $message = 'لا يمكن حذف هذا التصنيف لأنه مرتبط بكتب موجودة. أعد تصنيف تلك الكتب أو احذفها أولًا.';

            if (request()->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->withErrors(['category' => $message]);
        }

        $category->delete();

        if (request()->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return back();
    }
}
