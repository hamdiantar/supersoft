<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetRequest;
use App\Models\AssetGroup;
use App\Models\AssetEmployee;
use App\Models\AssetInsurance;
use App\Models\AssetExamination;
use App\Models\AssetLicense;
use App\Models\AssetType;
use App\Models\Asset;
use App\Models\Branch;
use App\Models\EmployeeData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_currencies');
//        $this->middleware('permission:create_currencies',['only'=>['create','store']]);
//        $this->middleware('permission:update_currencies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_currencies',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        $assets = Asset::orderBy( 'id', 'desc' );
//        dd( $request->all() );
        if ($request->has( 'name' ) &&  !empty($request['name']))
            $assets->where( 'id', [$request->name] );

        if ($request->has( 'branch_id' ) && !empty( $request['branch_id']))
            $assets->where( 'branch_id', $request['branch_id'] );

        if ($request->has( 'asset_group_id' ) && !empty( $request->asset_group_id ))
            $assets->where( 'asset_group_id', $request['asset_group_id'] );

        if ($request->has( 'asset_type_id' ) && !empty( $request['asset_type_id'] ))
            $assets->where( 'asset_type_id', $request['asset_type_id'] );

        if ($request->has( 'annual_consumtion_rate' ) && !empty($request['annual_consumtion_rate']))
            $assets->where( 'annual_consumtion_rate', $request['annual_consumtion_rate'] );

        if ($request->has( 'asset_age' ) &&  !empty($request['asset_age']))
            $assets->where( 'asset_age', $request['asset_age'] );

        if ($request->has( 'purchase_cost' ) &&  !empty($request['purchase_cost']))
            $assets->where( 'purchase_cost', $request['purchase_cost'] );

        if ($request->has( 'purchase_date' ) &&  !empty($request['purchase_date']))
            $assets->where( 'purchase_date', $request['purchase_date'] );

        if ($request->has( 'asset_status' ) &&  !empty($request['asset_status']))
            $assets->where( 'asset_status', $request['asset_status'] );

        if ($request->has( 'employee_id' ) &&  !empty($request['employee_id'])) {
//           $asset_ids = AssetEmployee::where('employee_id',$request->employee_id)->pluck('asset_id');
//            $assets->whereIn( 'id', $asset_ids );
            $assets->whereHas(
                'asset_employees',
                function ($query) use ($request) {
                    $query->where('employee_id', $request->employee_id);
                }
            );
        }

        $branches = Branch::all()->pluck( 'name', 'id' );

        $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsTypes = AssetType::select( ['id', 'name_ar', 'name_en'] )->get();
