<?php

namespace App\Http\Controllers\Admin;

use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\Country;
use App\Models\CustomerContact;
use App\Models\Supplier;
use App\Models\SupplierContact;
use App\Models\SupplierLibrary;
use App\Services\LibraryServices;
use App\Services\SuppliersGroupsService;
use App\Services\SuppliersGroupsTreeService;
use App\Traits\SupplierSubGroups;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\SupplierGroup;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DataExportCore\Suppliers;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\Suppliers\CreateSuppliersRequest;
use App\Http\Requests\Admin\Suppliers\UpdateSuppliersRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SuppliersController extends Controller
{
    use LibraryServices, SupplierSubGroups;

    /**
     * @var string
     */
    const view_path = 'admin.supplier_group_tree';

    /**
     * @var SuppliersGroupsService
     */
    protected $suppliersGroupsService;
    public $lang;

    /**
     * @var SuppliersGroupsTreeService
     */
    protected $suppliersGroupsTreeService;

    function __construct(
        SuppliersGroupsService $suppliersGroupsService,
        SuppliersGroupsTreeService $suppliersGroupsTreeService
    ) {
        $this->suppliersGroupsService = $suppliersGroupsService;
        $this->suppliersGroupsTreeService = $suppliersGroupsTreeService;
        $this->lang = App::getLocale();
    }
//    public function __construct()
//    {
//        $this->middleware('permission:view_suppliers');
//        $this->middleware('permission:create_suppliers',['only'=>['create','store']]);
//        $this->middleware('permission:update_suppliers',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_suppliers',['only'=>['destroy','deleteSelected']]);
//    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) {
                $sort_method = 'desc';
            }
            $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $sort_fields = [
                'id' => 'id',
                'supplier_name' => 'name_' . $lang,
                'supplier_group' => 'group_id',
                'supplier_type' => 'type',
                'funds_for' => 'funds_for',
                'funds_on' => 'funds_on',
                'status' => 'status',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at'
            ];
            $suppliers = Supplier::orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $suppliers = Supplier::orderBy('id', 'DESC');
        }
