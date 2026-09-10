<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuoteRequest;
use App\Http\Requests\Admin\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(): View
    {
        return view('admin.quotes.index', [
            'activeNav' => 'quotes',
            'quotes' => Quote::orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.quotes.create', ['activeNav' => 'quotes']);
    }

    public function store(StoreQuoteRequest $request): RedirectResponse
    {
        Quote::create($request->validated());

        return redirect()->route('admin.quotes.index')->with('status', 'تم إضافة الاقتباس بنجاح');
    }

    public function edit(Quote $quote): View
    {
        return view('admin.quotes.edit', ['activeNav' => 'quotes', 'quote' => $quote]);
    }

    public function update(UpdateQuoteRequest $request, Quote $quote): RedirectResponse
    {
        $quote->update($request->validated());

        return redirect()->route('admin.quotes.index')->with('status', 'تم حفظ التعديلات بنجاح');
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('status', 'تم حذف الاقتباس بنجاح');
    }
}
