<?php

namespace App\Http\Controllers\Admin;

use App\Filters\SparePartFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SparePart\SparePartRequest;
use App\Http\Requests\Admin\SparePart\UpdateSparPartsRequest;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SparePartsController extends Controller
{

    /**
     * @var SparePartFilter
     */
    protected $sparePartFilter;

    public function __construct(SparePartFilter $sparePartFilter)
    {
        $this->sparePartFilter = $sparePartFilter;
//        $this->middleware('permission:view_sparePartsUnit');
//        $this->middleware('permission:create_sparePartsUnit',['only'=>['create','store']]);
//        $this->middleware('permission:update_sparePartsUnit',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_sparePartsUnit',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $sparePart = SparePart::query()->where('spare_part_id', null);

        if ($request->hasAny(['type', 'branch_id'])) {
            $sparePart = $this->sparePartFilter->filter($request);
        }

        return view('admin.spare-parts.index', ['sparePart' => $sparePart->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.spare-parts.create');
    }

    public function store(SparePartRequest $request)
    {
        if (!auth()->user()->can('create_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();

        if ($request->has('image')) {
            $data['image'] = uploadImage($request->image, 'spare-parts');
        }

        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        SparePart::create($data);

        $url = 'admin/spare-parts';

        if ($request->has('spare_part_id')) {
            $url = 'admin/sub-parts-types';
        }

        return redirect()->to($url)->with(['message' => __('words.spare-part-created'), 'alert-type' => 'success']);
    }

    public function edit(SparePart $sparePart)
    {
        if (!auth()->user()->can('update_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.spare-parts.edit', compact('sparePart'));
    }

    public function update(UpdateSparPartsRequest $request, SparePart $sparePart)
    {
        if (!auth()->user()->can('update_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if ($request->has('image') && $data['image'] != null) {
            if (File::exists(storage_path('app/public/images/spare-parts/' . $sparePart->image))) {
                File::delete(storage_path('app/public/images/spare-parts/' . $sparePart->image));
            }
            $data['image'] = uploadImage($request->file('image'), 'spare-parts');
        }
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        $sparePart->update($data);

        $url = 'admin/spare-parts';

        if ($request->has('spare_part_id')) {
            $url = 'admin/sub-parts-types';
        }

        return redirect()->to($url)
            ->with(['message' => __('words.spare-part-updated'), 'alert-type' => 'success']);
    }

    public function destroy(SparePart $sparePart)
    {
        if (!auth()->user()->can('delete_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (File::exists(storage_path('app/public/images/spare-parts/' . $sparePart->image))) {
            File::delete(storage_path('app/public/images/spare-parts/' . $sparePart->image));
        }

        $url = 'admin/spare-parts';

        if ($sparePart->spare_part_id) {
            $url = 'admin/sub-parts-types';
        }

        $sparePart->delete();

        return redirect()->to($url)->with(['message' => __('words.spare-part-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            $spareParts = SparePart::whereIn('id', $request->ids)->get();

            foreach ($spareParts as $sparePart) {
                if (File::exists(storage_path('app/public/images/spare-parts/' . $sparePart->image))) {
                    File::delete(storage_path('app/public/images/spare-parts/' . $sparePart->image));
                }
            }

            SparePart::whereIn('id', $request->ids)->delete();

            return redirect()->to('admin/spare-parts')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/spare-parts')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
