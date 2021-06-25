<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Currency\CurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrenciesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_currencies');
//        $this->middleware('permission:create_currencies',['only'=>['create','store']]);
//        $this->middleware('permission:update_currencies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_currencies',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $currencies = Currency::orderBy('id' ,'desc')->get();
        return view('admin.currencies.index', compact('currencies'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.currencies.create');
    }

    public function store(CurrencyRequest $request)
    {
        if (!auth()->user()->can('create_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        Currency::create($request->all());
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.currency-created'), 'alert-type' => 'success']);
    }

    public function edit(Currency $currency)
    {
        if (!auth()->user()->can('update_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(CurrencyRequest $request, Currency $currency)
    {
        if (!auth()->user()->can('update_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $currency->update($request->all());
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.currency-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Currency $currency)
    {
        if ($currency->countries()->exists()) {
            return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }
        if (!auth()->user()->can('delete_currencies')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $currency->forceDelete();
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.currency-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $currencies = Currency::whereIn('id', $request->ids)->get();
            foreach ($currencies as $currency) {
                if ($currency->countries()->exists()) {
                    return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($currencies as $currency) {
                $currency->forceDelete();
            }
            return redirect()->to('admin/currencies')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
