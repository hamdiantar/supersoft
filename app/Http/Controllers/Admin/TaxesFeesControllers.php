<?php

namespace App\Http\Controllers\Admin;

use App\Filters\TaxFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tax\TaxRequest;
use App\Models\TaxesFees;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxesFeesControllers extends Controller
{
    /**
     * @var TaxFilter
     */
    protected $taxFilter;

    public function __construct(TaxFilter $taxFilter)
    {
        $this->taxFilter = $taxFilter;
//        $this->middleware('permission:view_taxes');
//        $this->middleware('permission:create_taxes',['only'=>['create','store']]);
//        $this->middleware('permission:update_taxes',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_taxes',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_taxes')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $taxes = TaxesFees::query();

        if ($request->hasAny((new TaxesFees())->getFillable())
            || $request->has('on_parts')
            || $request->has('id')
        ) {
            $taxes = $this->taxFilter->filter($request);
        }

        return view('admin.taxes.index', ['taxes' => $taxes->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_taxes')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.taxes.create');
    }

    public function store(TaxRequest $request)
    {
        if (!auth()->user()->can('create_taxes')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();

        $data['on_parts'] = $request->has('on_parts') && $data['type'] != 'additional_payments' ? 1 : 0;
        $data['purchase_quotation'] = $request->has('purchase_quotation') ? 1 : 0;

        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        TaxesFees::create($data);

        return redirect()->to('admin/taxes')->with(['message' => __('words.tax-created'), 'alert-type' => 'success']);
    }

    public function edit(TaxesFees $taxesFees)
    {
        if (!auth()->user()->can('update_taxes')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.taxes.edit', compact('taxesFees'));
    }

    public function update(TaxRequest $request, TaxesFees $taxesFees)
    {
        if (!auth()->user()->can('update_taxes')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();

        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        $data['on_parts'] = $request->has('on_parts') && $data['type'] != 'additional_payments' ? 1 : 0;
        $data['purchase_quotation'] = $request->has('purchase_quotation') ? 1 : 0;

        $taxesFees->update($data);

        return redirect()->to('admin/taxes')
            ->with(['message' => __('words.tax-updated'), 'alert-type' => 'success']);
    }

    public function destroy(TaxesFees $taxesFees)
    {
        if (!auth()->user()->can('delete_taxes')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $taxesFees->forceDelete();
        return redirect()->to('admin/taxes')
            ->with(['message' => __('words.tax-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_taxes')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            TaxesFees::whereIn('id', $request->ids)->forceDelete();
            return redirect()->to('admin/taxes')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/taxes')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
