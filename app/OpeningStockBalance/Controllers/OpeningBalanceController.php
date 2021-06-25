<?php

namespace App\OpeningStockBalance\Controllers;

use App\Filters\OpeningBalanceFilter;
use App\Models\OpeningBalance;
use Exception;
use App\Models\Store;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\OpeningStockBalance\Models\OpeningBalanceItems;
use App\OpeningStockBalance\Services\BalanceItemService;
use App\Models\OpeningBalance as Model;
use App\OpeningStockBalance\Requests\OpeningBalanceRequest;
use App\OpeningStockBalance\Services\CommonFunctionsService;
use App\OpeningStockBalance\Services\PurchaseInvoiceService;
use App\OpeningStockBalance\Services\DailyRestrictionService;
use App\OpeningStockBalance\Requests\OpeningBalanceEditRequest;

class OpeningBalanceController extends Controller
{
    protected $commonFunctionService;
    protected $purchaseInvoiceService;
    protected $dailyRestrictionService;
    protected $balanceItemService;
    private $is_crud_closed;

    /**
     * @var OpeningBalanceFilter
     */
    protected $openingBalanceFilter;

    function __construct(OpeningBalanceFilter $openingBalanceFilter)
    {
        $this->commonFunctionService = new CommonFunctionsService;
        $this->purchaseInvoiceService = new PurchaseInvoiceService;
        $this->dailyRestrictionService = new DailyRestrictionService;
        $this->balanceItemService = new BalanceItemService;
        $this->is_crud_closed = $this->commonFunctionService->is_any_operation_exists();
        $this->openingBalanceFilter = $openingBalanceFilter;
    }

    function index(Request $request)
    {
        if (!auth()->user()->can('view_opening-balance')) {
            return redirect(route('admin:home'))->with(['authorization' => 'error']);
        }
        $collection = OpeningBalance::query();
        if ($request->hasAny((new OpeningBalance())->getFillable())
            || $request->has('dateFrom')
            || $request->has('dateTo')
            || $request->has('part_name')
            || $request->has('barcode')
            || $request->has('supplier_barcode')
            || $request->has('partId')
        ) {
            $collection = $this->openingBalanceFilter->filter($request);
        }
        $collection = $collection->with('branch')->get();
        return view('opening-balance.index', compact('collection'));
    }

    function buildRow()
    {
        $part_id = isset($_GET['part_id']) && $_GET['part_id'] != '' ? $_GET['part_id'] : null;
        $branch = isset($_GET['branch']) && $_GET['branch'] != '' ? $_GET['branch'] : null;
        if ($part_id == null || $branch == null) {
            return response(['message' => __('opening-balance.choose-part-n-branch'), 'status' => 'error']);
        }
        return response([
            'row_code' => $this->commonFunctionService->buildPartRow($part_id, $branch),
            'status' => 'ok'
        ]);
    }

    function getMainTypes()
    {
        $branch = isset($_GET['branch']) && $_GET['branch'] != '' ? $_GET['branch'] : null;
        $types = $this->commonFunctionService->fetchTypes($branch);
        $options = '<option value="">' . __('opening-balance.select-one') . '</option>';
        foreach ($types as $type) {
            $options .= '<option value="' . $type->id . '"> ' . $type->type . ' </option>';
        }
        return response(['options' => $options]);
    }

    function getSubTypes()
    {
        $type_id = isset($_GET['type_id']) && $_GET['type_id'] != '' ? $_GET['type_id'] : null;
        $branch = isset($_GET['branch']) && $_GET['branch'] != '' ? $_GET['branch'] : null;
        $types = $this->commonFunctionService->fetchSubTypes($branch, $type_id);
        $options = '<option value="">' . __('opening-balance.select-one') . '</option>';
        foreach ($types as $type) {
            $options .= '<option value="' . $type->id . '"> ' . $type->type . ' </option>';
        }
        return response(['options' => $options]);
    }

    function redirect_created()
    {
        return redirect(route('opening-balance.index'))->with([
            'message' => __('opening-balance.balance-created'),
            'alert-type' => 'success'
        ]);
    }

    function redirect_updated()
    {
        return redirect(route('opening-balance.index'))->with([
            'message' => __('opening-balance.balance-edited'),
            'alert-type' => 'success'
        ]);
    }

