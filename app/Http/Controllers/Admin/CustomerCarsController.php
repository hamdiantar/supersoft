<?php

namespace App\Http\Controllers\Admin;

use App\Models\BankAccount;
use App\Models\Car;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\CustomerCategory;
use App\Filters\CustomerCarFilter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\CustomersCar;
use App\Http\Requests\Admin\CustomerCar\CustomerCarRequest;
use App\Http\Requests\Admin\CustomerCar\UpdateCustomerRequest;

class CustomerCarsController extends Controller
{
    /**
     * @var CustomerCarFilter
     */
    protected $customerCarFilter;

    public function __construct(CustomerCarFilter $customerCarFilter)
    {
        $this->customerCarFilter = $customerCarFilter;
//        $this->middleware('permission:view_customers');
//        $this->middleware('permission:create_customers',['only'=>['create','store']]);
//        $this->middleware('permission:update_customers',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_customers',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_customers')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $customers = Customer::query();
        $customers->globalCheck($request);
        $customersSearch = Customer::query();
        $responsiples = Customer::where('responsible' ,'!=' ,'')->whereNotNull('responsible')->get();
        $cars = Car::query();
        $customerCategories = CustomerCategory::query();
        if(false == authIsSuperAdmin()) {
            $customersSearch->where('branch_id', auth()->user()->branch_id);
            $cars->whereIn('customer_id', $customers->pluck('id')->toArray());
            $customerCategories->where('branch_id', auth()->user()->branch_id);
        }

        if ($request->hasAny((new Customer())->getFillable()) || $request->hasAny((new  Car())->getFillable())) {
            $customers = $this->customerCarFilter->filter($request);
        }

        $customer_id = NULL;
        if ($request->has('barcode') && $request->barcode != '') {
            try {
                $customer_id = Car::where('barcode' ,$request->barcode)->first()->customer_id;
            } catch (\Exception $e) {
                $customer_id = NULL;
            }
        }
        $rows = $request->has('rows') ? $request->rows : 10;
        $customers->when($customer_id ,function ($q) use ($customer_id) {
            $q->where('id' ,$customer_id);
        });
        if ($request->has('key')) {
            $key = $request->key;
            $customers->where(function ($q) use ($key) {
                $q->where('name_en' ,'like' ,"%$key%")
                ->orWhere('name_ar' ,'like' ,"%$key%");
            });
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $sort_fields = [
                'name' => 'name_'.$lang,
                'customer-type' => 'type',
                'customer-category' => 'customer_category_id',
                'status' => 'status',
                'cars-number' => 'cars_number',
                'balance-for' => 'balance_for',
                'balance-to' => 'balance_to',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $customers = $customers->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $customers = $customers->orderBy('id', 'DESC');
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            $customers = $customers->with(['customerCategory' ,'cars']);
            return (new ExportPrinterFactory(new CustomersCar($customers ,$visible_columns) ,$request->invoker))();
        }

        return view('admin.customers.index', [
            'cars' => $cars->get(),
            'customersSearch' => $customersSearch->get(),
            'customers' => $customers->paginate($rows)->appends(request()->query()),
            'customerCategories' => $customerCategories->get(),
            'responsiples' => $customersSearch->where('responsible' ,'!=' ,'')->whereNotNull('responsible')->get()
        ]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_customers')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $customerCategories = CustomerCategory::query();
        if(false == authIsSuperAdmin()) {
            $customerCategories->where('branch_id', auth()->user()->branch_id);
        }
        return view('admin.customers.create', ['customerCategories' => $customerCategories->get()]);
    }

    public function store(CustomerCarRequest $request)
    {
        if (!auth()->user()->can('create_customers')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();

        if ($request->has('can_edit')) {

            $data['can_edit'] = 1;
        }

        if(false == authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        if ($data['customer_category_id'] == '') {
            $data['customer_category_id'] = null;
        }

        $data['password'] = Hash::make($request['password']);

        $customer = Customer::create($data);

        if ($request['contacts']) {
            foreach ($request['contacts'] as $contact) {
                CustomerContact::create([
                    'customer_id' => $customer->id,
                    'phone_1' => $contact['phone_1'],
                    'phone_2' => $contact['phone_2'],
                    'address' => $contact['address'],
                    'name'    => $contact['name'],
                ]);
            }
        }

        if ($request['bankAccount']) {
            foreach ($request['bankAccount'] as $bankAccount) {
                BankAccount::create([
                    'customer_id' => $customer->id,
                    'bank_name' => $bankAccount['bank_name'],
                    'account_name' => $bankAccount['account_name'],
                    'branch' => $bankAccount['branch'],
                    'account_number' => $bankAccount['account_number'],
                    'iban' => $bankAccount['iban'],
                    'swift_code' => $bankAccount['swift_code'],
                ]);
            }
        }

        return redirect()->route('admin:customers.show', ['id' => $customer->id])
            ->with(['message' => __('words.customer-created'), 'alert-type' => 'success']);
    }

    public function edit(int $id)
    {
        if (!auth()->user()->can('update_customers')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $customerCategories = CustomerCategory::query();

        if(false == authIsSuperAdmin()) {
            $customerCategories->where('branch_id', auth()->user()->branch_id);
        }

        $customer = Customer::find($id);

        $carsCount = $customer->cars()->count();

        return view('admin.customers.edit', [
            'customer' => $customer,
            'carsCount' => $carsCount,
            'customerCategories' => $customerCategories->get(),
        ]);
    }

    public function update(UpdateCustomerRequest $request, int $id)
    {
        if (!auth()->user()->can('update_customers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $data = $request->all();
            if(false == authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $data['can_edit'] = 0;
            if ($request->has('can_edit')) {
                $data['can_edit'] = 1;
            }

            $customer = Customer::find($id);
            if ($request->has('password')) {
                $data['password'] = Hash::make($request['password']);
            }

            $customer->update($data);
            if ($request['contacts']) {
                foreach ($request['contacts'] as $contact) {
                    CustomerContact::create([
                        'customer_id' => $customer->id,
                        'phone_1' => $contact['phone_1'],
                        'phone_2' => $contact['phone_2'],
                        'address' => $contact['address'],
                        'name' => $contact['name'],
                    ]);
                }
            }

            if ($request['contactsUpdate']) {
                foreach ($request['contactsUpdate'] as $contactData) {
                    $contact = CustomerContact::find($contactData['contactId']);
                    if ($contact) {
                        $contact->update($contactData);
                    }
                }
            }

            if ($request['bankAccount']) {
                foreach ($request['bankAccount'] as $bankAccount) {
                    BankAccount::create([
                        'customer_id' => $customer->id,
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

            return redirect()->to('admin/customers')
                ->with(['message' => __('words.customer-updated'), 'alert-type' => 'success']);

        } catch(\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('words.customer-not-updated'), 'alert-type' => 'error']);
        }

    }

    public function destroy(Customer $customer)
    {
        if (!auth()->user()->can('delete_customers')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $customer->cars()->delete();
        $customer->delete();
        return redirect()->to('admin/customers')
            ->with(['message' => __('words.customer-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_customers')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $customers = Customer::whereIn('id', $request->ids)->get();
            foreach ($customers as $customer) {
                $customer->cars()->delete();
                $customer->delete();
            }
            return redirect()->to('admin/customers')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/customers')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }

    public function addCar(): View
    {
        return view('admin.customers.parts.add_car');
    }

    public function getCustomerCategories(Request $request)
    {
        $customers = CustomerCategory::where('branch_id', $request->id)->get();
        $html = '<option value="">' . __('Select Customer Category') . '</option>';
        foreach ($customers as $customer) {
            $html .= '<option value="' . $customer->id . '">' . $customer->name . '</option>';
        }
        return response()->json(['customerCategory' => $html]);
    }

    function getCustomers() {
        try {
            $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
            $choosed_acc = isset($_GET['choosed_acc']) && $_GET['choosed_acc'] != '' ? $_GET['choosed_acc'] : NULL;
            $customers = Customer::when($branch_id ,function ($q) use ($branch_id) {
                    $q->where('branch_id' ,$branch_id);
                })
                ->get();
            $html_select = "<option value=''>". __('Select One') ."</option>";
            foreach($customers as $customer) {
                $selected = $choosed_acc && $choosed_acc == $customer->id ? "selected" : "";
                $html_select .= "<option $selected value='". $customer->id ."'>". $customer->name ."</option>";
            }
            return response(['options' => $html_select]);
        } catch (\Exception $e) {
            return response(['message' => __('words.back-support')] ,400);
        }
    }
}
