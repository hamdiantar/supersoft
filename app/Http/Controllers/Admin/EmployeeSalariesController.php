<?php

namespace App\Http\Controllers\Admin;

use App\Filters\AdvanceFilter;
use Exception;
use App\Models\Locker;
use App\Models\Account;
use App\Models\Advance;
use App\Models\Advances;
use Illuminate\View\View;
use App\Models\EmployeeData;
use App\Models\ExpensesItem;
use App\Models\ExpensesType;
use Illuminate\Http\Request;
use App\Models\EmployeeSalary;
use App\Models\ExpensesReceipt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Filters\EmployeeSalaryFilter;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Employees\Salaries;
use App\Http\Requests\Admin\EmployeeSalary\EmployeeSalaryRequest;

class EmployeeSalariesController extends Controller
{
    /**
     * @var EmployeeSalaryFilter
     */
    protected $employeeSalaryFilter;

    public function __construct(EmployeeSalaryFilter $employeeSalaryFilter)
    {
        $this->employeeSalaryFilter = $employeeSalaryFilter;
//        $this->middleware('permission:view_employees_salaries');
//        $this->middleware('permission:create_employees_salaries',['only'=>['create','store']]);
//        $this->middleware('permission:update_employees_salaries',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_employees_salaries',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_employees_salaries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employees = EmployeeSalary::query();

        if ($request->hasAny((new EmployeeSalary())->getFillable())) {
            $employees = $this->employeeSalaryFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'employee_id',
                'salary' => 'salary',
                'date-from' => 'date_from',
                'date-to' => 'date_to',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $employees = $employees->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $employees = $employees->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $employees->where(function ($q) use ($key) {
                $q->where('salary' ,'like' ,"%$key%")
                ->orWhere('date' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Salaries($employees->with('employee') ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $employees = $employees->paginate($rows)->appends(request()->query());

        return view('admin.employees_salaries.index', ['employees' => $employees]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_employees_salaries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employees = EmployeeData::all();
        return view('admin.employees_salaries.create', compact('employees'));
    }

    public function store(EmployeeSalaryRequest $request)
    {
        if (!auth()->user()->can('create_employees_salaries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            DB::beginTransaction();
            $data = $request->all();

            $data['salary'] = $data['employee_data']['salary'];
            $data['insurances'] = $data['employee_data']['insurances'];
            $data['allowances'] = $data['employee_data']['allowances'];
            $data['advance_included'] = $request->has('advance_included') ? 1 : 0;

            $salary = EmployeeSalary::create($data);

            $url = app()->getLocale() .'/admin/employees_salaries?salary_id=' . $salary->id;

            if($request['save_type'] == 'save_and_print_receipt') {

                $url = app()->getLocale() .'/admin/employees_salaries?salary_id=' . $salary->id.'#show'.$salary->id;
            }

            if ($salary->deportation_method == 'bank') {
                $bank = Account::find($salary->account_id);
                if ($bank && $bank->balance >= $salary->employee_data['net_salary']) {
                    $bank->update(['balance' => $bank->balance - $salary->employee_data['net_salary']]);
                } else {
                    DB::rollback();
                    return redirect()->back()
                        ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                }
            } else {
                $locker = Locker::find($salary->locker_id);
                if ($locker && $locker->balance >= $salary->employee_data['net_salary']) {
                    $locker->update(['balance' => $locker->balance - $salary->employee_data['net_salary']]);
                } else {
                    DB::rollback();
                    return redirect()->back()
                        ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                }
            }
            $advance_data = [
                'deportation' => $salary->deportation_method === 'locker' ? 'safe' : 'bank',
                'date' => $salary->date,
                'amount' => $salary->employee_data['advances'],
                'employee_id' => $salary->employee_id,
                'branch_id' => $salary->branch_id,
                'locker_id' => $salary->locker_id,
                'account_id' => $salary->account_id,
                'cost_center_id' => $salary->cost_center_id,
                'salary_id' => $salary->id
            ];
            AdvancesController::restore_advance($advance_data);
            if ($salary->pay_type == "cash") {
                $salary->update([
                    'paid_amount' => $data['employee_data']['net_salary'],
                    'rest_amount' => 0
                ]);
                $this->createExpenseReceipt($request, $salary);
                DB::commit();
                return redirect()->to($url)
                    ->with(['message' => __('words.salary-created'), 'alert-type' => 'success']);
            }
            $salary->update([
                'rest_amount' => $data['employee_data']['net_salary']
            ]);
//            if ($salary->advance_included) {
//                Advances::create(
//                    [
//                        'branch_id' => $request->branch_id,
//                        'employee_data_id' => $salary->employee_id,
//                        'date' => $salary->date_to,
//                        'amount' => $salary->employee_data['advances'],
//                        'operation' => 'deposit',
//                        'deportation' => $request->deportation_method === 'locker' ? 'safe' : 'bank',
//                        'deportation_id' => $salary->deportation_id ?? null
//                    ]
//                );
//            }

            DB::commit();

            return redirect()->to($url)
                ->with(['message' => __('words.salary-created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            logger([
                'error' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ]);
            return redirect()->back()
                ->with(['message' => __('words.salary-not-created'), 'alert-type' => 'error']);
        }
    }

    public function edit(EmployeeSalary $employeeSalary)
    {
        if (!auth()->user()->can('update_employees_salaries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employees_salaries.edit', compact('employeeSalary'));
    }

    public function update(Request $request, EmployeeSalary $employeeSalary): RedirectResponse
    {
        if (!auth()->user()->can('update_employees_salaries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $url = app()->getLocale() .'/admin/employees_salaries?salary_id=' . $employeeSalary->id;

            if($request['save_type'] == 'save_and_print_receipt') {

                $url = app()->getLocale() .'/admin/employees_salaries?salary_id=' . $employeeSalary->id.'#show'.$employeeSalary->id;
            }

            DB::beginTransaction();
                if ($employeeSalary->deportation_method == 'bank') {
                    $bank = Account::find($employeeSalary->account_id);
                    if ($bank  && $bank->balance >= $employeeSalary->employee_data['net_salary']) {
                        $bank->update(['balance' => $bank->balance + $employeeSalary->employee_data['net_salary']]);
                    } else {
                        DB::rollback();
                        return redirect()->back()->with('error' ,__('words.can`t update salary'));
                    }
                } else {
                    $locker = Locker::find($employeeSalary->locker_id);
                    if ( $locker && $locker->balance >= $employeeSalary->employee_data['net_salary']) {
                        $locker->update(['balance' => $locker->balance + $employeeSalary->employee_data['net_salary']]);
                    } else {
                        DB::rollback();
                        return redirect()->back()->with('error' ,__('words.can`t update salary'));
                    }
                }
            $data = [
                'date' => $request->date,
                'employee_data' => $employeeSalary->employee_data
            ];
            $data['employee_data']['insurances'] = $request->employee_data['insurances'];
            $data['employee_data']['allowances'] = $request->employee_data['allowances'];
            $data['employee_data']['net_salary'] = $request->employee_data['net_salary'];
            $employeeSalary->update($data);
            $expenseReceipt = ExpensesReceipt::where('employee_salary_id', $employeeSalary->id)->first();
            if ($expenseReceipt)
                $expenseReceipt->update([
                    'cost' =>  $request->employee_data['net_salary']
                ]);
            DB::commit();

            return redirect()->to($url)
                ->with(['message' => __('words.salary-updated'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
//            logger([
//                'error' => $exception->getMessage(),
//                'line' => $exception->getLine(),
//                'trace' => $exception->getTrace(),
//            ]);
            return redirect()->back()
                ->with(['message' => __('words.salary-not-updated'), 'alert-type' => 'error']);
        }

    }

    public function destroy(EmployeeSalary $employeeSalary ,$for_internal_use = false)
    {
        if (!auth()->user()->can('delete_employees_salaries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            DB::beginTransaction();
            if ($employeeSalary->account_id) {
                $bank = Account::find($employeeSalary->account_id);
                if ($bank) {
                    $bank->update(['balance' => $bank->balance + $employeeSalary->employee_data['net_salary']]);
                } else {
                    DB::rollback();
                    if ($for_internal_use) return ['status' => false ,'message' => __('words.account-money-issue')];
                    return redirect()->back()
                        ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                }
            } else {
                $locker = Locker::find($employeeSalary->locker_id);
                if ($locker) {
                    $locker->update(['balance' => $locker->balance + $employeeSalary->employee_data['net_salary']]);
                } else {
                    DB::rollback();
                    if ($for_internal_use) return ['status' => false ,'message' => __('words.locker-money-issue')];
                    return redirect()->back()
                        ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                }
            }
            $employeeSalary->delete();
            // Ahmed Hesham update this to meet observer needs [to delete daily restrictions its work only on each model ,not bulk]
            $receipts = ExpensesReceipt::where('employee_salary_id', $employeeSalary->id)->get();
            foreach($receipts as $receipt) $receipt->delete();
            $advance = $employeeSalary->advance_included ? Advances::where('salary_id' ,$employeeSalary->id)->first() : NULL;
            if ($advance) {
                $advance_delete = (new AdvancesController(new AdvanceFilter))->destroy($advance ,true);
                if ($advance_delete['status'] == false) {
                    DB::rollback();
                    if ($for_internal_use) return $advance_delete;
                    return redirect()->back()->with(['message' => $advance_delete['message'], 'alert-type' => 'error']);
                }
            }
            DB::commit();
            if ($for_internal_use) return ['status' => true];
        return redirect()->to('admin/employees_salaries')
            ->with(['message' => __('words.salary-deleted'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            DB::rollback();
            if ($for_internal_use) return ['status' => false ,'message' => __('words.something-wrong')];
            return redirect()->back()
                ->with(['message' => __('words.something-wrong'), 'alert-type' => 'success']);
        }
    }

    function employeeData()
    {
        $data = request()->all();
        unset($data['_token']);
        if (!isset($data['employee_id']) || !isset($data['date_from']) || !isset($data['date_to']) ) {
            return response(['message' => __('words.salary-needs-data')], 400);
        }

        $from_month = (new \Carbon\Carbon($data['date_from']))->month;
        $to_month = (new \Carbon\Carbon($data['date_to']))->month;
        if ($from_month != $to_month)
            return response(['message' => __('words.same-month')], 400);
        try {
            $employee = EmployeeData::with([
                'employeeSetting',
                'advances' => function ($q) use ($data) {
                    $q->where('date', '>=', $data['date_from'])->where('date', '<=', $data['date_to'])
                        ->select(DB::raw('SUM(amount) as amount'), 'employee_data_id', 'operation')->groupBy('operation', 'employee_data_id');
                },
                'delays' => function ($q) use ($data) {
                    $q->where('date', '>=', $data['date_from'])->where('date', '<=', $data['date_to'])
                        ->select(
                            DB::raw('SUM(number_of_hours) as hours_count'),
                            DB::raw('SUM(number_of_minutes) as minutes_count'),
                            'employee_data_id', 'type'
                        )->groupBy('type', 'employee_data_id');
                },
                'rewards' => function ($q) use ($data) {
                    $q->where('date', '>=', $data['date_from'])->where('date', '<=', $data['date_to'])
                        ->select(DB::raw('SUM(cost) as amount'), 'type', 'employee_data_id')->groupBy('type', 'employee_data_id');
                },
                'absences' => function ($q) use ($data) {
                    $q->where('absence_type' ,'absence')->where('date' ,'>=' ,$data['date_from'])
                    ->where('date' ,'<=' ,$data['date_to'])->select(DB::raw("SUM(absence_days) AS absences") ,'employee_id')->groupBy('employee_id');
                }
            ])->findOrFail($data['employee_id']);
            $weekly_official_vacations = $this->getVocationsDayInWeek($employee);
            $employeeSalary = EmployeeSalary::where($data)->first();
            $dateRange = [$data['date_from'], $data['date_to']];

            $code = view("admin.employees_salaries.employee-template", compact('employee', 'employeeSalary', 'dateRange', 'weekly_official_vacations'))->render();
            return response(['code' => $code], 200);
        } catch (Exception $e) {
            logger(['error' => $e->getMessage()]);
            return response(['message' => __('words.No Data Available')], 400);
        }
    }

    function getVocationsDayInWeek(EmployeeData $employeeData): array
    {
        $days = [];
        if ($employeeData->employeeSetting->saturday) {
            $days[] = [
                'sat' => 'saturday'
            ];
        }
        if ($employeeData->employeeSetting->sunday) {
            $days[] = [
                'sun' => 'sunday'
            ];
        }
        if ($employeeData->employeeSetting->monday) {
            $days[] = [
                'mon' => 'monday'
            ];
        }
        if ($employeeData->employeeSetting->tuesday) {
            $days[] = [
                'tue' => 'tuesday'
            ];
        }
        if ($employeeData->employeeSetting->wednesday) {
            $days[] = [
                'wed' => 'wednesday'
            ];
        }
        if ($employeeData->employeeSetting->thursday) {
            $days[] = [
                'thu' => 'thursday'
            ];
        }
        if ($employeeData->employeeSetting->friday) {
            $days[] = [
                'fri' => 'friday'
            ];
        }
        return $days;
    }

    public function createExpenseReceipt(Request $request, EmployeeSalary $employeeSalary)
    {
        $expenseReceipt = ExpensesReceipt::create([
            'date' => now(),
            'time' => now(),
            'receiver' => $this->getEmployeeName($request->employee_id),
            'for' => 'salaries',
            'cost' => $employeeSalary->employee_data['net_salary'],
            'deportation' => $request->deportation_method === 'locker' ? 'safe' : 'bank',
            'expense_type_id' => $this->getExpenseTypeID(),
            'expense_item_id' => $this->getExpenseItemID(),
            'branch_id' => $request->branch_id,
            'locker_id' => $request->has('locker_id') ? $request->locker_id : null,
            'account_id' => $request->has('account_id') ? $request->account_id : null,
            'employee_salary_id' => $employeeSalary->id,
            'cost_center_id' => $request->cost_center_id
        ]);
//        if ($expenseReceipt && $request->has('locker_id')) {
//            $this->addBalanceInLocker([
//                'locker_id' => $request->locker_id,
//                'cost' => $employeeSalary->salary,
//            ]);
//        }
//
//        if ($expenseReceipt && $request->has('account_id')) {
//            $this->addBalanceInBankAccount([
//                'account_id' => $request->account_id,
//                'cost' => $employeeSalary->salary,
//            ]);
//        }
    }

    function getEmployeeName(int $employeeId): string
    {
        $emp = EmployeeData::find($employeeId);
        return $emp->name;
    }

    function getExpenseItemID(): int
    {
        $item = ExpensesItem::where('item_en', 'salaries Payments')->first();
        return $item->id;
    }

    function getExpenseTypeID(): int
    {
        $type = ExpensesType::where('type_en', 'salaries')->first();
        return $type->id;
    }

    public function addBalanceInLocker(array $data)
    {
        $locker = Locker::find($data['locker_id']);
        $locker->update([
            'balance' => ($locker->balance - $data['cost'])
        ]);
    }

    public function addBalanceInBankAccount(array $data)
    {
        $account = Account::find($data['account_id']);
        $account->update([
            'balance' => ($account->balance - $data['cost'])
        ]);
    }

    function deleteSelected() {

        if (!auth()->user()->can('delete_employees_salaries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!request()->has('ids'))
            return redirect()->back()->with([
                'message' => __("words.select-one-least"),
                'alert-type' => "error"
            ]);
        foreach(request('ids') as $id) {
            $salary = EmployeeSalary::find($id);
            if ($salary) {
                $destroy_result = $this->destroy($salary ,true);
                if ($destroy_result['status'] == false) {
                    return redirect()->back()->with([
                        'message' => $destroy_result['message'],
                        'alert-type' => "error"
                    ]);
                }
            }
        }
        return redirect()->back()->with([
            'message' => __("words.selected-row-deleted"),
            'alert-type' => "success"
        ]);
    }

    static function employee_card_work($employee ,$date_range) {
        $card_work = 0;

        if ($employee && $employee->employeeSetting && $employee->employeeSetting->service_status) {
            $employee_services = \App\Models\CardInvoice
                ::join('card_invoice_types' ,'card_invoices.id' ,'=' ,'card_invoice_types.card_invoice_id')
                ->join('card_invoice_type_items' ,'card_invoice_types.id' ,'=' ,'card_invoice_type_items.card_invoice_type_id')
                ->join('card_invoice_type_items_employee_data' ,'card_invoice_type_items.id' ,'=' ,'card_invoice_type_items_employee_data.item_id')
                ->where('card_invoice_type_items_employee_data.employee_id' ,$employee->id)
                ->whereBetween('card_invoices.date' ,$date_range)
                ->select(
                    'card_invoice_type_items_employee_data.percent as percent',
                    'card_invoice_type_items.sub_total as total'
                )
                ->get();
            $total = 0;
            foreach($employee_services as $service) {
                $total += (float)$service->total;
            }
            $card_work = ($employee->employeeSetting->card_work_percent / 100) * $total;
            return $card_work;
        }

        return $card_work;
    }
}
