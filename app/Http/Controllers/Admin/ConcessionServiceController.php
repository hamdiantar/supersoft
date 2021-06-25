<?php

namespace App\Http\Controllers\Admin;

use App\Models\Concession;
use App\Models\ConcessionType;
use App\Services\ConcessionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ConcessionServiceController extends Controller
{
    public $concessionService;

    public function __construct()
    {
        $this->concessionService = new ConcessionService();
    }

    public function getConcessionTypes(Request $request)
    {

        $rules = [
            'type' => 'required|string|in:add,withdrawal',
        ];

        if (authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $concessionTypes = ConcessionType::where('branch_id', $branch_id)->where('type', $request['type'])->get();

            $view = view('admin.concession_services.ajax_concession_types', compact('concessionTypes'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function getDataByBranch(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'type' => 'required|string|in:add,withdrawal',
            'branch_id' => 'required|integer|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $concessionTypes = ConcessionType::where('type', $request['type'])->where('branch_id', $request['branch_id'])->get();
            $view = view('admin.concession_services.ajax_concession_types', compact('concessionTypes'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function getItems(Request $request)
    {
        $rules = [
            'concession_type_id' => 'required|integer|exists:concession_types,id'
        ];

        $branch_id = auth()->user()->branch->id;

        if (authIsSuperAdmin() && !$request->has('search')) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = $request->branch_id;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $concession_id = $request->has('concession_id') ? $request['concession_id'] : null;

            $concessionType = ConcessionType::find($request['concession_type_id']);

            $concessionTypeItem = $concessionType->concessionItem;

            if (!$concessionTypeItem) {
                return response()->json('sorry, this type not have items', 400);
            }

            $data = $this->getItemsData($concessionType, $request, $branch_id, $concession_id);

            $search = $request->has('search') ? true : false;

            $view = view('admin.concession_services.ajax_concession_items', compact('data', 'search'))->render();

            return response()->json(['view' => $view, 'model' => $concessionTypeItem->model], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function getItemsData($concessionType, $request, $branch_id, $concession_id)
    {
        $concessionTypeItem = $concessionType->concessionItem;

        $model = "\App\Models" . "\\" . $concessionTypeItem->model;

        $data = $model::where('branch_id', $branch_id);

        if ($concessionTypeItem->model == 'StoreTransfer' && $concessionType->type == 'add') {

            $data->whereDoesntHave('concession', function ($q) use ($concession_id){
                $q->where('type', 'add')->where('id','!=', $concession_id);

            })->whereHas('concession', function ($q) {

                $q->where('type', 'withdrawal'); // and status accepted
            });

        } else {

            $data->WhereDoesntHave('concession', function ($q) use ($concession_id){
                $q->where('id','!=', $concession_id);
            });
        }

        if ($concessionTypeItem->model == 'Settlement' && $concessionType->type == 'add') {

            $data = $data->where('type', 'positive')->select('id', 'number')->get();

        } elseif ($concessionTypeItem->model == 'Settlement' && $concessionType->type == 'withdrawal') {

            $data = $data->where('type', 'negative')->select('id', 'number')->get();

        } else {

            $data = $data->get();
        }

        return $data;
    }

    public function getPartsData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'concession_item_id' => 'required|integer',
            'model' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $model = $this->concessionService->getModelNameSpace($request['model']);

            $modelRaw = $model::find($request['concession_item_id']);

            if (!$modelRaw) {
                return response()->json('sorry, selected item not valid', 400);
            }

            $totalQuantity = 0;
            $totalPrice = 0;

            if ($request['model'] == 'OpeningBalance') {

                $items = $modelRaw->items;

                foreach ($modelRaw->items as $item) {

                    $totalPrice += $item->buy_price * $item->quantity;
                    $totalQuantity += $item->quantity;
                }

            } else {

                $items = $modelRaw->items;

                foreach ($modelRaw->items as $item) {

                    $totalPrice += $item->price * $item->quantity;
                    $totalQuantity += $item->quantity;
                }
            }

            $modelName = $request['model'];

            $view = view('admin.concessions.part_row', compact('items', 'modelName'))->render();

            return response()->json(['view' => $view, 'total_quantity' => $totalQuantity, 'total_price' => $totalPrice], 200);

        } catch (\Exception $e) {

            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }
    }

    public function getConcessionTypesIndexSearch(Request $request)
    {
        $rules = [
            'type' => 'required|string|in:add,withdrawal,all',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $concessionTypes = ConcessionType::query();
            $concessions = Concession::query();

            if ($request['type'] != 'all') {
                $concessionTypes->where('type', $request['type']);
                $concessions->where('type', $request['type']);
            }

            $concessionTypes = $concessionTypes->get();
            $concessions = $concessions->select('id','number')->get();

            $view = view('admin.concession_services.ajax_concession_types', compact('concessionTypes'))->render();

            return response()->json(['view' => $view, 'concessions' => $concessions], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function getItemsIndexSearch(Request $request)
    {

//        $rules = [
//            'concession_type_id' => 'required|integer|exists:concession_types,id'
//        ];

        $branch_id = auth()->user()->branch->id;

//        $validator = Validator::make($request->all(), $rules);
//
//        if ($validator->fails()) {
//            return response()->json($validator->errors()->first(), 400);
//        }

        try {

            $concessionType = ConcessionType::find($request['concession_type_id']);

            $concessionTypeItem = $concessionType->concessionItem;

            if (!$concessionTypeItem) {

                return response()->json('sorry, this type not have items', 400);
            }

            $concessionTypeItem = $concessionType->concessionItem;

            $model = "\App\Models" . "\\" . $concessionTypeItem->model;

            $data = $model::where('branch_id', $branch_id);

            if ($concessionTypeItem->model == 'Settlement' && $concessionType->type == 'add') {

                $data = $data->where('type', 'positive')->select('id', 'number')->get();

            } elseif ($concessionTypeItem->model == 'Settlement' && $concessionType->type == 'withdrawal') {

                $data = $data->where('type', 'negative')->select('id', 'number')->get();

            } else {

                $data = $data->get();
            }

            $search = true;

            $view = view('admin.concession_services.ajax_concession_items', compact('data', 'search'))->render();

            return response()->json(['view' => $view, 'model' => $concessionTypeItem->model], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }
}
