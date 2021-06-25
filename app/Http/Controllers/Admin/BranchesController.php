<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Models\City;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Currency;
use App\Models\NotificationSetting;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\AccountingModule\Models\AccountsTree;
use App\Http\Requests\Admin\Branch\BranchRequest;
use App\Http\Requests\Admin\Branch\BranchUpdateRequest;

class BranchesController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_branches');
//        $this->middleware('permission:create_branches',['only'=>['create','store']]);
//        $this->middleware('permission:update_branches',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_branches',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_branches')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::orderBy('id' ,'desc')->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_branches')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.branches.create');
    }

    public function store(BranchRequest $request)
    {
        if (!auth()->user()->can('create_branches')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if(!authIsSuperAdmin()){

            return redirect()->to('admin/branches')
                ->with(['message' => __('words.cant-access-page'), 'alert-type' => 'error']);
        }

        $branchData = $request->all();
        if ($request->has('logo') && $request->file('logo') !== null) {
            $branchData['logo'] = uploadImage($request->logo, 'branches');
        }

//        if ($request->has('map') && $request->file('map') !== null) {
//            $branchData['map'] = uploadImage($request->map, 'branches');
//        }

        $b = Branch::create($branchData);

        $notificationSetting = NotificationSetting::create([

            'branch_id' => $b->id,
        ]);

        $this->create_account_trees($b->id);
        return redirect()->to('admin/branches')
            ->with(['message' => __('words.branch-created'), 'alert-type' => 'success']);
    }

    private function create_account_trees($branch_id) {
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الأصول',
            'name_en' => 'Assets',
            'account_type_name_ar' => 'ميزانية',
            'account_type_name_en' => 'Budget',
            'account_nature' => 'debit',
            'code' => 1,
            'branch_id' => $branch_id
        ]);
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الخصوم و حقوق الملكية',
            'name_en' => 'Liabilities and equity',
            'account_type_name_ar' => 'ميزانية',
            'account_type_name_en' => 'Budget',
            'account_nature' => 'debit',
            'code' => 2,
            'branch_id' => $branch_id
        ]);
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'المصروفات',
            'name_en' => 'Expense',
            'account_type_name_ar' => 'قائمة الدخل',
            'account_type_name_en' => 'Income List',
            'account_nature' => 'credit',
            'code' => 3,
            'branch_id' => $branch_id
        ]);
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الإيرادات',
            'name_en' => 'Revenue',
            'account_type_name_ar' => 'قائمة الدخل',
            'account_type_name_en' => 'Income List',
            'account_nature' => 'credit',
            'code' => 4,
            'branch_id' => $branch_id
        ]);
    }

    public function edit(Branch $branch)
    {
        if (!auth()->user()->can('update_branches')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.branches.edit', compact('branch'));
    }

    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        if (!auth()->user()->can('update_branches')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if(!authIsSuperAdmin()){

            return redirect()->to('admin/branches')
                ->with(['message' => __('words.cant-access-page'), 'alert-type' => 'error']);
        }

        $branchData = $request->all();
        if ($request->has('logo') && $branchData['logo'] != null) {

            if (File::exists(storage_path('app/public/images/branches/' . $branch->logo)))
                File::delete(storage_path('app/public/images/branches/' . $branch->logo));

            $branchData['logo'] = uploadImage($request->file('logo'), 'branches');
        }

//        if ($request->has('map') && $branchData['map'] != null) {
//
//            if (File::exists(storage_path('app/public/images/branches/' . $branch->map)))
//                File::delete(storage_path('app/public/images/branches/' . $branch->map));
//
//            $branchData['map'] = uploadImage($request->file('map'), 'branches');
//        }


        $branch->update($branchData);
        return redirect()->to('admin/branches')
            ->with(['message' => __('words.branch-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Branch $branch)
    {
        if (!auth()->user()->can('delete_branches')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        if ($branch->users()->exists()) {
            return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }
        $branch->delete();
        return redirect()->to('admin/branches')
            ->with(['message' => __('words.branch-deleted'), 'alert-type' => 'success']);
    }

    public function getCitiesByCountryId(Request $request)
    {
        $cities = City::where('country_id', $request->country_id)->get();
        $country = Country::find($request->country_id);
        $currencies = Currency::find($country->currency_id);
        $htmlCities = '<option value="">' . __('Select City') . '</option>';
        foreach ($cities as $city) {
            $htmlCities .= '<option value="' . $city->id . '">' . $city->name . '</option>';
        }
        $htmlCurrency = '<option  selected value="' . $currencies->id . '">' . $currencies->name . '</option>';
        return response()->json([
            'cities' => $htmlCities,
            'currency' => $htmlCurrency,
        ]);
    }

    public function getAreasByCityId(Request $request)
    {
        $areas = Area::where('city_id', $request->city_id)->get();
        $html = '<option value="">' . __('Choose Area') . '</option>';
        foreach ($areas as $area) {
            $html .= '<option value="' . $area->id . '">' . $area->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_branches')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $branches = Branch::whereIn('id', $request->ids)->get();
            foreach ($branches as $branch) {
                if ($branch->users()->exists()) {
                    return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($branches as $branch) {
                $branch->delete();
            }
            return redirect()->to('admin/branches')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/branches')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
