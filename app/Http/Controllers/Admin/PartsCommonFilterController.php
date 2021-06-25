<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Part;
use App\Models\SparePart;
use App\Models\Store;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class PartsCommonFilterController extends Controller
{
    use SubTypesServices;

    public $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    public function dataByBranch(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'branch_id' => 'required|integer|exists:branches,id'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $branch_id = $request['branch_id'];

            $mainTypes = SparePart::where('status', 1)->where('branch_id', $branch_id)
                ->where('spare_part_id', null)
                ->select('id', 'type_' . $this->lang)
                ->get();

            $subTypes = SparePart::where('status', 1)->where('branch_id', $branch_id)
                ->where('spare_part_id', '!=', null)
                ->select('id', 'type_' . $this->lang)
                ->get();

            $parts = Part::where('status', 1)->where('branch_id', $branch_id)->select('name_' . $this->lang, 'id')->get();

            $mainTypesView = view('admin.parts_common_filters.ajax_main_types', compact('mainTypes'))->render();
            $subTypesView = view('admin.parts_common_filters.ajax_sub_types', compact('subTypes'))->render();
            $partsView = view('admin.parts_common_filters.ajax_parts', compact('parts'))->render();

            $returnedData = [

                'main_types' => $mainTypesView,
                'sub_types' => $subTypesView,
                'parts' => $partsView,
            ];

            if ($request->has('type') && $request['type'] == 'store_transfer') {

                $returnedData['storeOptions'] = $this->get_branch_stores($request['branch_id']);
            }

            return response()->json($returnedData, 200);

        } catch (\Exception $e) {
            dd($e->getMessage());

            return response()->json('sorry, please try later', 400);
        }
    }

    public function dataByMainType(Request $request)
    {
        $rules = [
            'main_type_id' => 'nullable|integer|exists:spare_parts,id'
        ];

        if ($request->has('store_id') && $request['store_id']) {
            $rules['store_id'] = 'required|integer|exists:stores,id';
        }

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = $request['branch_id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $mainPartTypes = SparePart::query()
                ->where('branch_id', $branch_id)
                ->where('spare_part_id', null)
                ->select('id', 'type_' . $this->lang);

            if ($request['main_type_id'] != null) {
                $mainPartTypes->where('id', $request['main_type_id']);
            }

            $mainPartTypes = $mainPartTypes->get();

            $order = $request->has('order') ? $request['order'] : 1;

            $subTypes = $this->getSubPartTypes($mainPartTypes, $order);

            $parts = Part::where('status', 1)->where('branch_id', $branch_id);

            if ($request['main_type_id'] != null) {

                $parts->whereHas('spareParts', function ($q) use ($request) {
                    $q->where('spare_part_type_id', $request['main_type_id']);
                });
            }

            if ($request->has('store_id') && $request['store_id']) {

                $parts->whereHas('stores', function ($q) use ($request) {
                    $q->where('store_id', $request['store_id']);
                });
            }

            $parts = $parts->select('name_' . $this->lang, 'id')->get();

            $subTypesView = view('admin.parts_common_filters.ajax_sub_types', compact('subTypes'))->render();

            $partsView = view('admin.parts_common_filters.ajax_parts', compact('parts'))->render();

            return response()->json(['sub_types' => $subTypesView, 'parts' => $partsView], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function dataBySubType(Request $request)
    {
        $rules = [
            'sub_type_id' => 'nullable|integer|exists:spare_parts,id'
        ];

        if ($request->has('store_id') && $request['store_id']) {
            $rules['store_id'] = 'required|integer|exists:store_id';
        }

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = $request['branch_id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $parts = Part::where('status', 1)->where('branch_id', $branch_id);

            if ($request['sub_type_id'] != null) {

                $parts->whereHas('spareParts', function ($q) use ($request) {
                    $q->where('spare_part_type_id', $request['sub_type_id']);
                });
            }

            if ($request->has('store_id') && $request['store_id']) {
                $parts->whereHas('stores', function ($q) use ($request) {
                    $q->where('store-id', $request['store_id']);
                });
            }

            $parts = $parts->select('name_' . $this->lang, 'id')->get();

            $partsView = view('admin.parts_common_filters.ajax_parts', compact('parts'))->render();

            return response()->json(['parts' => $partsView], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    function get_branch_stores($branch_id)
    {

        $stores = Store::where('branch_id', $branch_id)->get();

        $options_html = '<option value=""> ' . __('Select One') . '</option>';

        foreach ($stores as $store) {
            $options_html .= '<option value="' . $store->id . '">' . $store->name . '</option>';
        }

        return $options_html;
    }
}
