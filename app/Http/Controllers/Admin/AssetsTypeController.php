<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetTypeRequest;
use App\Models\AssetType;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsTypeController extends Controller
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

        $assetsTypes = AssetType::orderBy('id' ,'desc')->get();
        return view('admin.assetsTypes.index', compact('assetsTypes'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }
        $branches = Branch::select(['id','name_ar','name_en'])->get();
        return view('admin.assetsTypes.create',compact(['branches']));
    }

    public function store(AssetTypeRequest $request)
    {
        if (!auth()->user()->can('create_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }
        AssetType::create($request->all());
        return redirect()->to('admin/assets-type')
            ->with(['message' => __('words.asset-type-created'), 'alert-type' => 'success']);
    }

    public function edit(AssetType $assetType)
    {
        if (!auth()->user()->can('update_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }
        $branches = Branch::select(['id','name_ar','name_en'])->get();
        return view('admin.assetsTypes.edit', compact('assetType','branches'));
    }

    public function update(AssetTypeRequest $request, AssetType $assetType)
    {
        if (!auth()->user()->can('update_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $assetType->update($request->all());
        return redirect()->to('admin/assets-type')
            ->with(['message' => __('words.asset-type-updated'), 'alert-type' => 'success']);
    }

    public function destroy(AssetType $assetType)
    {
        // if ($currency->countries()->exists()) {
        //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if($assetType->assets->count()){
            return redirect()->to('admin/assets-type')
            ->with(['message' => __('words.type-asset-cannot-deleted-has-asset '), 'alert-type' => 'error']);

        }

        $assetType->delete();
        return redirect()->to('admin/assets-type')
            ->with(['message' => __('words.asset-type-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $assetsTypes = AssetType::whereIn('id', $request->ids)->get();
            foreach ($assetsTypes as $assetType) {
                if(!$assetType->assets->count()){
                    $assetType->delete();
                }
            }

            return redirect()->to('admin/assets-type')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets-type')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
