<?php

namespace App\Http\Controllers\Admin;

use App\Filters\OpeningBalanceFilter;
use App\Http\Controllers\DataExportCore\OpeningBalancePrintExcel;
use App\Http\Controllers\DataExportCore\SpareParts;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\OpeningBalance\CreateRequest;
use App\Models\Branch;
use App\Models\OpeningBalance;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\SparePart;
use App\OpeningStockBalance\Models\OpeningBalanceItems;
use App\Services\OpeningBalanceServices;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OpeningBalanceController extends AbstractController
{
    use SubTypesServices;

    /**
     * @var OpeningBalanceFilter
     */
    protected $openingBalanceFilter;

    /**
     * @var OpeningBalanceServices
     */
    protected $openingBalanceServices;

    /**
     * @var string
     */
    public $lang;

    function __construct(OpeningBalanceFilter $openingBalanceFilter, OpeningBalanceServices $openingBalanceServices)
    {
        $this->lang = App::getLocale();
        $this->openingBalanceFilter = $openingBalanceFilter;
        $this->openingBalanceServices = $openingBalanceServices;
    }

    public function getSortFields(): array
    {
        return [
            'id' => 'id',
            'operation_date' => 'operation_date',
            'serial_number' => 'serial_number',
            'total_money' => 'total_money',
            'created-at' => 'created_at',
            'updated-at' => 'updated_at',
        ];
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
        $collection = $this->implementDataTableSearch($collection, $request);
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new OpeningBalancePrintExcel($collection, $visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 25;
        $collection = $collection->with('branch')->paginate($rows)->appends(request()->query());

        return view('admin.opening_balance.index', compact('collection'));
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

        $lastNumber = OpeningBalance::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 2000000;

        return view('admin.opening_balance.create', compact('branches', 'mainTypes', 'subTypes', 'parts', 'number'));
    }

    public function store(CreateRequest $request)
    {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message' => 'sorry, please select items', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $openingBalanceData = $this->openingBalanceServices->openingBalanceData($data);

            $openingBalanceData['user_id'] = auth()->id();

            $openingBalanceData['branch_id'] = authIsSuperAdmin() ? $data['branch_id'] : auth()->user()->branch_id;

            $lastNumber = OpeningBalance::where('branch_id', $openingBalanceData['branch_id'])
                ->orderBy('id', 'desc')
                ->first();

            $openingBalanceData['serial_number'] = $lastNumber ? $lastNumber->number + 1 : 2000000;

            $openingBalance = OpeningBalance::create($openingBalanceData);

            foreach ($data['items'] as $item) {

                $itemData = $this->openingBalanceServices->openingBalanceItemData($item);
                $itemData['opening_balance_id'] = $openingBalance->id;
                OpeningBalanceItems::create($itemData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:opening-balance.index'))->with(['message' => __('opening.balance.created.successfully'), 'alert-type' => 'success']);
    }

    public function edit(OpeningBalance $openingBalance)
    {
        $branch_id = $openingBalance->branch_id;

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

        return view('admin.opening_balance.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'openingBalance'));
    }

    public function show(OpeningBalance $openingBalance)
    {
        return view('admin.opening_balance.show', compact('openingBalance'));
    }

    public function update(CreateRequest $request, OpeningBalance $openingBalance)
    {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message' => 'sorry, please select items', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->openingBalanceServices->resetOpeningBalanceItems($openingBalance);

            $data = $request->all();

            $openingBalanceData = $this->openingBalanceServices->openingBalanceData($data);

            $openingBalance->update($openingBalanceData);

            foreach ($data['items'] as $item) {

                $itemData = $this->openingBalanceServices->openingBalanceItemData($item);
                $itemData['opening_balance_id'] = $openingBalance->id;
                OpeningBalanceItems::create($itemData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:opening-balance.index'))->with(['message' => __('opening.balance.updated.successfully'), 'alert-type' => 'success']);
    }

    public function destroy (OpeningBalance $openingBalance) {

        try {

            if ($openingBalance->concession) {
                return redirect()->back()->with([
                    'message' => __('sorry, this item have related data'),
                    'alert-type' => 'error'
                ]);
            }

            foreach ($openingBalance->items as $item) {
                $item->delete();
            }

            $openingBalance->delete();

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('opening.balance.deleted.successfully'), 'alert-type'=>'success']);
    }

    function deleteSelected()
    {
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

                foreach ($openingBalance->items as $item) {
                    $item->delete();
                }

                $openingBalance->delete();

            } catch (\Exception $e) {
                DB::rollBack();

                return redirect()->back()->with(['message' => $e->getMessage(), 'alert-type' => 'error']);
            }
        }
        DB::commit();
        return redirect()->back()->with(['message' => __('opening-balance.rows-deleted'), 'alert-type' => 'success']);
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

            $view = view('admin.opening_balance.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {
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

            $view = view('admin.opening_balance.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }
}