    function getParts()
    {
        $type_id = isset($_GET['type_id']) && $_GET['type_id'] != '' ? $_GET['type_id'] : null;
        $branch = isset($_GET['branch']) && $_GET['branch'] != '' ? $_GET['branch'] : null;
        $parts_options = '<option value="">' . __('opening-balance.select-one') . '</option>';
        $parts = $this->commonFunctionService->fetchParts($branch, $type_id);

        foreach ($parts as $part) {
            $barcode = $part->prices->first() ? $part->prices->first()->barcode . ' - ' : '';

            $parts_options .= '<option value="' . $part->id . '"> ' . $barcode . $part->name . ' </option>';
        }
        return response(['parts' => $parts_options]);
    }

    function create()
    {
//         if ($this->is_crud_closed) return $this->crud_closed();

        if (!auth()->user()->can('create_opening-balance')) {
            return redirect(route('admin:home'))->with(['authorization' => 'error']);
        }
        $branch_id = authIsSuperAdmin() ? null : auth()->user()->branch_id;
        $branches = $branch_id ? [] : Branch::select('name_ar', 'name_en', 'id')->get();
        $types = $this->commonFunctionService->fetchTypes($branch_id);
        $subTypes = $this->commonFunctionService->fetchSubTypes($branch_id);
        $parts = $this->commonFunctionService->fetchParts($branch_id);
        $last_serial_number = $this->get_last_serial_number();
        return view('opening-balance.create', compact(
            'branches', 'types', 'subTypes', 'parts', 'last_serial_number'
        ));
    }

    function store(OpeningBalanceRequest $request)
    {

//         if ($this->is_crud_closed) return response(['message' => __('opening-balance.crud-closed') ,'location' => route('opening-balance.index')] ,301);

        try {

            DB::beginTransaction();

            $openingBalance = Model::create([
                'branch_id' => $request->branch_id,
                'serial_number' => $this->get_last_serial_number(),
                'operation_date' => $request->operation_date,
                'operation_time' => $request->operation_time,
                'notes' => $request->has('notes') ? $request->notes : '',
                'user_id' => auth()->id()
            ]);

            $total_money = 0;

            foreach ($request->table_data as $row) {
                $this->balanceItemService->create_item($openingBalance->id, $row);
                $total_money += (isset($row['quantity']) ? $row['quantity'] : 0) * (isset($row['buy_price']) ? $row['buy_price'] : 0);
            }

            $openingBalance->update(['total_money' => $total_money]);

            $this->purchaseInvoiceService->after_create($openingBalance);

            DB::commit();

            return response(['message' => 'created']);

        }catch (Exception $e) {

//            dd($e->getMessage());
           return response(['message' => __('opening-balance.try-later') ,'location' => route('opening-balance.index')] ,301);
        }
    }

