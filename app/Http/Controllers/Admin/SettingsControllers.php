<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Settings\createSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SettingsControllers extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('permission:view_setting');
//        $this->middleware('permission:update_setting', ['only' => ['edit', 'update']]);
//    }

    public function edit()
    {
        if(authIsSuperAdmin()){
            return redirect()->back()->with(['message' => __('you are not super admin'), 'alert-type'=>'error']);
        }

        $setting = Setting::where('branch_id', auth()->user()->branch_id)->first();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(createSettingsRequest $request)
    {

        $user = auth()->user();

        if(!$user->branch_id){

            return redirect(route('admin:settings.edit'))
                ->with([ 'message' => __('branch is required'),'alert-type'=>'error']);
        }

        $branch = $user->branch;

        if(!$branch){

            return redirect(route('admin:settings.edit'))
                ->with([ 'message' => __('branch is required'),'alert-type'=>'error']);
        }

        $data = $request->validated();

        $data['sales_invoice_status'] = 0;

        if($request->has('sales_invoice_status')){

            $data['sales_invoice_status'] = 1;
        }

        $data['maintenance_status'] = 0;

        if($request->has('maintenance_status')){

            $data['maintenance_status'] = 1;
        }

        $data['invoice_setting'] = 0;

        if($request->has('invoice_setting')){

            $data['invoice_setting'] = 1;
        }

        $data['filter_setting'] = 0;

        if($request->has('filter_setting')){

            $data['filter_setting'] = 1;
        }

        $data['quotation_terms_status'] = 0;

        if($request->has('quotation_terms_status')){

            $data['quotation_terms_status'] = 1;
        }

        $setting = Setting::where('branch_id', $branch->id)->first();

        if($setting){

            $setting->update($data);
        }

        if(!$setting){

            $data['branch_id'] = $branch->id;
            Setting::create($data);
        }

        return redirect(route('admin:settings.edit'))
            ->with(['message'=> __('Settings Updated Successfully'),'alert-type'=>'success']);

    }
}
