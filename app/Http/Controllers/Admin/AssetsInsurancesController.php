<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetInsuranceRequest;
use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\AssetInsurance;
use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsInsurancesController extends Controller
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
        $assetsInsurances = AssetInsurance::where("asset_id" , $asset->id)->orderBy('id' ,'desc')->get();
        return view('admin.assetsInsurances.index', compact('asset' , 'assetsInsurances'));
    }

    // public function create()
    // {
    //     // if (!auth()->user()->can('create_currencies')) {

    //     //     return redirect()->back()->with(['authorization' => 'error']);
    //     // }
    //     $branches = Branch::all();
    //     $assetsGroups = AssetGroup::all();
    //     $assetsTypes = AssetType::all();

    //     return view('admin.assets.create' , compact("assetsGroups","branches","assetsTypes"));
    // }

    public function store(AssetInsuranceRequest $request )
    {

        if($request->asset_insurance_id){

            $getInsurance = AssetInsurance::find($request->asset_insurance_id);
            if($getInsurance){
                AssetInsurance::where("id" , $request->asset_insurance_id)->update([
                    "insurance_details" => $request->name,
                    "asset_id" => $request->asset_id,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                    'status'=>$request->status
                ]);
            }
            return redirect()->to('admin/assets-insurances/'.$request->asset_id)
            ->with(['message' => __('words.asset-insurances-updated'), 'alert-type' => 'success']);

        }else{
            AssetInsurance::create([
                "insurance_details" => $request->name,
                "asset_id" => $request->asset_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                'status'=>$request->status
            ]);
            return redirect()->to('admin/assets-insurances/'.$request->asset_id)
            ->with(['message' => __('words.asset-insurances-created'), 'alert-type' => 'success']);
        }


    }



    public function destroy(AssetInsurance $assetInsurance)
    {
        // // if ($currency->countries()->exists()) {
        // //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        $assetInsurance->delete();
        return redirect()->to('admin/assets-insurances/'.$assetInsurance->asset_id)
            ->with(['message' => __('words.asset-insurance-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected( Request $request)
    {
        // if (!auth()->user()->can('delete_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if (isset($request->ids)) {
            $assetsInsurances = AssetInsurance::whereIn('id', $request->ids)->delete();

            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }

    }
}