    function edit($id)
    {
        if (!auth()->user()->can('edit_opening-balance')) {
            return redirect(route('admin:home'))->with(['authorization' => 'error']);
        }

        try {
            $openingBalance = $this->commonFunctionService->fetch_opening_balance($id);

            if ($openingBalance->concession) {
                return redirect()->back()->with(
                    ['message' => __('opening-balance.crud-closed'), 'alert-type' => 'error']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with([
                'message' => __('opening-balance.balance-not-found'),
                'alert-type' => 'error'
            ]);
        }
        $branch_id = authIsSuperAdmin() ? null : auth()->user()->branch_id;
        $branches = $branch_id ? [] : Branch::select('name_ar', 'name_en', 'id')->get();
        $types = $this->commonFunctionService->fetchTypes($branch_id);
        $subTypes = $this->commonFunctionService->fetchSubTypes($branch_id);
        $parts = $this->commonFunctionService->fetchParts($branch_id);
        $stores = Store::where('branch_id', $openingBalance->branch_id)->select('id', 'name_ar', 'name_en')->get();

        return view('opening-balance.edit', compact(
            'branches', 'types', 'subTypes', 'parts', 'openingBalance', 'stores'
        ));
    }

    function update(OpeningBalanceEditRequest $request, $id)
    {
//        if ($this->is_crud_closed) return response(['message' => __('opening-balance.crud-closed'), 'location' => route('opening-balance.index')], 301);

        if (!auth()->user()->can('edit_opening-balance')) {
            return response(['message' => __('opening-balance.not-authorized'), 'location' => route('admin:home')],
                301);
        }

        try {
            $openingBalance = OpeningBalance::findOrFail($id);

            if ($openingBalance->concession) {
                return response([
                    'message' => __('opening-balance.crud-closed'),
                    'location' => route('opening-balance.index')
                ]);
            }
        } catch (Exception $e) {
            return response(['message' => __('opening-balance.balance-not-found')], 400);
        }

        try {
            DB::beginTransaction();
            $this->balanceItemService->remove_items_from_balance(request('deleted_ids'));
            $balance_data = $request->all();
            if (!isset($balance_data['table_data'])) {
                throw new Exception(__('opening-balance.items-required'));
            }
            $balance_items = $balance_data['table_data'];
            unset($balance_data['table_data']);
            $total_money = 0;
            foreach ($balance_items as $item_id => $balance_item) {
                $old_item = OpeningBalanceItems::find($item_id);

                if ($old_item) {
                    $this->balanceItemService->update_item($old_item, $balance_item);
                } else {
                    $this->balanceItemService->create_item($openingBalance->id, $balance_item);
                }
                $total_money += $balance_item['buy_price'] * $balance_item['quantity'];
            }
            $balance_data['total_money'] = $total_money;
            $openingBalance->update($balance_data);
            $this->purchaseInvoiceService->after_edit($openingBalance);
            DB::commit();
            return response(['message' => 'created']);
//            return redirect(route('opening-balance.index'))->with(['message' => __('opening-balance.updated'), 'alert-type' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage()], 400);
        }
    }

    private function run_delete(OpeningBalance $openingBalance)
    {
        $this->purchaseInvoiceService->after_delete($openingBalance);
        $this->balanceItemService->remove_items_from_balance($openingBalance->items()->pluck('id')->toArray());
        $openingBalance->items()->forceDelete();
        $openingBalance->forceDelete();
    }

    function delete($id)
    {
        if (!auth()->user()->can('delete_opening-balance')) {
            return redirect(route('admin:home'))->with(['authorization' => 'error']);
        }

        try {
            $openingBalance = OpeningBalance::findOrFail($id);

            if ($openingBalance->concession) {
                return redirect()->back()->with([
                    'message' => __('sorry, this item have related data'),
                    'alert-type' => 'error'
                ]);
            }
        } catch (Exception $e) {
            return redirect()->back()->with([
                'message' => __('opening-balance.balance-not-found'),
                'alert-type' => 'error'
            ]);
        }

        try {
            DB::beginTransaction();
            $this->run_delete($openingBalance);
            DB::commit();
            return redirect()->back()->with([
                'message' => __('opening-balance.deleted'),
                'alert-type' => 'success'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    function deleteSelected()
    {
//        if ($this->is_crud_closed) return $this->crud_closed();

        if (!auth()->user()->can('delete_opening-balance')) {
            return redirect(route('admin:home'))->with(['authorization' => 'error']);
        }

        if (!request()->has('ids')) {
            return redirect()->back()->with([
                'message' => __('opening-balance.select-row-at-least'),
                'alert-type' => 'error'
            ]);
        }

        DB::beginTransaction();

        foreach (request('ids') as $index => $id) {
            if ($index == 0) {
                continue;
            }
            try {
                $openingBalance = OpeningBalance::findOrFail($id);
                if ($openingBalance->concession) {
                    return redirect()->back()->with([
                        'message' => __('sorry, this item have related data'),
                        'alert-type' => 'error'
                    ]);
                }
                $this->run_delete($openingBalance);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with([
                    'message' => $e->getMessage(),
                    'alert-type' => 'error'
                ]);
            }
        }
        DB::commit();
        return redirect()->back()->with([
            'message' => __('opening-balance.rows-deleted'),
            'alert-type' => 'success'
        ]);
    }

    public function show(OpeningBalance $openingBalance)
    {
        return view('opening-balance.show', compact('openingBalance'));
    }

    private function get_last_serial_number()
    {
        $last_number = Model::select('serial_number')->orderBy('id', 'desc')->first();
        return $last_number ? (int)$last_number->serial_number + 1 : 1;
    }

    private function crud_closed()
    {
        return redirect(route('opening-balance.index'))->with([
            'message' => __('opening-balance.crud-closed'),
            'alert-type' => 'warning'
        ]);
    }
}
