<?php

namespace App\Http\Controllers\Admin;

use App\Filters\SettlementFilter;
use App\Http\Controllers\DataExportCore\SettlementPrintExcel;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\Settlements\CreateRequest;
use App\Models\Branch;
use App\Models\DamagedStock;
use App\Models\DamagedStockItem;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\Settlement;
use App\Models\SettlementItem;
use App\Models\SparePart;
use App\Models\Store;
use App\Services\SettlementServices;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettlementController extends AbstractController
{

    use SubTypesServices;

    /**
     * @var SettlementFilter
     */
    protected $settlementFilter;

    public $lang;

    public $settlementService;

    public function __construct(SettlementFilter $settlementFilter)
    {
        $this->lang = App::getLocale();
        $this->settlementService = new SettlementServices();
        $this->settlementFilter = $settlementFilter;
    }

    public function getSortFields(): array
    {
        return [
            'id' => 'id',
            'branch' => 'branch',
            'date' => 'date',
            'number' => 'number',
            'total' => 'total',
            'created-at' => 'created_at',
            'updated-at' => 'updated_at',
        ];
    }

    public function index(Request $request)
    {
        $dataQuery = Settlement::query();
        if ($request->hasAny((new Settlement())->getFillable())
            || $request->has('dateFrom')
            || $request->has('dateTo')
            || $request->has('store_id')
            || $request->has('settlement_type')
            || $request->has('barcode')
            || $request->has('supplier_barcode')
            || $request->has('partId')
        ) {
            $dataQuery = $this->settlementFilter->filter($request);
        }
        $data = $this->implementDataTableSearch($dataQuery, $request);
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new SettlementPrintExcel($data, $visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 25;
        $data = $data->paginate($rows);

        return view('admin.settlements.index', compact('data'));
    }

    public function create(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $lastNumber = Settlement::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.settlements.create', compact('branches', 'mainTypes', 'subTypes', 'parts', 'number'));
    }

    public function store(CreateRequest $request)
    {
        if ($this->settlementService->checkMaxQuantityOfItem($request['items'])) {
            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $data['user_id'] = auth()->id();

            $lastNumber = Settlement::where('branch_id', $data['branch_id'])->orderBy('id', 'desc')->first();

            $data['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            $data['total'] = 0;

            $settlement = Settlement::create($data);

            $total = 0;

            foreach ($request['items'] as $item) {

                $item['settlement_id'] = $settlement->id;
                SettlementItem::create($item);
                $total += $item['price'] * $item['quantity'];
            }

            $settlement->total = $total;

            $settlement->save();

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlements.created'), 'alert-type' => 'success']);
    }

    public function show (Settlement $settlement) {

        $totalQuantity = $settlement->items->sum('quantity');
        return view('admin.settlements.show', compact('settlement', 'totalQuantity'));
    }

    public function edit(Settlement $settlement)
    {
        if ($settlement->concession) {
            return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
        }

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('branch_id', $settlement->branch_id)
            ->where('status', 1)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('branch_id', $settlement->branch_id)
            ->where('status', 1)
            ->select('name_' . $this->lang, 'id')
            ->get();

//        $stores = Store::where('branch_id', $settlement->branch_id)->select('name_' . $this->lang,'id')->get();

        $totalQuantity = $settlement->items->sum('quantity');

        return view('admin.settlements.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts','settlement', 'totalQuantity'));
    }

    public function update (CreateRequest $request, Settlement $settlement) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type' => 'error']);
        }

        if ($settlement->concession) {
            return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
        }

        if ($this->settlementService->checkMaxQuantityOfItem($request['items'])) {
            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->settlementService->deleteItems($settlement);

            $data = $request->validated();

            $data['total'] = 0;

            $settlement->update($data);

            $total = 0;

            foreach ($request['items'] as $item) {

                $item['settlement_id'] = $settlement->id;
                SettlementItem::create($item);
                $total += $item['price'] * $item['quantity'];
            }

            $settlement->total = $total;

            $settlement->save();

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlements.updated'), 'alert-type' => 'success']);
    }

    public function destroy (Settlement $settlement) {

        if ($settlement->concession) {
            return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
        }

        try {

            $settlement->forceDelete();

        }catch (\Exception $e) {

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlement.deleted'), 'alert-type' => 'success']);
    }

    public function selectPartRaw(Request $request)
    {
        $rules = [

            'part_id'=>'required|integer|exists:parts,id',
            'index'=>'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'] + 1;

            $part = Part::find($request['part_id']);

            $view = view('admin.settlements.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts'=> $view, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function priceSegments(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'price_id'=>'required|integer|exists:part_prices,id',
            'index'=>'required|integer'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'];

            $priceSegments = PartPriceSegment::where('part_price_id', $request['price_id'])->get();

            $view = view('admin.settlements.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view'=> $view], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            $settlements = Settlement::whereIn('id', $request->ids)->get();
            foreach ($settlements as $settlement) {
                if ($settlement->concession) {
                    return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
                }
            }
            foreach ($settlements as $settlement) {
                $settlement->forceDelete();
            }
            return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlement.deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:settlements.index'))->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
