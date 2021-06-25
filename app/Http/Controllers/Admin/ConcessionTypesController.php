<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ConcessionTypes\CreateRequest;
use App\Http\Requests\Admin\ConcessionTypes\UpdateRequest;
use App\Models\Branch;
use App\Models\ConcessionType;
use App\Models\ConcessionTypeItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class ConcessionTypesController extends Controller
{
    public $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    public function index(Request $request)
    {
        $types = ConcessionType::query();

        if ($request->has('branch_id') && $request['branch_id'] != '') {

            $types->where('branch_id', $request['branch_id']);
        }

        if ($request->has('type_id') && $request['type_id'] != '') {

            $types->where('id', $request['type_id']);
        }

        if ($request->has('type') && $request['type'] != 'all') {

            $types->where('type', $request['type']);
        }

        $types = $types->get();

        $data['types'] = ConcessionType::get();

        $branches = Branch::select('id','name_' . $this->lang)->get();
        return view('admin.concession_types.index', compact('types', 'branches', 'data'));
    }

    public function create()
    {
        $concessionItems = ConcessionTypeItem::where('type', 'add')->select('id','name', 'type')->get();
        $branches = Branch::select('id', 'name_' . $this->lang)->get();
        return view('admin.concession_types.create', compact('branches', 'concessionItems'));
    }

    public function store(CreateRequest $request)
    {
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {

                $data['branch_id'] = auth()->user()->branch_id;
            }

            ConcessionType::create($data);

            return redirect(route('admin:concession-types.index'))
                ->with(['message' => __('words.concession-type-created'), 'alert-type' => 'success']);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
    }

    public function edit(ConcessionType $concessionType)
    {
        $branches = Branch::select('id', 'name_' . $this->lang)->get();
        $concessionItems = ConcessionTypeItem::where('type', $concessionType->type)->select('id','name', 'type')->get();
        return view('admin.concession_types.edit', compact('concessionType','branches', 'concessionItems'));
    }

    public function update (UpdateRequest $request, ConcessionType $concessionType)
    {
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {

                $data['branch_id'] = auth()->user()->branch_id;
            }

            $concessionType->update($data);

            return redirect(route('admin:concession-types.index'))
                ->with(['message' => __('words.concession-type-updated'), 'alert-type' => 'success']);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
    }

    public function destroy (ConcessionType $concessionType) {

        try {

            if ($concessionType->concessions->count()) {

                return redirect()->back()
                    ->with(['message' => __('sorry, this item have related data'), 'alert-type' => 'error']);
            }

            $concessionType->forceDelete();

            return redirect(route('admin:concession-types.index'))
                ->with(['message' => __('words.concession-type-deleted'), 'alert-type' => 'success']);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            foreach($request['ids'] as $id) {
                $type = ConcessionType::find($id);
                if ($type->concessions->count()) {
                    continue;
                }
                $type->forceDelete();
            }
            return redirect()->back()->with(['message'=> __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getConcessionItems (Request $request) {

        $validator = Validator::make($request->all(), [

            'type'=>'required|string|in:add,withdrawal',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $concessionItems = ConcessionTypeItem::where('type', $request['type'])->select('id','name', 'type')->get();

            $view = view('admin.concession_types.ajax_concession_items', compact('concessionItems'))->render();

            return response()->json(['view'=> $view], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

    }
}