//dd($request->all());
        whereBetween( $assets, 'DATE(purchase_date)', $request->purchase_date1, $request->purchase_date2 );
        whereBetween( $assets, 'DATE(date_of_work)', $request->date_of_work1, $request->date_of_work2 );
        whereBetween( $assets, 'asset_age', $request->asset_age1, $request->asset_age2 );
        whereBetween( $assets, 'purchase_cost', $request->purchase_cost1, $request->purchase_cost2 );
        whereBetween( $assets, 'annual_consumtion_rate', $request->annual_consumtion_rate1, $request->annual_consumtion_rate2 );

        $assets = $assets->get();
        return view( 'admin.assets.index', compact( 'assets', 'branches', 'assetsGroups', 'assetsTypes' ) );
    }

    public function show(Request $request)
    {

        $asset = Asset::where( "id", $request['asset_id'] )->first();
        $assetEmployees = AssetEmployee::where( "asset_id", $asset->id )->get();
        $assetInsurances = AssetInsurance::where( "asset_id", $asset->id )->get();
        $assetExaminations = AssetExamination::where( "asset_id", $asset->id )->get();
        $assetLicenses = AssetLicense::where( "asset_id", $asset->id )->get();
        $assetType = AssetType::where( "id", $asset->asset_type_id )->first();
        $view = view( 'admin.assets.show', compact( "asset", "assetType", "assetEmployees", "assetInsurances", "assetExaminations", "assetLicenses" ) )->render();

        return response()->json( ['view' => $view] );
    }

    public function create()
    {
        $branches = Branch::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsTypes = AssetType::select( ['id', 'name_ar', 'name_en'] )->get();

        return view( 'admin.assets.create', compact( "assetsGroups", "branches", "assetsTypes" ) );
    }

    public function store(AssetRequest $request)
    {
        $asset_group = AssetGroup::find( $request->asset_group_id );
        if ($request->purchase_cost > 0 && $request->annual_consumtion_rate > 0 && ($request->purchase_cost / $request->annual_consumtion_rate) > 0) {
            $asset_age = ($request->purchase_cost / $request->annual_consumtion_rate) / 100;
        } else {
            $asset_age = 0;
        }
        Asset::create( [
            'asset_age' => $asset_age,
            'branch_id' => $request->branch_id,
            'annual_consumtion_rate' => $asset_group->annual_consumtion_rate,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'asset_group_id' => $request->asset_group_id,
            'asset_type_id' => $request->asset_type_id,
            'asset_status' => $request->asset_status,
            'asset_details' => $request->asset_details,
            'purchase_date' => $request->purchase_date,
            'date_of_work' => $request->date_of_work,
            'purchase_cost' => $request->purchase_cost,
        ] );
        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.asset-created' ), 'alert-type' => 'success'] );
    }

    public function edit(asset $asset)
    {
        $branches = Branch::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsTypes = AssetType::select( ['id', 'name_ar', 'name_en'] )->get();
        return view( 'admin.assets.edit', compact( 'asset', "assetsGroups", "branches", "assetsTypes" ) );
    }

    public function update(AssetRequest $request, asset $asset)
    {
        $asset_group = AssetGroup::find( $request->asset_group_id );
        if ($request->purchase_cost > 0 && $request->annual_consumtion_rate > 0 && ($request->purchase_cost / $request->annual_consumtion_rate) > 0) {
            $asset_age = ($request->purchase_cost / $request->annual_consumtion_rate) / 100;
        } else {
            $asset_age = 0;
        }
        $asset->update( [
            'asset_age' => $asset_age,
            'branch_id' => $request->branch_id,
            'annual_consumtion_rate' => $request->annual_consumtion_rate,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'asset_group_id' => $request->asset_group_id,
            'asset_type_id' => $request->asset_type_id,
            'asset_status' => $request->asset_status,
            'asset_details' => $request->asset_details,
            'purchase_date' => $request->purchase_date,
            'date_of_work' => $request->date_of_work,
            'purchase_cost' => $request->purchase_cost,
        ] );
        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.asset-updated' ), 'alert-type' => 'success'] );
        // return redirect()->to('admin/assets-groups')
        //     ->with(['message' => __('words.asset-group-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Asset $asset)
    {
        // // if ($currency->countries()->exists()) {
        // //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        // delete Asset Insurances
        AssetInsurance::where( "asset_id", $asset->id )->delete();
        // delete Asset Examinations
        AssetExamination::where( "asset_id", $asset->id )->delete();
        // delete Asset Licenses
        AssetLicense::where( "asset_id", $asset->id )->delete();

        // delete Asset Employees
        AssetEmployee::where( "asset_id", $asset->id )->delete();
        // delete Asset
        $asset->delete();

        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        // if (!auth()->user()->can('delete_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if (isset( $request->ids )) {
            // delete Asset Insurances
            AssetInsurance::whereIn( "asset_id", $request->ids )->delete();
            // delete Asset Examinations
            AssetExamination::whereIn( "asset_id", $request->ids )->delete();
            // delete Asset Licenses
            AssetLicense::whereIn( "asset_id", $request->ids )->delete();

            // delete Asset Employees
            AssetEmployee::whereIn( "asset_id", $request->ids )->delete();
            // delete Asset
            Asset::whereIn( "id", $request->ids )->delete();
            return redirect()->to( 'admin/assets' )
                ->with( ['message' => __( 'words.selected-rows-delete' ), 'alert-type' => 'success'] );
        }
        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.no-data-delete' ), 'alert-type' => 'error'] );
    }

    public function getAssetsGroupsByBranchId(Request $request): JsonResponse
    {
        if (is_null( $request->branch_id )) {
            return response()->json( __( 'please select valid Branch' ), 400 );
        }
        if ($assets_groups = AssetGroup::where( 'branch_id', $request->branch_id )->get()) {
            $assets_groups_data = view( 'admin.assets.asset_groups_by_branch_id', compact( 'assets_groups' ) )->render();
            return response()->json( [
                'data' => $assets_groups_data,
            ] );
        }
    }

    public function getAssetsTypesByBranchId(Request $request): JsonResponse
    {
        if (is_null( $request->branch_id )) {
            return response()->json( __( 'please select valid Branch' ), 400 );
        }
        if ($assets_types = AssetType::where( 'branch_id', $request->branch_id )->get()) {
            $assets_types_data = view( 'admin.assets.asset_types_by_branch_id', compact( 'assets_types' ) )->render();
            return response()->json( [
                'data' => $assets_types_data,
            ] );
        }
    }

    public function getAssetsGroupsAnnualConsumtionRate(Request $request)
    {
        if (is_null( $request->asset_group_id )) {
            return response()->json( __( 'please select valid assets group type' ), 400 );
        }
        if ($annual_consumtion_rate = AssetGroup::find( $request->asset_group_id )->annual_consumtion_rate) {
            return ['status' => true, 'annual_consumtion_rate' => $annual_consumtion_rate];
        }
    }

}
