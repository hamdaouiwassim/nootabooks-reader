<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookReportController extends Controller
{
    public function index(Request $request): View
    {
        $reports = BookReport::with(['book', 'user'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q');
                $query->where(function ($q) use ($term) {
                    $q->where('reporter_name', 'like', "%{$term}%")
                        ->orWhere('reporter_email', 'like', "%{$term}%")
                        ->orWhereHas('book', fn ($bq) => $bq->where('title', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.book-reports.index', [
            'activeNav' => 'book-reports',
            'reports' => $reports,
            'totalReports' => BookReport::count(),
            'pendingReports' => BookReport::where('status', 'pending')->count(),
        ]);
    }

    public function show(BookReport $bookReport): View
    {
        $bookReport->load(['book.writer', 'user']);

        return view('admin.book-reports.show', [
            'activeNav' => 'book-reports',
            'report' => $bookReport,
        ]);
    }

    public function markReviewed(BookReport $bookReport): RedirectResponse
    {
        $bookReport->update(['status' => $bookReport->status === 'pending' ? 'reviewed' : 'pending']);

        return back()->with('status', $bookReport->status === 'reviewed' ? 'تم وضع علامة "تمت المراجعة" على البلاغ' : 'تمت إعادة البلاغ إلى قيد الانتظار');
    }

    public function destroy(BookReport $bookReport): RedirectResponse
    {
        $bookReport->delete();

        return back()->with('status', 'تم حذف البلاغ بنجاح');
    }
}
