<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\DataExportCore\SpareParts;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\Concession\CreateRequest;
use App\Models\Branch;
use App\Models\Concession;
use App\Models\ConcessionType;
use App\Models\ConcessionTypeItem;
use App\Services\ConcessionService;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ConcessionController extends AbstractController
{
    public $lang;
    public $concessionService;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->concessionService = new ConcessionService();
    }

    public function getSortFields(): array
    {
        return [
            'id' => 'id',
            'date' => 'date',
            'number' => 'number',
            'total_quantity' => 'total_quantity',
            'type' => 'type',
            'status' => 'status',
            'created-at' => 'created_at',
            'updated-at' => 'updated_at',
        ];
    }

    public function index(Request $request)
    {
        $rows = $request->has('rows') ? $request->rows : 25;
        $concessions = $this->concessionService->search($request);
        $concessions = $this->implementDataTableSearch($concessions, $request);
        $concessions = $concessions->paginate($rows);
        $additionalData['concessions'] = Concession::select('id', 'number')->get();
        $concessionTypes = ConcessionType::select('id', 'name_' . $this->lang)->get();
        return view('admin.concessions.index', compact('concessions', 'concessionTypes', 'additionalData'));
    }

    public function archive(Request $request)
    {
        $concessions = $this->concessionService->search($request, true);
        $concessions = $concessions->get();
        $concessionTypes = ConcessionType::select('id', 'name_' . $this->lang)->get();
        return view('admin.concessions.archive', compact('concessions', 'concessionTypes'));
    }

    public function create(Request  $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $concessionTypes = ConcessionType::where('branch_id', $branch_id)
            ->where('type', 'add')
            ->select('id', 'name_' . $this->lang, 'type')
            ->get();

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $lastNumber = Concession::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.concessions.create', compact('concessionTypes', 'branches', 'number'));
    }

    public function store(CreateRequest $request)
    {
        try {

            $concessionType = ConcessionType::find($request['concession_type_id']);

            if ($concessionType->type != $request['type']) {
                return redirect()->back()->with(['message' => 'sorry, concession type not valid', 'alert-type' => 'error']);
            }

            $concessionTypeItem = $concessionType->concessionItem;

            if (!$concessionTypeItem) {
                return redirect()->back()->with(['message' => 'sorry, concession type not have item', 'alert-type' => 'error']);
            }

            $className = $concessionTypeItem->model;

            if ($this->concessionService->checkItemHasOldConcession($className, $request['item_id'],$concessionType->type)) {
                return redirect()->back()->with(['message' => 'sorry, this item has old concession', 'alert-type' => 'error']);
            }

            $data = $request->all();

            $data['concessionable_type'] = $className;

            DB::beginTransaction();

            $concessionData = $this->concessionService->concessionData($data, 'create');

            $concession = Concession::create($concessionData);

            if ($className == 'StoreTransfer' && $concession->type == 'add') {

                if (!$this->concessionService->checkStoreTransferHasConcession($concession)) {

                    return redirect()->back()->with(['message' => __('sorry, please create withdrawal concession first and accept'),
                        'alert-type' => 'error']);
                }
            }

            $this->concessionService->createConcessionItems($concession);

            if ($concession->status == 'accepted') {
               $acceptQuantityData = $this->concessionService->acceptQuantity($concession);

               if (isset($acceptQuantityData['status']) && !$acceptQuantityData['status']) {

                   $message = isset($acceptQuantityData['message']) ? $acceptQuantityData['message'] : '';
                   return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
               }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:concessions.index'))->with(['message' => 'Concession created successfully', 'alert-type' => 'success']);
    }

    public function edit(Concession $concession)
    {
        if ($concession->status != 'pending') {

            return redirect()->back()->with(['message' => 'sorry, you can note update this item', 'alert-type' => 'error']);
        }

        $concessionTypes = ConcessionType::where('branch_id', $concession->branch_id)
            ->where('type', $concession->type)
            ->select('id', 'name_' . $this->lang, 'type')
            ->get();

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $model = $concession->concessionable_type;

        $modelName = $concession->concessionType->concessionItem ? $concession->concessionType->concessionItem->model : '';

        $concessionTypeItems = $this->getConcessionTypeItems($concession, $modelName, $model);

        $totalPrice = 0;

        foreach ($concession->concessionItems as $item) {

            $totalPrice += $item->price * $item->quantity;
        }

        return view('admin.concessions.edit',
            compact('concessionTypes', 'branches', 'concession', 'concessionTypeItems', 'totalPrice'));
    }

    public function getConcessionTypeItems ($concession, $modelName, $model) {

        $concessionTypeItems = $model::where('branch_id', $concession->branch_id);

        if ($modelName == 'Settlement' && $concession->concessionType->type == 'add') {

            $concessionTypeItems->where('type', 'positive');
        }

        if ($modelName == 'Settlement' && $concession->concessionType->type == 'withdrawal') {

            $concessionTypeItems->where('type', 'negative');
        }

        if ($modelName == 'StoreTransfer' && $concession->type == 'add') {

            $concessionTypeItems->whereDoesntHave('concession', function ($q) use ($concession) {

                $q->where('type', 'add')->where('concessionable_id','!=', $concession->concessionable_id);

            })->whereHas('concession', function ($q) use ($concession) {

                $q->where('type', 'withdrawal'); // and status accepted
            });

        }else {

            $concessionTypeItems->whereHas('concession', function ($q) use ($concession) {

                $q->where('concessionable_id', $concession->concessionable_id);

            })->orDoesntHave('concession');
        }

        $concessionTypeItems = $concessionTypeItems->get();

        return $concessionTypeItems;
    }

    public function update(CreateRequest $request, Concession $concession)
    {
        if ($concession->status != 'pending') {

            return redirect()->back()->with(['message' => 'sorry, you can note update this item', 'alert-type' => 'error']);
        }

        try {

            $concessionType = ConcessionType::find($request['concession_type_id']);

            if ($concessionType->type != $request['type']) {

                return redirect()->back()->with(['message' => 'sorry, concession type not valid', 'alert-type' => 'error']);
            }

            $concessionTypeItem = $concessionType->concessionItem;

            if (!$concessionTypeItem) {

                return redirect()->back()->with(['message' => 'sorry, concession type not have item', 'alert-type' => 'error']);
            }

            $data = $request->all();

            $data['concessionable_type'] = $concessionTypeItem->model;

            DB::beginTransaction();

            $this->concessionService->deleteConcessionItems($concession);

            $concessionData = $this->concessionService->concessionData($data, 'update');

            $className = $concession->concessionable ? class_basename($concession->concessionable) : '';


            if ($concession->concessionable_id != $data['item_id'] &&
                $this->concessionService->checkItemHasOldConcession($className, $request['item_id'],$concessionType->type)) {

                return redirect()->back()->with(['message' => 'sorry, this item has old concession', 'alert-type' => 'error']);
            }

            if ($className == 'StoreTransfer' && $concession->type == 'add') {

                if (!$this->concessionService->checkStoreTransferHasConcession($concession)) {

                    return redirect()->back()->with(['message' => __('sorry, please create withdrawal concession first'), 'alert-type' => 'error']);
                }
            }

            $concession->update($concessionData);

            $this->concessionService->createConcessionItems($concession);

            if ($concession->status == 'accepted') {

                $this->concessionService->acceptQuantity($concession);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:concessions.index'))->with(['message' => 'Concession created successfully', 'alert-type' => 'success']);
    }

    public function show(Request $request)
    {

        $concession = Concession::findOrFail($request['concession_id']);

        $view = view('admin.concessions.show', compact('concession'))->render();

        return response()->json(['view' => $view]);
    }

    public function destroy(Concession $concession)
    {
        if ($concession->status == 'accepted') {
            return redirect()->back()->with([
                'message' => __('sorry, this item accepted'),
                'alert-type' => 'error'
            ]);
        }
        $concession->forceDelete();
        return redirect()->back()->with(['message' => 'Concession deleted successfully', 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids) && isset($request->archive)) {
            $concessions = Concession::whereIn('id', $request->ids)->get();
            foreach ($concessions as $concession) {
                $concession->delete();
            }
            return back()->with(['message' => __('words.selected-row-archived'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->restore)) {
            $concessions = Concession::withTrashed()->whereIn('id', $request->ids)->get();
            foreach ($concessions as $concession) {
                $concession->restore();
            }
            return back()->with(['message' => __('words.selected-row-restored'), 'alert-type' => 'success']);
        }

        if (isset($request->ids)) {
            $concessions = Concession::whereIn('id', $request->ids)->get();
            foreach ($concessions as $concession) {
                if ($concession->status == 'accepted') {
                    return redirect()->back()->with([
                        'message' => __('sorry, this item accepted'),
                        'alert-type' => 'error'
                    ]);
                }
                $concession->forceDelete();
            }
            return redirect()->back()->with(['message' => 'Concession deleted successfully', 'alert-type' => 'success']);
        }
        return redirect(route('admin:damaged-stock.index'))->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function restoreDelete(int $id)
    {
        try {
            $concession = Concession::withTrashed()->findOrFail($id);
            $concession->restore();
            return back()->with(['message' => __('words.data-restored-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-restore-date-from-archive'), 'alert-type' => 'error']);
        }
    }

    public function archiveData(Concession $concession)
    {
        $concession->delete();
        return redirect()->back()->with(['message' => __('words.words.data-archived-success'), 'alert-type' => 'success']);
    }
}
