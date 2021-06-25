<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Part;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationItem;
use App\Models\PurchaseRequest;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use App\Services\PurchaseQuotationCompareServices;
use App\Services\SupplyOrderServices;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PurchaseQuotationCompareController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $purchaseQuotationCompareServices;
    public $supplyOrderServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseQuotationCompareServices = new PurchaseQuotationCompareServices();
        $this->supplyOrderServices = new SupplyOrderServices();
    }

    public function index(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['quotations'] = $this->purchaseQuotationCompareServices->filter($request, $branch_id);

        $data['purchase_quotations'] = PurchaseQuotation::where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        $data['purchase_request'] = PurchaseRequest::where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        $data['parts'] = Part::where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang)
            ->get();

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        $data['suppliers'] = Supplier::where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang)
            ->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $data['partsTypes'] = $this->getAllPartTypes($mainTypes);

        return view('admin.purchase_quotations_compare.index', compact('data'));
    }

    public function store(Request $request)
    {
        $rules = [
            'purchase_quotations_items' => 'required',
            'purchase_quotations_items.*' => 'required|integer|exists:purchase_quotation_items,id',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        $this->validate($request, $rules);

        $checkItemsData = $this->purchaseQuotationCompareServices->checkItems($request['purchase_quotations_items']);

        if (isset($checkItemsData['status']) && !$checkItemsData['status']) {

            $message = isset($checkItemsData['message']) ? $checkItemsData['message'] : 'sorry, not valid data';
            return redirect()->back()->with(['message' => __($message), 'alert-type' => 'error']);
        }


        try {

            if (!isset($checkItemsData['suppliersId']) && !isset($checkItemsData['suppliersId'][0])) {
                return redirect()->back()->with(['message' => __('supplier not valid'), 'alert-type' => 'error']);
            }

            if (!isset($checkItemsData['purchase_request_ids']) && !isset($checkItemsData['purchase_request_ids'][0])) {
                return redirect()->back()->with(['message' => __('purchase request not valid'), 'alert-type' => 'error']);
            }

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $lastNumber = SupplyOrder::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

            $number = $lastNumber ? intval($lastNumber->number) + 1 : 1;

            $data = [
                'number' => $number,
                'supplier_id' => $checkItemsData['suppliersId'][0],
                'purchase_request_id' => $checkItemsData['purchase_request_ids'][0],
                'type' => $checkItemsData['purchase_request_ids'][0] ? 'from_purchase_request' : 'normal',
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => Carbon::now()->format('H:i:s'),
                'user_id' => auth()->id(),
                'discount' => 0,
                'discount_type' => 'amount',
            ];

            foreach ($request['purchase_quotations_items'] as $index => $itemId) {

                $index += 1;

                $item = $item = PurchaseQuotationItem::find($itemId);

                $data['items'][$index]['part_id'] = $item->part_id;
                $data['items'][$index]['part_price_id'] = $item->part_price_id;
                $data['items'][$index]['quantity'] = $item->quantity;
                $data['items'][$index]['price'] = $item->price;
                $data['items'][$index]['discount'] = $item->discount;
                $data['items'][$index]['discount_type'] = $item->discount_type;
                $data['items'][$index]['taxes'] = $item->taxes->pluck('id')->toArray();
            }

            $supplyOrderData = $this->supplyOrderServices->supplyOrderData($data);

            $supplyOrderData['user_id'] = auth()->id();
            $supplyOrderData['branch_id'] = $branch_id;

            DB::beginTransaction();

            $supplyOrder = SupplyOrder::create($supplyOrderData);

            if (isset($checkItemsData['purchase_quotations'])) {

                $quotations = array_values(array_unique($checkItemsData['purchase_quotations']));
                $supplyOrder->purchaseQuotations()->attach($quotations);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->supplyOrderServices->supplyOrderItemData($item);
                $itemData['supply_order_id'] = $supplyOrder->id;
                $supplyOrderItem = SupplyOrderItem::create($itemData);

                if (isset($item['taxes'])) {
                    $supplyOrderItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        $url = route('admin:supply-orders.edit', $supplyOrder->id);

        return redirect($url)->with(['message' => __('supply.orders.created.successfully'), 'alert-type' => 'success']);
    }
}