//        if(!authIsSuperAdmin())
//            $suppliers->where('branch_id', auth()->user()->branch_id);

        if ($request->has('name') && $request['name'] != '') {
            $suppliers->where('id', $request['name']);
        }

        if ($request->has('branch_id') && $request['branch_id'] != '') {
            $suppliers->where('branch_id', $request['branch_id']);
        }

        if ($request->has('group_id') && $request['group_id'] != '') {
            $suppliers->where('main_groups_id', 'like', '%' . serialize($request['group_id']) . '%')
                ->orWhere('sub_groups_id', 'like', '%' . serialize($request['group_id']) . '%');
        }

        if ($request->has('supplier_type') && $request['supplier_type'] != '') {
            if ($request['supplier_type'] === 'all') {
                $suppliers->where('supplier_type', 'supplier')
                    ->orWhere('supplier_type', 'contractor')
                    ->orWhere('supplier_type', 'both_together');
            } else {
                $suppliers->where('supplier_type', $request['supplier_type']);
            }
        }

        if ($request->has('country_id') && $request['country_id'] != '') {
            $suppliers->where('country_id', $request['country_id']);
        }

        if ($request->has('phone_1') && $request['phone_1'] != '') {
            $suppliers->where('phone_1', $request['phone_1']);
        }

        if ($request->has('type') && $request['type'] != '') {
            $suppliers->where('type', $request['type']);
        }

        if ($request->has('commercial_number') && $request['commercial_number'] != '') {
            $suppliers->where('commercial_number', $request['commercial_number']);
        }

        if ($request->has('active') && $request['active'] != '') {
            $suppliers->where('status', 1);
        }

        if ($request->has('inactive') && $request['inactive'] != '') {
            $suppliers->where('status', 0);
        }

        $rows = $request->has('rows') ? $request->rows : 10;
        if ($request->has('key')) {
            $key = $request->key;
            $suppliers->where(function ($q) use ($key) {
                $q->where('name_en', 'like', "%$key%")
                    ->orWhere('name_ar', 'like', "%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            $suppliers = $suppliers->with('suppliersGroup');
            return (new ExportPrinterFactory(new Suppliers($suppliers, $visible_columns), $request->invoker))();
        }
        $suppliers = $suppliers->paginate($rows)->appends(request()->query());

        $branches = filterSetting() ? Branch::all()->pluck('name', 'id') : null;
        $suppliers_search = filterSetting() ? Supplier::all() : null;
        $groups = filterSetting() ? SupplierGroup::all()->pluck('name', 'id') : null;
        $countries = filterSetting() ? Country::all()->pluck('name', 'id') : null;

        return view('admin.suppliers.index',
            compact('suppliers', 'suppliers_search', 'branches', 'groups', 'countries'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name', 'id');

        $mainGroups = SupplierGroup::where('status', 1)
            ->where('supplier_group_id', null)
            ->select('id','name_' . $this->lang)
            ->get();

        $subGroups = $this->getSubGroups($mainGroups);

        return view('admin.suppliers.create', compact('branches', 'mainGroups', 'subGroups'));
    }

    public function store(CreateSuppliersRequest $request)
    {
        if (!auth()->user()->can('create_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $data = $request->validated();

            $data['status'] = 0;

            if ($request->has('status')) {
                $data['status'] = 1;
            }
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $supplier = Supplier::create($data);

            if ($request['contacts']) {
                foreach ($request['contacts'] as $contact) {
                    SupplierContact::create([
                        'supplier_id' => $supplier->id,
                        'phone_1' => $contact['phone_1'],
                        'phone_2' => $contact['phone_2'],
                        'address' => $contact['address'],
                        'name' => $contact['name'],
                    ]);
                }
            }

            if ($request['bankAccount']) {
                foreach ($request['bankAccount'] as $bankAccount) {
                    BankAccount::create([
                        'supplier_id' => $supplier->id,
                        'bank_name' => $bankAccount['bank_name'],
                        'account_name' => $bankAccount['account_name'],
                        'branch' => $bankAccount['branch'],
                        'account_number' => $bankAccount['account_number'],
                        'iban' => $bankAccount['iban'],
                        'swift_code' => $bankAccount['swift_code'],
                    ]);
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }
        return redirect(route('admin:suppliers.index'))
            ->with(['message' => __('words.supplier-created'), 'alert-type' => 'success']);
    }

    public function show(Supplier $supplier)
    {
        if (!auth()->user()->can('view_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $supplierGroupsTreeMain = $this->getMainSupplierGroupsAsTree($supplier->main_groups_id);
        $supplierGroupsTreeSub = $this->getSubSupplierGroupsAsTree($supplier->sub_groups_id);
        return view('admin.suppliers.show', compact('supplier', 'supplierGroupsTreeMain', 'supplierGroupsTreeSub'));
    }

    public function edit(Supplier $supplier)
    {
        if (!auth()->user()->can('update_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name', 'id');

        $mainGroups = SupplierGroup::where('status', 1)
            ->where('branch_id', $supplier->branch_id)
            ->where('supplier_group_id', null)
            ->select('id','name_' . $this->lang)
            ->get();

        if ($supplier->group_id) {

            $groups = $mainGroups->where('id', $supplier->group_id);

            $orders = array_keys($groups->toArray());

            $order = isset($orders[0]) ? $orders[0] + 1 : 1;

            $subGroups = $this->getSubGroups($groups, $order);

        }else {

            $subGroups = $this->getSubGroups($mainGroups);
        }

        return view('admin.suppliers.edit', compact('branches', 'supplier','mainGroups','subGroups'));
    }

    public function update(UpdateSuppliersRequest $request, Supplier $supplier)
    {
        if (!auth()->user()->can('update_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $data = $request->validated();

            if (!$request->has("main_groups_id")) {
                $data['main_groups_id'] = null;
            }

            if (!$request->has("sub_groups_id")) {
                $data['sub_groups_id'] = null;
            }
            $data['status'] = 0;
            if (!$request->has('supplier_type1')) {
                $data['supplier_type1'] = null;
            }
            if (!$request->has('supplier_type2')) {
                $data['supplier_type2'] = null;
            }
            if ($request->has('status')) {
                $data['status'] = 1;
            }
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $supplier->update($data);
            if ($request['contacts']) {
                foreach ($request['contacts'] as $contact) {
                    SupplierContact::create([

                        'supplier_id' => $supplier->id,
                        'phone_1' => $contact['phone_1'],
                        'phone_2' => $contact['phone_2'],
                        'address' => $contact['address'],
                        'name' => $contact['name'],
                    ]);
                }
            }
            if ($request['contactsUpdate']) {
                foreach ($request['contactsUpdate'] as $contactData) {
                    $contact = SupplierContact::find($contactData['contactId']);
                    if ($contact) {
                        $contact->update($contactData);
                    }
                }
            }
            if ($request['bankAccount']) {
                foreach ($request['bankAccount'] as $bankAccount) {
                    BankAccount::create([
                        'supplier_id' => $supplier->id,
                        'bank_name' => $bankAccount['bank_name'],
                        'account_name' => $bankAccount['account_name'],
                        'branch' => $bankAccount['branch'],
                        'account_number' => $bankAccount['account_number'],
                        'iban' => $bankAccount['iban'],
                        'swift_code' => $bankAccount['swift_code'],
                    ]);
                }
            }

            if ($request['bankAccountUpdate']) {
                foreach ($request['bankAccountUpdate'] as $bankAccountData) {
                    $bankAccount = BankAccount::find($bankAccountData['bankAccountId']);
                    if ($bankAccount) {
                        $bankAccount->update($bankAccountData);
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:suppliers.index'))
            ->with(['message' => __('words.supplier-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Supplier $supplier)
    {
        if (!auth()->user()->can('delete_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $supplier->delete();
        return redirect(route('admin:suppliers.index'))
            ->with(['message' => __('words.supplier-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_suppliers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            Supplier::whereIn('id', $request->ids)->delete();
            return redirect(route('admin:suppliers.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:suppliers.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getSupplierGroupsByBranch(Request $request)
    {
        $mainGroup =  mainSuppliersGroupsSelectAsTree(
            isset($supplier) ? $supplier : NULL,
            'removeToNewData',
            isset($supplier) ? $supplier->id : NULL,
            isset($request->branch_id) ? $request->branch_id : NULL
        );
        $subGroup = subSupplierGroupsSelectAsTree(
            isset($supplier) ? $supplier : NULL,
            'removeToNewData',
            isset($supplier) ? $supplier->id : NULL,
            isset($mainGroupIds) ? $mainGroupIds : [],
            isset($request->branch_id) ? $request->branch_id : NULL
        );
        return response()->json([
            'mainGroup' => $mainGroup,
            'subGroup' => $subGroup,
        ]);
    }

    function getSuppliers()
    {
        try {
            $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : null;
            $choosed_acc = isset($_GET['choosed_acc']) && $_GET['choosed_acc'] != '' ? $_GET['choosed_acc'] : null;
            $suppliers = Supplier::when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
                ->get();
            $html_select = "<option value=''>" . __('Select One') . "</option>";
            foreach ($suppliers as $supplier) {
                $selected = $choosed_acc && $choosed_acc == $supplier->id ? "selected" : "";
                $html_select .= "<option $selected value='" . $supplier->id . "'>" . $supplier->name . "</option>";
            }
            return response(['options' => $html_select]);
        } catch (Exception $e) {
            return response(['message' => __('words.back-support')], 400);
        }
    }

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {
            $supplier = Supplier::find($request['supplier_id']);
            $library_path = $this->libraryPath($supplier, 'supplier');
            $director = 'supplier_library/' . $library_path;
            $files = [];
            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);
                $fileName = $fileData['file_name'];
                $extension = Str::lower($fileData['extension']);
                $name = $fileData['name'];

                $files[$index] = $this->createSupplierLibrary($supplier->id, $fileName, $extension, $name);
            }
            $view = view('admin.suppliers.library', compact('files', 'library_path'))->render();
        } catch (Exception $e) {
            return response()->json(__('words.back-support', 400));
        }
        return response()->json(['view' => $view, 'message' => __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:suppliers,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {
            $supplier = Supplier::find($request['id']);
            if (!$supplier) {
                return response('supplier not valid', 400);
            }

            $library_path = $supplier->library_path;
            $files = $supplier->files;
            $view = view('admin.suppliers.library', compact('files', 'library_path'))->render();

        } catch (Exception $e) {
            return response('sorry, please try later', 400);
        }

        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:supplier_libraries,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {
            $file = SupplierLibrary::find($request['id']);
            $supplier = $file->supplier;
            $filePath = storage_path('app/public/supplier_library/' . $supplier->library_path . '/' . $file->file_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $file->delete();
        } catch (Exception $e) {
            return response('sorry, please try later', 200);
        }
        return response(['id' => $request['id']], 200);
    }

    public function getSubGroupsByMainIds(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'nullable|integer|exists:supplier_groups,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $mainGroups = SupplierGroup::query()->where('status',1)
                ->where('supplier_group_id',null);

            if ($request['group_id']) {
                $mainGroups->where('id', $request['group_id']);
            }

            $mainGroups = $mainGroups->select('id','name_' . $this->lang)->get();

            $order = $request->has('order') ? $request['order'] : 1;

            $subGroups = $this->getSubGroups($mainGroups, $order);

            $supplier = $request->has('supplier_id') ? Supplier::find($request['supplier_id']) : null;

            $view = view('admin.suppliers.ajax_sub_groups', compact('subGroups', 'supplier'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {
            return response()->json(__('sorry, please try later'), 400);
        }

    }

    private function checkMainGroupsIds(array $mainGroupsIds = null): bool
    {
        return !empty($mainGroupsIds);
    }

    function getMainSupplierGroupsAsTree(Collection $mainSupplierGroups)
    {
        $htmlCode = "<ul>";
        $startCounter = 1;
        $mainSupplierGroups
            ->each(function ($supplierGroup) use (&$htmlCode, &$startCounter) {
                $htmlCode .= view(self::view_path . '.tree-li-without-folder', [
                    'child' => $supplierGroup,
                    'counter' => $startCounter
                ])->render();
                $htmlCode .= '</li>';
                $startCounter++;
            });
        $htmlCode .= '</ul>';
        return $htmlCode;
    }

    function getSubSupplierGroupsAsTree(Collection $subSupplierGroups)
    {
        $htmlCode = "<ul>";
        $startCounter = 1;
        $subSupplierGroups
            ->each(function ($supplierGroup) use (&$htmlCode, &$startCounter) {
                $htmlCode .= view(self::view_path . '.tree-li-without-folder', [
                    'child' => $supplierGroup,
                    'counter' => $startCounter
                ])->render();
                $htmlCode .= '</li>';
                $startCounter++;
            });
        $htmlCode .= '</ul>';
        return $htmlCode;
    }
}
