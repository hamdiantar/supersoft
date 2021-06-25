<?php

namespace App\Http\Controllers\Admin;

use App\Models\Part;
use App\Models\SparePart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoicePartsController extends Controller
{
    public $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    public function getSubPartsTypes(Request $request)
    {
        $branch_id = Auth::user()->branch_id;

        $rules = [
            'type' => 'required|string:in:sub_type,main_type'
        ];

        if ($request['part_type_id'] != 'all') {

            $rules['part_type_id'] = 'required|integer|exists:spare_parts,id';
        }

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = $request['branch_id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            if ($request['part_type_id'] != 'all') {

                $sparPart = SparePart::find($request['part_type_id']);

                $subSparParts = $sparPart->children()->where('branch_id', $branch_id)->get();

                $parts = $sparPart->allParts;

            } else {

                $subSparParts = SparePart::where('status', 1)->where('branch_id', $branch_id)->where('spare_part_id', '!=', null)->get();

                $parts = $parts = Part::where('branch_id', $branch_id)->where('status', 1)->get();
            }

            $subTypes = view('admin.invoices_parts_filter.sub_types', compact('subSparParts'))->render();

            $parts = view('admin.invoices_parts_filter.parts', compact('parts'))->render();

        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 400);
        }

        return response()->json(['subTypes' => $subTypes, 'parts' => $parts, 'type' => $request['type'], 'message' => 'done'], 200);
    }

    public function partsFilterFooter(Request $request)
    {
        $branch_id = Auth::user()->branch_id;

        $rules = [
            'part_type_id'=>'required|integer|exists:spare_parts,id',
            'type' => 'required|string:in:sub_type,main_type'
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = $request['branch_id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $lang = $this->lang;

            $sparPart = SparePart::find($request['part_type_id']);

            $subTypes = $sparPart->children()->where('branch_id', $branch_id)->get();

            $parts = $sparPart->allParts;

        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 400);
        }

        return response()->json(['subTypes' => $subTypes, 'parts' => $parts,
            'type' => $request['type'], 'message' => 'done', 'lang'=> $lang], 200);
    }
}
