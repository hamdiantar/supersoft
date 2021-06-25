<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseRequest\CreateRequest;
use App\Models\Branch;
use App\Models\Part;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\SparePart;
use App\Services\PurchaseRequestService;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $purchaseRequestServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseRequestServices = new PurchaseRequestService();
    }

    public function index()
    {
        $data = PurchaseRequest::get();
        return view('admin.purchase_requests.index', compact('data'));
    }

    public function create (Request $request) {

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

        $lastNumber = PurchaseRequest::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.purchase_requests.create', compact('branches', 'mainTypes', 'subTypes', 'parts', 'number'));
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = $request->validated();

            $purchaseRequestData = $this->purchaseRequestServices->purchaseRequestData($data);

            $purchaseRequestData['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;;

            $lastNumber = PurchaseRequest::where('branch_id', $purchaseRequestData['branch_id'])->orderBy('id', 'desc')->first();

            $purchaseRequestData['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            $purchaseRequestData['user_id'] = auth()->id();

            $purchaseRequest = PurchaseRequest::create($purchaseRequestData);

            if (isset($data['items'])) {

                foreach ($data['items'] as $item) {

                    $itemData = $this->purchaseRequestServices->purchaseRequestItemData($item);

                    $itemData['purchase_request_id'] = $purchaseRequest->id;

                    $purchaseRequestItem = PurchaseRequestItem::create($itemData);

                    if (isset($item['item_types'])) {

                        $purchaseRequestItem->spareParts()->attach($item['item_types']);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-created'), 'alert-type' => 'success']);
    }

    public function show (PurchaseRequest $purchaseRequest) {

        return view('admin.purchase_requests.show', compact('purchaseRequest'));
    }

    public function print(Request $request)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($request['purchase_request_id']);

        $view = view('admin.purchase_requests.print', compact('purchaseRequest'))->render();

        return response()->json(['view' => $view]);
    }

    public function edit(Request $request, PurchaseRequest $purchaseRequest)
    {
        if (!$request->has('request_type') && $purchaseRequest->status != 'under_processing') {
            return redirect()->back()->with(['message' => __('you can not update, finished for editing'), 'alert-type' => 'error']);
        }

        if ($request->has('request_type') && $purchaseRequest->status != 'ready_for_approval') {
            return redirect()->back()->with(['message' => __('you can not update, finished for approval'), 'alert-type' => 'error']);
        }

        $request_type = $request->has('request_type') ? 'approval' : 'edit';

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $branch_id = $purchaseRequest->branch_id;

        $mainTypes = SparePart::where('branch_id', $branch_id)
            ->where('status', 1)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('branch_id', $branch_id)
            ->where('status', 1)
            ->select('name_' . $this->lang, 'id')
            ->get();

        return view('admin.purchase_requests.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'purchaseRequest', 'request_type'));
    }

    public function update(CreateRequest $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status == 'ready_for_approval') {
            return redirect()->back()->with(['message' => 'you can not update', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->validated();

            $this->purchaseRequestServices->resetItems($purchaseRequest);

            $purchaseRequestData = $this->purchaseRequestServices->purchaseRequestData($data);

            $purchaseRequest->update($purchaseRequestData);

            if (isset($data['items'])) {

                foreach ($data['items'] as $item) {

                    $itemData = $this->purchaseRequestServices->purchaseRequestItemData($item);

                    $itemData['purchase_request_id'] = $purchaseRequest->id;

                    $purchaseRequestItem = PurchaseRequestItem::create($itemData);

                    if (isset($item['item_types'])) {

                        $purchaseRequestItem->spareParts()->attach($item['item_types']);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            dd($e->getMessage());

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-updated'), 'alert-type' => 'success']);

    }

    public function selectPart(Request $request)
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

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $partMainTypes = $part->spareParts()->where('status', 1)
                ->where('branch_id', $branch_id)->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

            $partTypes = $this->purchaseRequestServices->getPartTypes($partMainTypes, $part->id);

            $partTypesView = view('admin.purchase_requests.part_types', compact( 'part','index', 'partTypes'))->render();

            $view = view('admin.purchase_requests.part_raw', compact('part', 'index', 'partTypes'))->render();

            return response()->json(['parts' => $view, 'partTypesView'=> $partTypesView,  'index' => $index], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        try {

            DB::beginTransaction();

            $this->purchaseRequestServices->deletePurchaseRequest($purchaseRequest);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-deleted'), 'alert-type' => 'success']);
    }

    public function approval (CreateRequest $request, PurchaseRequest $purchaseRequest) {

        try {

            DB::beginTransaction();

            $purchaseRequest->status = $request['status'];
            $purchaseRequest->save();

            if (isset($request['items'])) {

                foreach ($request['items'] as $item) {

                    $purchaseRequestItem = PurchaseRequestItem::find($item['item_id']);

                    $purchaseRequestItem->approval_quantity = $item['approval_quantity'];

                    $purchaseRequestItem->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-approved'), 'alert-type' => 'success']);
    }
}
