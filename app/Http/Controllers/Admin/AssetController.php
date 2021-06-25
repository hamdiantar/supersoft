<!-- <?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AssetRequest as Request;
use App\Http\Controllers\Controller;
use App\Model\Asset as Model;

class AssetController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_assets');
//        $this->middleware('permission:create_assets',['only'=>['create','store']]);
//        $this->middleware('permission:update_assets',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_assets',['only'=>['destroy','deleteSelected']]);
    }

    

    public function index() {

        if (!auth()->user()->can('view_assets')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        
        return view(self::view . 'index' ,compact('assets' ,'__assets'));
    }


    public function show(Request $request)
    {

        $asset = Asset::findOrFail($request['asset_id']);

        $view = view('admin.assets.show', compact('asset'))->render();

        return response()->json(['view' => $view]);
    }

    public function create() {

        if (!auth()->user()->can('create_assets')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = \App\Models\Branch::all();
        return view(self::view . 'create' ,compact('branches'));
    }

    public function store(Request $request) {

        if (!auth()->user()->can('create_assets')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        Model::create($request->all());
        return redirect(route(self::module_base))->with([
            'message' => __('words.asset-created'),
            'alert-type' => 'success'
        ]);
    }


    public function edit(Model $asset) {

        if (!auth()->user()->can('update_assets')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = \App\Models\Branch::all();
        return view(self::view . 'edit' ,compact('asset' ,'branches'));
    }

    public function update(Request $request, Model $asset) {

        if (!auth()->user()->can('update_assets')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $asset->update($request->all());
        return redirect(route(self::module_base))->with([
            'message' => __('words.asset-updated'),
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Model $asset) {

        if (!auth()->user()->can('delete_assets')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $asset->delete();
        return redirect(route(self::module_base))->with([
            'message' => __('words.asset-deleted'),
            'alert-type' => 'success'
        ]);
    }

    public function deleteSelected() {

        if (!auth()->user()->can('delete_assets')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!request()->has('ids')) {
            return redirect(route(self::module_base))->with([
                'message' => __('words.select-asset-at-least'),
                'alert-type' => 'error'
            ]);
        }
        foreach(array_unique(request('ids')) as $id) {
            $asset = Model::find($id);
            if ($asset) $asset->delete();
        }
        return redirect(route(self::module_base))->with([
            'message' => __('words.asset-deleted'),
            'alert-type' => 'success'
        ]);
    }

} -->
