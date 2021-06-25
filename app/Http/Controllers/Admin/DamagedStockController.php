<?php

namespace App\Http\Controllers\Admin;

use App\Filters\DamagedStockFilter;
use App\Http\Controllers\DataExportCore\DamagedStockPrintExcel;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\DamageStock\CreateRequest;
use App\Models\Branch;
use App\Models\DamagedStock;
use App\Models\DamagedStockItem;
use App\Models\EmployeeData;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\SparePart;
use App\Models\Store;
use App\Services\DamagedStockServices;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DamagedStockController extends AbstractController
{
    use SubTypesServices;

    public $lang;

    public $damagedStockService;

    /**
     * @var DamagedStockFilter
     */
    protected $damagedStockFilter;

    public function __construct(DamagedStockFilter $damagedStockFilter)
    {
        $this->lang = App::getLocale();
        $this->damagedStockService = new DamagedStockServices();
        $this->damagedStockFilter = $damagedStockFilter;
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
        $dataQuery = DamagedStock::query();
        if ($request->hasAny((new DamagedStock())->getFillable())
            || $request->has('dateFrom')
            || $request->has('dateTo')
            || $request->has('store_id')
            || $request->has('damage_type')
            || $request->has('barcode')
            || $request->has('supplier_barcode')
            || $request->has('partId')
            || $request->has('employee')
        ) {
            $dataQuery = $this->damagedStockFilter->filter($request);
        }
        $data = $this->implementDataTableSearch($dataQuery, $request);
         if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new DamagedStockPrintExcel($data, $visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 25;
        $data = $data->with('branch')->paginate($rows);
        return view('admin.damaged_stock.index', compact('data'));
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

        $lastNumber = DamagedStock::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.damaged_stock.create', compact('branches', 'mainTypes', 'subTypes', 'parts', 'number'));
    }

    public function store(CreateRequest $request)
    {
        if ($this->damagedStockService->checkMaxQuantityOfItem($request['items'])) {
            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $data['user_id'] = auth()->id();

            $lastNumber = DamagedStock::where('branch_id', $data['branch_id'])->orderBy('id', 'desc')->first();

            $data['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            $data['total'] = 0;

            $damageStock = DamagedStock::create($data);

            $total = 0;

            foreach ($request['items'] as $item) {

                $item['damaged_stock_id'] = $damageStock->id;
                DamagedStockItem::create($item);
                $total += $item['price'] * $item['quantity'];
            }

            $damageStock->total = $total;

            $damageStock->save();

            if ($request->has('employees') && $request['type'] == 'un_natural') {

                $this->damagedStockService->addEmployees($damageStock, $request['employees']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:damaged-stock.index'))->with(['message' => __('Damaged-stock.created'), 'alert-type' => 'success']);
    }

    public function edit(DamagedStock $damagedStock)
    {
        if ($damagedStock->concession) {
            return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
        }

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('branch_id', $damagedStock->branch_id)
            ->where('status', 1)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('branch_id', $damagedStock->branch_id)
            ->where('status', 1)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $totalQuantity = $damagedStock->items->sum('quantity');

        $employees = EmployeeData::where('status', 1)->where('branch_id', $damagedStock->branch_id)->select('name_' . $this->lang, 'id')->get();

        return view('admin.damaged_stock.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'damagedStock', 'totalQuantity', 'employees'));
    }

    public function update(CreateRequest $request, DamagedStock $damagedStock)
    {

        if ($damagedStock->concession) {
            return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
        }

        if (!$request->has('items')) {
            return redirect()->back()->with(['message' => 'sorry, please select items', 'alert-type' => 'error']);
        }

        if ($this->damagedStockService->checkMaxQuantityOfItem($request['items'])) {
            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->damagedStockService->deleteItems($damagedStock);

            $data = $request->validated();

            $data['total'] = 0;

            $damagedStock->update($data);

            $total = 0;

            foreach ($request['items'] as $item) {

                $item['damaged_stock_id'] = $damagedStock->id;
                DamagedStockItem::create($item);
                $total += $item['price'] * $item['quantity'];
            }

            $damagedStock->total = $total;

            $damagedStock->save();

            if ($request->has('employees') && $request['type'] == 'un_natural') {

                $this->damagedStockService->addEmployees($damagedStock, $request['employees']);

            } else {

                $damagedStock->employees()->detach();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:damaged-stock.index'))->with(['message' => __('Damaged-stock.updated'), 'alert-type' => 'success']);
    }

    public function destroy(DamagedStock $damagedStock)
    {
        if ($damagedStock->concession) {
            return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
        }
        try {
            $damagedStock->forceDelete();
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }
        return redirect(route('admin:damaged-stock.index'))->with(['message' => __('Damaged-stock.deleted'), 'alert-type' => 'success']);
    }

    public function selectPartRaw(Request $request)
    {
        $rules = [
            'part_id' => 'required|integer|exists:parts,id',
            'index' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'] + 1;

            $part = Part::find($request['part_id']);

            $view = view('admin.damaged_stock.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {

//            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }
    }

    public function priceSegments(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'price_id' => 'required|integer|exists:part_prices,id',
            'index' => 'required|integer'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'];

            $priceSegments = PartPriceSegment::where('part_price_id', $request['price_id'])->get();

            $view = view('admin.damaged_stock.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function show(DamagedStock $damagedStock)
    {

        $totalQuantity = $damagedStock->items->sum('quantity');
        return view('admin.damaged_stock.show', compact('damagedStock', 'totalQuantity'));
    }

    public function newEmployeesPercent(Request $request)
    {

        $rules = [
            'employees_count' => 'required|integer',
            'employees_items_count' => 'required|integer',
        ];


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

            $employees_count = $request['employees_count'] + 1;

            $employees_items_count = $request['employees_items_count'] + 1;

            $employees = EmployeeData::where('status', 1)->where('branch_id', $branch_id)->select('name_' . $this->lang, 'id')->get();

            $view = view('admin.damaged_stock.employees.ajax_employee_percent', compact('employees', 'employees_items_count'))->render();

            return response()->json(['view' => $view, 'employees_items_count' => $employees_items_count, 'employees_count' => $employees_count], 200);

        } catch (\Exception $e) {

            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }

    }

    public function deleteEmployee(Request $request)
    {

        $rules = [
            'item_id' => 'required|integer|exists:damaged_stock_employee_data,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            DB::table('damaged_stock_employee_data')->where('id', $request['item_id'])->delete();

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            $damagedStocks = DamagedStock::whereIn('id', $request->ids)->get();
            foreach ($damagedStocks as $damagedStock) {
                if ($damagedStock->concession) {
                    return redirect()->back()->with(['message' => __('sorry, this item has related data'), 'alert-type' => 'error']);
                }
            }
            foreach ($damagedStocks as $damagedStock) {
                $damagedStock->forceDelete();
            }
            return redirect(route('admin:damaged-stock.index'))->with(['message' => __('Damaged-stock.deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:damaged-stock.index'))->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
