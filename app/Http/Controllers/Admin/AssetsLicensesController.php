<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetLicenseRequest;
use App\Models\AssetGroup;
use App\Models\AssetLicense;
use App\Models\AssetInsurance;
use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsLicensesController extends Controller
{
    
    public function __construct()
    {
//        $this->middleware('permission:view_currencies');
//        $this->middleware('permission:create_currencies',['only'=>['create','store']]);
//        $this->middleware('permission:update_currencies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_currencies',['only'=>['destroy','deleteSelected']]);
    }

    public function index(asset $asset)
    {
        // if (!auth()->user()->can('view_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }
        $assetsLicenses = AssetLicense::where("asset_id" , $asset->id)->orderBy('id' ,'desc')->get();
        return view('admin.assetsLicenses.index', compact('asset' , 'assetsLicenses'));
    }

    
    public function store(AssetLicenseRequest $request )
    {
        // if (!auth()->user()->can('create_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }
        
        if($request->asset_license_id){
            
            $getlicense = AssetLicense::find($request->asset_license_id);
            if($getlicense){
                AssetLicense::where("id" , $request->asset_license_id)->update([
                    "license_details" => $request->name,
                    "asset_id" => $request->asset_id,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date
                ]);
            }
            return redirect()->to('admin/assets-licenses/'.$request->asset_id)
            ->with(['message' => __('words.asset-licenses-updated'), 'alert-type' => 'success']);
            
        }else{
            AssetLicense::create([
                "license_details" => $request->name,
                "asset_id" => $request->asset_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date
            ]);
            return redirect()->to('admin/assets-licenses/'.$request->asset_id)
            ->with(['message' => __('words.asset-licenses-created'), 'alert-type' => 'success']);
        }
        
        
    }

    

    public function destroy(AssetLicense $assetLicense)
    {
        // // if ($currency->countries()->exists()) {
        // //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        $assetLicense->delete();
        return redirect()->to('admin/assets-licenses/'.$assetLicense->asset_id)
            ->with(['message' => __('words.asset-licenses-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected( Request $request)
    {
        // if (!auth()->user()->can('delete_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if (isset($request->ids)) {
            $assetsLicenses = AssetLicense::whereIn('id', $request->ids)->delete();
            
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        
    }
}
