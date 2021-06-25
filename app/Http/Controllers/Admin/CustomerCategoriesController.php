<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CustomerCategories\CreateCustomerCategoriesRequest;
use App\Http\Requests\Admin\CustomerCategories\UpdateCustomerCategoryRequest;
use App\Http\Requests\Admin\ServicesTypes\CreateRequest;
use App\Models\Branch;
use App\Models\CustomerCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerCategoriesController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_customer_groups');
//        $this->middleware('permission:create_customer_groups',['only'=>['create','store']]);
//        $this->middleware('permission:update_customer_groups',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_customer_groups',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $categories = CustomerCategory::orderBy('id','DESC');

        if($request->has('name') && $request['name'] != '')
            $categories->where('id',$request['name']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $categories->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $categories->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $categories->where('status',0);

        $categories = $categories->get();

        $branches = filterSetting() ? Branch::all()->pluck('name','id') : null;
        $customers_categories = filterSetting() ? CustomerCategory::all()->pluck('name','id') : null;

        return view('admin.customer-categories.index',compact('categories','branches','customers_categories'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        return view('admin.customer-categories.create',compact('branches'));
    }

    public function store(CreateCustomerCategoriesRequest $request)
    {
        if (!auth()->user()->can('create_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            $data['status'] = 0;

            if(!authIsSuperAdmin()){

                $data['branch_id'] = auth()->user()->branch_id;
            }

            if($request->has('status'))
                $data['status'] = 1;

            CustomerCategory::create($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:customers-categories.index'))
            ->with(['message' => __('words.category-types-created'),'alert-type'=>'success']);
    }

    public function show(CustomerCategory $customers_category)
    {
        if (!auth()->user()->can('show_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.customer-categories.show',compact('customers_category'));
    }

    public function edit(CustomerCategory $customers_category)
    {
        if (!auth()->user()->can('update_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        return view('admin.customer-categories.edit',compact('customers_category','branches'));
    }

    public function update(UpdateCustomerCategoryRequest $request, CustomerCategory $customers_category)
    {
        if (!auth()->user()->can('update_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status')){

                $data['status'] = 1;
            }


            if(!authIsSuperAdmin()){

                $data['branch_id'] = auth()->user()->branch_id;
            }

            $customers_category->update($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:customers-categories.index'))
            ->with(['message' => __('words.category-types-updated'),'alert-type'=>'success']);
    }

    public function destroy(CustomerCategory $customers_category)
    {
        if (!auth()->user()->can('delete_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $customers_category->delete();

        return redirect(route('admin:customers-categories.index'))
            ->with(['message' => __('words.category-types-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_customer_groups')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            CustomerCategory::whereIn('id', $request->ids)->delete();
            return redirect(route('admin:customers-categories.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:customers-categories.index'))
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
