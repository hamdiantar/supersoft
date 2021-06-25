<?php

namespace App\Http\Controllers\Admin;

use App\Filters\SparePartFilter;
use App\Http\Requests\Admin\SubSpareParts\CreateRequest;
use App\Http\Requests\Admin\SubSpareParts\UpdateRequest;
use App\Models\SparePart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SubPartsTypesController extends Controller
{
    protected $sparePartFilter;

    public function __construct(SparePartFilter $sparePartFilter)
    {
        $this->sparePartFilter = $sparePartFilter;
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $sparePart = SparePart::query()->where('spare_part_id', '!=', null);

        if ($request->hasAny(['type', 'branch_id', 'main_type'])) {
            $sparePart = $this->sparePartFilter->filter($request);
        }

        $mainSpareParts = SparePart::where('spare_part_id', null)->get()->pluck('type', 'id');

        return view('admin.sub_parts_types.index',
            ['sparePart' => $sparePart->orderBy('id', 'desc')->get() , 'mainSpareParts' => $mainSpareParts]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $parts_types = SparePart::where('spare_part_id', null)->get()->pluck('type', 'id');

        return view('admin.sub_parts_types.create', compact('parts_types'));
    }

    public function store(CreateRequest $request)
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

        return redirect()->to('admin/sub-parts-types')->with(['message' => __('words.spare-part-created'), 'alert-type' => 'success']);
    }

    public function edit(SparePart $sparePart)
    {
        if (!auth()->user()->can('update_spareParts')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $parts_types = SparePart::where('spare_part_id', null)->get()->pluck('type', 'id');

        return view('admin.sub_parts_types.edit', compact('parts_types', 'sparePart'));
    }

    public function update(UpdateRequest $request, SparePart $sparePart)
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

        return redirect()->to('admin/sub-parts-types')
            ->with(['message' => __('words.spare-part-updated'), 'alert-type' => 'success']);
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

            return redirect()->to('admin/sub-parts-types')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/sub-parts-types')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
