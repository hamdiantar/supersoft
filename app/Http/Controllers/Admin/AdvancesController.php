<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Locker;
use App\Models\Account;
use App\Models\Advances;
use Illuminate\View\View;
use App\Models\RevenueItem;
use App\Models\RevenueType;
use App\Models\EmployeeData;
use App\Models\ExpensesItem;
use App\Models\ExpensesType;
use Illuminate\Http\Request;
use App\Filters\AdvanceFilter;
use App\Models\RevenueReceipt;
use App\Models\ExpensesReceipt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DataExportCore\Employees\Advances as EmployeesAdvances;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\Advance\AdvanceRequest;

class AdvancesController extends Controller
{
    /**
     * @var AdvanceFilter
     */
    protected $advanceFilter;

    public function __construct(AdvanceFilter $advanceFilter)
    {
        $this->advanceFilter = $advanceFilter;
        $this->middleware('permission:view_parts');
        $this->middleware('permission:create_parts',['only'=>['create','store']]);
        $this->middleware('permission:update_parts',['only'=>['edit','update']]);
        $this->middleware('permission:delete_parts',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        $advances = Advances::query();

        if ($request->hasAny((new Advances())->getFillable())) {
            $advances = $this->advanceFilter->filter($request);
        }
        if (request()->has('sort_by') && request('sort_by') != '') {
            $sort_by = request('sort_by');
            $sort_method = request()->has('sort_method') ? request('sort_method') :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'employee_data_id',
                'type' => 'operation',
                'cost' => 'amount',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $advances = $advances->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $advances = $advances->orderBy('id', 'DESC');
        }
        if (request()->has('key')) {
            $key = request('key');
            $advances->where(function ($q) use ($key) {
                $q->where('amount' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%");
            });
        }
        if (request()->has('invoker') && in_array(request('invoker') ,['print' ,'excel'])) {
            $visible_columns = request()->has('visible_columns') ? request('visible_columns') : [];
            return (new ExportPrinterFactory(new EmployeesAdvances($advances->with('employee') ,$visible_columns), request('invoker')))();
        }
        $rows = request()->has('rows') ? request('rows') : 10;
        $advances = $advances->paginate($rows)->appends(request()->query());

        return view('admin.advances.index', ['advances' => $advances]);
    }

    public function create(): View
    {
        return view('admin.advances.create');
    }

    public function store(AdvanceRequest $request)
    {
        try {

            DB::beginTransaction();

            $advance = Advances::create($request->all());


            if ($request->operation == 'withdrawal') {
                if ($request->deportation == 'bank') {
                    $bank = Account::find($request->account_id);
                    if ($bank && $bank->balance >= $request->amount) {
                        $bank->update(['balance' => $bank->balance - $request->amount]);

                    } else {
                        DB::rollback();
                        return redirect()->back()
                            ->with(['message' => __('words.cant-withdraw-account'), 'alert-type' => 'error']);                    }

                } else {
                    $locker = Locker::find($request->locker_id);
                    if ($locker && $locker->balance >= $request->amount) {
                        $locker->update(['balance' => $locker->balance - $request->amount]);
                    } else {
                        DB::rollback();
                        return redirect()->back()
                            ->with(['message' => __('words.cant-withdraw-locker'), 'alert-type' => 'error']);
                    }
                }
            } else {
                if ($request->deportation == 'bank') {
                    $bank = Account::find($request->account_id);
                    if ($bank) {
                        $bank->update(['balance' => $bank->balance + $request->amount]);
                    } else {
                        DB::rollback();
                        return redirect()->back()
                            ->with(['message' => __('words.cant-deposit-account'), 'alert-type' => 'error']);                    }
                } else {
                    $locker = Locker::find($request->locker_id);
                    if ($locker) {
                        $locker->update(['balance' => $locker->balance + $request->amount]);
                    } else {
                        DB::rollback();
                        return redirect()->back()
                            ->with(['message' => __('words.cant-deposit-locker'), 'alert-type' => 'error']);
                    }
                }
            }
            if (!$this->employeeCanAdvance($request->employee_data_id, $request->date, $request->operation, null)) {
                DB::rollback();
                return redirect()->back()
                    ->with(['message' => __('words.employee-advances-reach'), 'alert-type' => 'error']);
            }
            if ($request->operation === "withdrawal") {
                //create new Expenses
              $this->createExpenseReceipt($request, $advance);
            }

            if ($request->operation === "deposit") {
                //create new Revenue
                $this->createRevenueReceipt($request, $advance);
            }
            DB::commit();

            $url = 'admin/advances';

            if($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/advances?invoice='.$advance->id.'#showAdvance';
            }

            return redirect()->to($url)
                ->with(['message' => __('words.advance-created'), 'alert-type' => 'success']);

        } catch (Exception $exception) {
            logger([
                'error' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ]);
            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }
    }

    public function edit(Advances $advances): View
    {
        $emp = EmployeeData::find($advances->employee_data_id);
        $branch = Branch::find($advances->branch_id);
        $branchName = $branch->name;
        $empName = $branch->name;
        $totalWith = $totalDep = 0;
        foreach ($emp->advances as $adv) {
            if ($adv->operation === __('withdrawal')) $totalWith += $adv->amount;
            if ($adv->operation === __('deposit')) $totalDep += $adv->amount;
        }
        $total = $totalWith - $totalDep;
        if ($total < 0) $total = 0;
        $max_advance = $emp->employeeSetting->max_advance;
        $restFromMaxAdvance = $max_advance - $total;
        $deporationName = $this->getDeporationName($advances);

        return view('admin.advances.edit', compact('advances', 'total',
            'empName', 'branchName', 'max_advance', 'restFromMaxAdvance', 'deporationName'));
    }

    public function update(Request $request, Advances $advances): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $advance = $advances;
            if ($advance->operation == __('withdrawal')) {
                $diff = $request->amount - $advance->amount;
                if ($diff > 0) {
                    if ($advance->deportation == 'bank') {
                        $bank = Account::find($advance->account_id);
                        if ($bank && $bank->balance >= $diff) {
                            $bank->update(['balance' => $bank->balance - $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                        }
                    } else {
                        $locker = Locker::find($advance->locker_id);
                        if ($locker && $locker->balance >= $diff) {
                            $locker->update(['balance' => $locker->balance - $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                        }
                    }
                }
                else {
                    $diff = abs($diff);
                    if ($advance->deportation == 'bank') {
                        $bank = Account::find($advance->account_id);
                        if ($bank) {
                            $bank->update(['balance' => $bank->balance + $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                        }
                    } else {
                        $locker = Locker::find($advance->locker_id);
                        if ($locker) {
                            $locker->update(['balance' => $locker->balance + $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                        }
                    }
                }
            }
            else {
                $diff = $request->amount - $advance->amount;
                if ($diff < 0) {
                    $diff = abs($diff);
                    if ($advance->deportation == 'bank') {
                        $bank = Account::find($advance->account_id);
                        if ($bank && $bank->balance >= $diff) {
                            $bank->update(['balance' => $bank->balance - $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                        }
                    } else {
                        $locker = Locker::find($advance->locker_id);
                        if ($locker && $locker->balance >= $diff) {
                            $locker->update(['balance' => $locker->balance - $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                        }
                    }
                } else {
                    if ($advance->deportation == 'bank') {
                        $bank = Account::find($advance->account_id);
                        if ($bank) {
                            $bank->update(['balance' => $bank->balance + $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                        }
                    } else {
                        $locker = Locker::find($advance->locker_id);
                        if ($locker) {
                            $locker->update(['balance' => $locker->balance + $diff]);
                        } else {
                            DB::rollback();
                            return redirect()->back()
                                ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                        }
                    }
                }
            }
            $data = [
                'notes' => $request->notes,
                'date' => $request->date,
                'amount' => $request->amount,
            ];
            $advance->update($data);

            if (!$this->employeeCanAdvance($advance->employee_data_id ,$request->date ,$advance->operation ,$advance->id) ) {
                DB::rollback();
                return redirect()->back()
                    ->with(['message' => __('words.employee-advances-reach'), 'alert-type' => 'error']);
            }
            if ($advance->operation === __("withdrawal")) {
                $expense = ExpensesReceipt::where('advance_id', $advance->id)->first();
                $expense->update([
                    'cost' => $data['amount'],
                    'cost_center_id' => $request->cost_center_id
                ]);
            }

            if ($advance->operation === __("deposit")) {
                $revenue = RevenueReceipt::where('advance_id', $advance->id)->first();
                $revenue->update([
                    'cost' => $data['amount'],
                    'cost_center_id' => $request->cost_center_id
                ]);
            }
            DB::commit();

            $url = 'admin/advances';

            if($request['save_type'] == 'save_and_print_receipt') {

                $url = 'admin/advances?invoice='.$advance->id.'#showAdvance';
            }

            return redirect()->to($url)
                ->with(['message' => __('words.advance-updated'), 'alert-type' => 'success']);

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with(['message' => __('words.advance-not-updated'), 'alert-type' => 'error']);
        }
    }

    public function destroy(Advances $advances ,$for_internal_use = false)
    {
        DB::beginTransaction();
        if ($advances->operation == __('deposit')) {
            if ($advances->deportation == 'bank') {
                $bank = Account::find($advances->account_id);
                if ($bank && $bank->balance >= $advances->amount) {
                    $bank->update(['balance' => $bank->balance - $advances->amount]);
                } else {
                    DB::rollback();
                    if ($for_internal_use) return ['status' => false ,'message' => __('words.account-money-issue')];
                    return redirect()->back()
                        ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                }
            } else {
                $locker = Locker::find($advances->locker_id);
                if ($locker && $locker->balance >= $advances->amount) {
                    $locker->update(['balance' => $locker->balance - $advances->amount]);
                } else {
                    DB::rollback();
                    if ($for_internal_use) return ['status' => false ,'message' => __('words.locker-money-issue')];
                    return redirect()->back()
                        ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                }
            }
        } else {
            if ($advances->deportation == 'bank') {
                $bank = Account::find($advances->account_id);
                if ($bank) {
                    $bank->update(['balance' => $bank->balance + $advances->amount]);
                } else {
                    DB::rollback();
                    if ($for_internal_use) return ['status' => false ,'message' => __('words.account-money-issue')];
                    return redirect()->back()
                        ->with(['message' => __('words.account-money-issue'), 'alert-type' => 'error']);
                }
            } else {
                $locker = Locker::find($advances->locker_id);
                if ($locker) {
                    $locker->update(['balance' => $locker->balance + $advances->amount]);
                } else {
                    DB::rollback();
                    if ($for_internal_use) return ['status' => false ,'message' => __('words.locker-money-issue')];
                    return redirect()->back()
                        ->with(['message' => __('words.locker-money-issue'), 'alert-type' => 'error']);
                }
            }
        }
        $advances->delete();
        $rec = $advances->getMyReceipt();
        if ($rec) $rec->delete();
        DB::commit();
        if ($for_internal_use) return ['status' => true];
        return redirect()->to('admin/advances')
            ->with(['message' => __('words.advance-deleted'), 'alert-type' => 'success']);
    }

    private function employeeCanAdvance($employee_id, $date, $operation, $advance_id)
    {
        if ($operation !== __('withdrawal') && $operation !== 'withdrawal') {
            return [true ,'p1'];
        }

        $employee = EmployeeData::find($employee_id);
        if (!$employee) {
            return [false ,'p2'];
        }

        $date = new Carbon($date);
        $range = [$date->startOfMonth()->toDateString(), $date->endOfMonth()->toDateString()];
        $withdraw = Advances::where('employee_data_id', $employee_id)
            ->where('operation', 'withdrawal')
            ->when($advance_id, function ($q) use ($advance_id) {
                // $q->where('id', '!=', $advance_id);
            })
            ->whereBetween('date', $range)
            ->sum('amount');
        $deposit = Advances
            ::where('employee_data_id', $employee_id)
            ->where('operation', 'deposit')
            ->whereBetween('date', $range)
            ->sum('amount');
        if ($deposit > $withdraw) {
            return [true ,'p3'];
        }
        $diff = $withdraw - $deposit;
        return $diff <= optional($employee->employeeSetting)->max_advance;
    }

    function getEmployeeName(int $employeeId): string
    {
        $emp = EmployeeData::find($employeeId);
        return $emp->name;
    }

    function getExpenseItemID(): int
    {
        $item = ExpensesItem::where('item_en', 'advances Payments')->first();
        return $item->id;
    }

    function getExpenseTypeID(): int
    {
        $type = ExpensesType::where('type_en', 'advances')->first();
        return $type->id;
    }

    function getRevenueItemID(): int
    {
        $item = RevenueItem::where('item_en', 'advances Payments')->first();
        return $item->id;
    }

    function getRevenueTypeID(): int
    {
        $type = RevenueType::where('type_en', 'advances')->first();
        return $type->id;
    }

    public function addBalanceInLocker(array $data)
    {
        $locker = Locker::find($data['locker_id']);
        if (isset($data['revenue'])) {
            $locker->update([
                'balance' => ($locker->balance + $data['cost'])
            ]);
        }
        if (isset($data['expense'])) {
            $locker->update([
                'balance' => ($locker->balance - $data['cost'])
            ]);
        }

    }

    public function addBalanceInBankAccount(array $data)
    {
        $account = Account::find($data['account_id']);
        if (isset($data['revenue'])) {
            $account->update([
                'balance' => ($account->balance + $data['cost'])
            ]);
        }
        if (isset($data['expense'])) {
            $account->update([
                'balance' => ($account->balance - $data['cost'])
            ]);
        }

    }

    public function createExpenseReceipt(Request $request, Advances $advances)
    {
        $number = time();
        $last_number = ExpensesReceipt::orderBy('id' ,'desc')->first();
        if ($last_number && $last_number->number) {
            $number = $last_number->number + 1;
        } else if ($last_number) {
            $number = $last_number->id + 1;
        }
        $expenseReceipt = ExpensesReceipt::create([
            'date' => now(),
            'time' => now(),
            'receiver' => $this->getEmployeeName($request->employee_data_id),
            'for' => 'advance',
            'cost' => $request->amount,
            'deportation' => $request->deportation,
            'expense_type_id' => $this->getExpenseTypeID(),
            'expense_item_id' => $this->getExpenseItemID(),
            'branch_id' => $request->branch_id,
            'locker_id' => $request->has('locker_id') ? $request->locker_id : null,
            'account_id' => $request->has('account_id') ? $request->account_id : null,
            'advance_id' => $advances->id,
            'number' => $number,
            'cost_center_id' => $request->cost_center_id
        ]);
//        if ($expenseReceipt && $request->has('locker_id')) {
//            $this->addBalanceInLocker([
//                'locker_id' => $request->locker_id,
//                'cost' => $request->amount,
//                'expense' => true,
//            ]);
//        }
//
//        if ($expenseReceipt && $request->has('account_id')) {
//            $this->addBalanceInBankAccount([
//                'account_id' => $request->account_id,
//                'cost' => $request->amount,
//                'expense' => true,
//            ]);
//        }
    }

    public function createRevenueReceipt(Request $request, Advances $advances)
    {
        $number = time();
        $last_number = RevenueReceipt::orderBy('id' ,'desc')->first();
        if ($last_number && $last_number->number) {
            $number = $last_number->number + 1;
        } else if ($last_number) {
            $number = $last_number->id + 1;
        }
        $revenueReceipt = RevenueReceipt::create([
            'date' => now(),
            'time' => now(),
            'receiver' => $this->getEmployeeName($request->employee_data_id),
            'for' => 'advance',
            'cost' => $request->amount,
            'deportation' => $request->deportation,
            'revenue_type_id' => $this->getRevenueTypeID(),
            'revenue_item_id' => $this->getRevenueItemID(),
            'branch_id' => $request->branch_id,
            'locker_id' => $request->has('locker_id') ? $request->locker_id : null,
            'account_id' => $request->has('account_id') ? $request->account_id : null,
            'advance_id' => $advances->id,
            'number' => $number,
            'cost_center_id' => $request->cost_center_id
        ]);
//        if ($revenueReceipt && $request->has('locker_id')) {
//            $this->addBalanceInLocker([
//                'locker_id' => $request->locker_id,
//                'cost' => $request->amount,
//                'revenue' => true,
//            ]);
//        }
//
//        if ($revenueReceipt && $request->has('account_id')) {
//            $this->addBalanceInBankAccount([
//                'account_id' => $request->account_id,
//                'cost' => $request->amount,
//                'revenue' => true,
//            ]);
//        }
    }

    public function checkMaxAdvanceForEmployee(int $employeeId, int $amount)
    {
        $emp = EmployeeData::find($employeeId);
        return optional($emp->employeeSetting)->max_advance >= $amount;
    }

    public function checkIfEmployeeHasAdvance(int $employeeId): bool
    {
        $emp = EmployeeData::find($employeeId);
        return $emp->advances->count();
    }

    public function countTotalAmountEmployee(int $employeeId)
    {
        $emp = EmployeeData::find($employeeId);
        return $emp->advances->sum('amount');
    }

    public function getDeporationName(Advances $advances): string
    {
        if ($advances->operation === __('withdrawal')) {
            $expense = ExpensesReceipt::where('advance_id', $advances->id)->first();
            if ($expense->locker_id) {
                $locker = Locker::withTrashed()->find($expense->locker_id);
                return $locker->name;
            }
            if ($expense->account_id) {
                $account = Account::withTrashed()->find($expense->account_id);
                return $account->name;
            }
        }
        if ($advances->operation === __('deposit')) {
            $revenue = RevenueReceipt::where('advance_id', $advances->id)->first();
            if ($revenue->locker_id) {
                $locker = Locker::withTrashed()->find($revenue->locker_id);
                return $locker->name;
            }
            if ($revenue->account_id) {
                $account = Account::withTrashed()->find($revenue->account_id);
                return $account->name;
            }
        }
    }

    public function checkMaxAdvance(Request $request)
    {
        $emp = EmployeeData::find($request->empId);
        $totalWith = $totalDep = 0;
        foreach ($emp->advances as $adv) {
            if ($adv->operation == __('withdrawal')) {
                $totalWith += $adv->amount;
            }
            if ($adv->operation == __('deposit')) {
                $totalDep += $adv->amount;
            }
        }
        $total = $totalWith - $totalDep;
        if ($total < 0) {
            $total = 0;
        }
        $max_advance = $emp->employeeSetting->max_advance;
        $restFromMaxAdvance = $max_advance - $total;
        return response()->json(
            [
                'result' =>  $restFromMaxAdvance >= $request->amount
            ]
        );
    }

    public function show(Request $request)
    {
        $advance = Advances::find($request->advance_id);
        if ($advance->operation == __("withdrawal")) {
            $expense = ExpensesReceipt::where('advance_id', $advance->id)->first();
            $printExpense = view('admin.advances.printExpense', compact('expense'))->render();
            return response()->json(['print' => $printExpense]);
        }

        if ($advance->operation == __("deposit")) {
            $revenue = RevenueReceipt::where('advance_id', $advance->id)->first();
            $printrevenue = view('admin.advances.printRevenue', compact('revenue'))->render();
            return response()->json(['print' => $printrevenue]);
        }
    }

    function deleteSelected() {
        if (!request()->has('ids'))
            return redirect()->back()->with([
                'message' => __("words.select-one-least"),
                'alert-type' => "error"
            ]);
        foreach(request('ids') as $id) {
            $advance = Advances::find($id);
            if ($advance) {
                $destroy_result = $this->destroy($advance ,true);
                if ($destroy_result['status'] == false)
                return redirect()->back()->with([
                    'message' => $destroy_result['message'],
                    'alert-type' => "error"
                ]);
            }
        }
        return redirect()->back()->with([
            'message' => __("words.selected-row-deleted"),
            'alert-type' => "success"
        ]);
    }

    static function restore_advance($data) {
        $advance = Advances::create([
            'deportation' => $data['deportation'],
            'operation' => 'deposit',
            'date' => $data['date'],
            'amount' => $data['amount'],
            'employee_data_id' => $data['employee_id'],
            'branch_id' => $data['branch_id'],
            'locker_id' => isset($data['locker_id']) ? $data['locker_id'] : NULL,
            'account_id' => isset($data['account_id']) ? $data['account_id'] : NULL,
            'salary_id' => $data['salary_id']
        ]);
        $number = time();
        $last_number = RevenueReceipt::orderBy('id' ,'desc')->first();
        if ($last_number && $last_number->number) {
            $number = $last_number->number + 1;
        } else if ($last_number) {
            $number = $last_number->id + 1;
        }
        RevenueReceipt::create([
            'date' => now(),
            'time' => now(),
            'receiver' => EmployeeData::find($data['employee_id'])->name,
            'for' => 'advance',
            'cost' => $data['amount'],
            'deportation' => $data['deportation'],
            'revenue_type_id' => RevenueType::where('type_en', 'advances')->first()->id,
            'revenue_item_id' => RevenueItem::where('item_en', 'advances Payments')->first()->id,
            'branch_id' => $data['branch_id'],
            'locker_id' => isset($data['locker_id']) ? $data['locker_id'] : NULL,
            'account_id' => isset($data['account_id']) ? $data['account_id'] : NULL,
            'advance_id' => $advance->id,
            'number' => $number,
            'cost_center_id' => $data['cost_center_id']
        ]);

        $deportation_way = $data['deportation'] == 'bank' ? Account::find($data['account_id']) : Locker::find($data['locker_id']);
        if ($deportation_way) $deportation_way->update(['balance' => $deportation_way->balance + $data['amount']]);
    }
}
