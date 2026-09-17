<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = SearchLog::query()
            ->with('user')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('query', 'like', '%'.$request->string('q').'%');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.search-logs.index', [
            'activeNav' => 'search-logs',
            'logs' => $logs,
            'totalSearches' => SearchLog::count(),
            'topQueries' => SearchLog::topQueries(7),
        ]);
    }

    public function destroy(SearchLog $searchLog): RedirectResponse
    {
        $searchLog->delete();

        return back()->with('status', 'تم حذف السجل بنجاح');
    }

    public function clear(): RedirectResponse
    {
        SearchLog::query()->delete();

        return back()->with('status', 'تم مسح سجل البحث بالكامل');
    }
}
