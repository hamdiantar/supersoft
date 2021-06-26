<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetGroupRequest;
use App\Models\AssetGroup;
use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsGroupController extends Controller
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

        $assetsGroups = AssetGroup::orderBy('id' ,'desc')->get();
        return view('admin.assetsGroups.index', compact('assetsGroups'));
    }

    public function create()
    {
        $branches = Branch::select(['id','name_ar','name_en'])->get();
        return view('admin.assetsGroups.create',compact(['branches']));
    }

    public function store(AssetGroupRequest $request)
    {
        // if (!auth()->user()->can('create_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

       AssetGroup::create([
           'branch_id' => $request->branch_id,
           'annual_consumtion_rate' => $request->annual_consumtion_rate,
           'name_ar' => $request->name_ar,
           'name_en' => $request->name_en,
       ]);
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.asset-type-created'), 'alert-type' => 'success']);
    }

    public function edit(assetGroup $assetGroup)
    {
        $branches = Branch::select(['id','name_ar','name_en'])->get();
        return view('admin.assetsGroups.edit', compact('assetGroup','branches'));
    }

    public function update(AssetGroupRequest $request, AssetGroup $assetGroup)
    {
        // if (!auth()->user()->can('update_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        $assetGroup->update([
            'annual_consumtion_rate' => $request->annual_consumtion_rate,
           'name_ar' => $request->name_ar,
           'name_en' => $request->name_en,
           'branch_id' => $request->branch_id,
        ]);
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.asset-group-updated'), 'alert-type' => 'success']);
    }

    public function destroy(AssetGroup $assetGroup)
    {
        // // if ($currency->countries()->exists()) {
        // //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        // $getAssets = Asset::where('asset_group_id' , $assetGroup->id)->get();
        if($assetGroup->assets->count()){
            return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.group-asset-cannot-deleted-has-asset '), 'alert-type' => 'error']);

        }
        $assetGroup->delete();
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.asset-group-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        // if (!auth()->user()->can('delete_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if (isset($request->ids)) {
            $assetsGroups = AssetGroup::whereIn('id', $request->ids)->get();
            foreach ($assetsGroups as $assetGroup) {
                if(!$assetGroup->assets->count()){
                    $assetGroup->delete();
                }
            }

            return redirect()->to('admin/assets-groups')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
