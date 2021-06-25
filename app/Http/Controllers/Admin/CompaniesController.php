<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\companies\CompanyRequest;
use App\Models\CarModel;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompaniesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_companies');
//        $this->middleware('permission:create_companies',['only'=>['create','store']]);
//        $this->middleware('permission:update_companies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_companies',['only'=>['destroy','deleteSelected']]);
    }
    public function index()
    {
        if (!auth()->user()->can('view_companies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $companies = Company::orderBy('id' ,'desc')->get();
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_companies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.companies.create');
    }

    public function store(CompanyRequest $request)
    {
        if (!auth()->user()->can('create_companies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        Company::create($request->all());
        return redirect()->to('admin/companies')
            ->with(['message' => __('words.company-created'), 'alert-type' => 'success']);
    }

    public function edit(Company $company)
    {
        if (!auth()->user()->can('update_companies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.companies.edit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company)
    {
        if (!auth()->user()->can('update_companies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $company->update($request->all());
        return redirect()->to('admin/companies')
            ->with(['message' => __('words.company-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Company $company)
    {
        if (!auth()->user()->can('delete_companies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $company->delete();
        return redirect()->to('admin/companies')
            ->with(['message' => __('words.company-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_companies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            Company::whereIn('id', $request->ids)->delete();
            return redirect()->to('admin/companies')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/companies')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }

    public function getModelsByCompany(Request $request)
    {
        $models = CarModel::where('company_id', $request->company_id)->get();
        $htmlModels = '<option value="">' . __('Select Car Model') . '</option>';
        foreach ($models as $model) {
            $htmlModels .= '<option value="' . $model->id . '">' . $model->name . '</option>';
        }
        return response()->json([
            'models' => $htmlModels,
        ]);
    }
}
