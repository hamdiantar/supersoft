<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetEmployeeRequest;
use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\AssetEmployee;
use App\Models\Asset;
use App\Models\Branch;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsEmployeesController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_currencies');
//        $this->middleware('permission:create_currencies',['only'=>['create','store']]);
//        $this->middleware('permission:update_currencies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_currencies',['only'=>['destroy','deleteSelected']]);
    }

    public function index(asset $asset, Request $request)
    {
//dd($request->all());
        $assetsEmployees = AssetEmployee::where( "asset_id", $asset->id )->orderBy( 'id', 'desc' );
        if ($request->has( 'employee_id' ) && $request['employee_id'] != 0)
            $assetsEmployees->where( 'employee_id', $request['employee_id'] );

        if ($request->has( 'start_date' ) && $request['start_date'] != '')
            $assetsEmployees->where( 'start_date', $request->start_date );

        if ($request->has( 'end_date' ) && $request['end_date'] != '')
            $assetsEmployees->where( 'end_date', $request->end_date );

        if ($request->has( 'active' ) && $request['active'] != '')
            $assetsEmployees->where( 'status', '1' );
        if ($request->has( 'inactive' ) && $request['inactive'] != '')
            $assetsEmployees->where( 'status', '0' );

//        whereBetween( $assetsEmployees, 'DATE(purchase_date)', $request->purchase_date1, $request->purchase_date2 );

        $assetsEmployees = $assetsEmployees->get();
        $employees = EmployeeData::where( "branch_id", $asset->branch_id )->select( ['id', 'name_ar', 'name_en'] )->get();
        return view( 'admin.assetsEmployees.index', compact( 'asset', 'assetsEmployees', 'employees' ) );
    }

    public function store(AssetEmployeeRequest $request)
    {

        if ($request->asset_employee_id) {

            $getEmployee = AssetEmployee::find( $request->asset_employee_id );
            if ($getEmployee) {
                AssetEmployee::where( "id", $request->asset_employee_id )->update( [
                    "employee_id" => $request->employee_id,
                    "asset_id" => $request->asset_id,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                    'status' => $request->status
                ] );
            }
            return redirect()->to( 'admin/assets-employees/' . $request->asset_id )
                ->with( ['message' => __( 'words.asset-employee-updated' ), 'alert-type' => 'success'] );

        } else {
            AssetEmployee::create( [
                "employee_id" => $request->employee_id,
                "asset_id" => $request->asset_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                'status' => $request->status
            ] );
            return redirect()->to( 'admin/assets-employees/' . $request->asset_id )
                ->with( ['message' => __( 'words.asset-employee-created' ), 'alert-type' => 'success'] );
        }


    }


    public function destroy(AssetEmployee $assetEmployee)
    {
        // // if ($currency->countries()->exists()) {
        // //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        $assetEmployee->delete();
        return redirect()->to( 'admin/assets-employees/' . $assetEmployee->asset_id )
            ->with( ['message' => __( 'words.asset-group-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        // if (!auth()->user()->can('delete_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if (isset( $request->ids )) {
            $assetsEmployees = AssetEmployee::whereIn( 'id', $request->ids )->delete();

            return redirect()->back()
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        // return redirect()->to('admin/assets-groups')
        //     ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }

    public function getAssetsEmployeePhone(Request $request)
    {
        return ['status' => true, 'phone' => EmployeeData::where( 'id', $request->employee_id )->value( 'phone1' )];

    }
}
