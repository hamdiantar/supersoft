<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ExpensesReceipt;
use App\Models\EmployeeSalary;
use App\Models\Locker;
use App\Models\Account;

use App\Http\Requests\Admin\ExpenseReceipt\ExpenseReceiptRequest;

use Exception;
use DB;

class EmployeeSalaryPayments extends Controller
{
    const view = "admin.employees_salaries.payments";

    function index() {
        $salary_id = isset($_GET['salary_id']) && $_GET['salary_id'] != "" ? $_GET['salary_id'] : NULL;
        if ($salary_id == NULL) return redirect()->to(route('admin:employees_salaries.index'));

        $salary = EmployeeSalary::with([
            'payments' => function ($q) {
                $q->select('id' ,'cost' ,'deportation' ,'employee_salary_id' ,'date' ,
                    'created_at' ,'updated_at' ,'locker_id' ,'account_id' ,'receiver', 'payment_type', 'bank_name', 'check_number', 'number')
                ->with(['locker' ,'bank']);
            }
        ])->where('id' ,$salary_id)->first();

        return view(self::view . ".index" ,compact('salary'));
    }

    function create() {
        $salary_id = isset($_GET['salary_id']) && $_GET['salary_id'] != "" ? $_GET['salary_id'] : NULL;
        if ($salary_id == NULL) return redirect()->to(route('admin:employees_salaries.index'));

        $salary = EmployeeSalary::find($salary_id);
        if ($salary->rest_amount <= 0)
            return redirect()->to(route('admin:employee-salary-payments.index' ,['salary_id' => $salary_id]))->with([
                'alert-type' => 'warning',
                'message' => __('words.salary-paid')
            ]);
        $branch_id = $salary->employee ? $salary->employee->branch_id : $salary->branch_id;
        $user_accessible_locker = $user_accessible_accounts = NULL;
        if (!authIsSuperAdmin()) {
            $user_accessible_locker = \DB::table('lockers_users')
                ->where('user_id' ,auth()->user()->id)->pluck('locker_id')->toArray();
            $user_accessible_accounts = \DB::table('accounts_users')
                ->where('user_id' ,auth()->user()->id)->pluck('account_id')->toArray();
        }
        $lockers = Locker::
            where('branch_id' ,$branch_id)
            ->when($user_accessible_locker ,function ($q) use ($user_accessible_locker) {
                $q->whereIn('id' ,$user_accessible_locker)->orWhereDoesntHave('accessible_users');
            })->where('status', 1)
            ->get();
        $accounts = Account::
            where('branch_id' ,$branch_id)
            ->when($user_accessible_accounts ,function ($q) use ($user_accessible_accounts) {
                $q->whereIn('id' ,$user_accessible_accounts)->orWhereDoesntHave('accessible_users');
            })->where('status', 1)
            ->get();

        $expense_types = \App\Models\ExpensesType::where('type_en' ,'salaries')->where('branch_id' ,$branch_id);
        $temp = clone $expense_types;
        $expense_types_ids = $temp->pluck('id')->toArray();
        $expense_types = $expense_types->get();
        $expense_items = \App\Models\ExpensesItem::whereIn('expense_id' ,$expense_types_ids)->get();

        return view(self::view . ".create" ,compact('salary' ,'lockers' ,'accounts' ,'expense_types' ,'expense_items'));
    }

    function store(ExpenseReceiptRequest $request) {
        try {

            DB::beginTransaction();
            $data = $request->all();
            $last_number = ExpensesReceipt::orderBy('id' ,'desc')->first();
            if ($last_number && $last_number->number) {
                $data['number'] = (int)$last_number->number + 1;
            } else if ($last_number) {
                $data['number'] = (int)$last_number->id + 1;
            }
            $rec = ExpensesReceipt::create($data);
            if ($rec->deportation == 'safe') {
                $locker = Locker::find($rec->locker_id);
                if ($locker && $locker->balance >= $rec->cost) {
                    $locker->update(['balance' => $locker->balance - $rec->cost]);
                } else {
                    DB::rollback();
                    return redirect()->back()->with([
                        'alert-type' => 'error',
                        'message' => __("words.locker-money-issue")
                    ]);
                }
            } else {
                $bank = Account::find($rec->account_id);
                if ($bank && $bank->balance >= $rec->cost) {
                    $bank->update(['balance' => $bank->balance - $rec->cost]);
                } else {
                    DB::rollback();
                    return redirect()->back()->with([
                        'alert-type' => 'error',
                        'message' => __("words.account-money-issue")
                    ]);
                }
            }
            $rec->salary->update([
                'paid_amount' => $rec->salary->paid_amount + $rec->cost,
                'rest_amount' => $rec->salary->rest_amount - $rec->cost
            ]);

            $url = route('admin:employee-salary-payments.index' ,['salary_id' => $rec->employee_salary_id]);

            if($request['save_type'] == 'save_and_print_receipt') {

                $url = route('admin:employee-salary-payments.index' ,['salary_id' => $rec->employee_salary_id, '#show'.$rec->id]);
            }

            DB::commit();

            if($request['save_type'] == 'save_and_print') {

                $url = 'admin/employees_salaries?#show'.$rec->employee_salary_id;

                return  redirect($url)->with([
                    'alert-type' => 'success',
                    'message' => __("words.salary-payment-created")
                ]);
            }

            return redirect()->to($url)->with([
                'alert-type' => 'success',
                'message' => __("words.salary-payment-created")
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->to(route('admin:employee-salary-payments.index' ,['salary_id' => $request->employee_salary_id]))
            ->with([
                'alert-type' => 'error',
                'message' => __("words.try-again")
            ]);
        }
    }

    function edit(Request $request ,$id) {
        try {
            $salary_payment = ExpensesReceipt::findOrFail($id);
            $salary = EmployeeSalary::findOrFail($salary_payment->employee_salary_id);
        } catch (Exception $e) {
            return redirect()->to(route('admin:employees_salaries.index'))->with([
                'alert-type' => 'error',
                'message' => __("words.No Data Available")
            ]);
        }

        if ($salary->rest_amount <= 0)
            return redirect()->to(route('admin:employee-salary-payments.index' ,['salary_id' => $salary->id]))->with([
                'alert-type' => 'warning',
                'message' => __('words.salary-paid')
            ]);
        return view(self::view . ".edit" ,compact('salary' ,'salary_payment'));
    }

    function update(Request $request, $id) {
        try {
            $salary_payment = ExpensesReceipt::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->to(route('admin:employees_salaries.index'))->with([
                'alert-type' => 'error',
                'message' => __("words.No Data Available")
            ]);
        }

        $data = [
            'cost' => $request->cost,
            'date' => $request->date,
            'time' => $request->time,
            'payment_type' => $request->payment_type,
            'check_number' => $request->check_number ?? null,
            'bank_name' => $request->bank_name ?? null,
            'cost_center_id' => $request->cost_center_id
        ];
        $amount_difference = $request->cost - $salary_payment->cost;
        try {
            DB::beginTransaction();
            $salary_payment->update($data);
            if ($amount_difference > 0) {
                $salary_payment->salary->update([
                    'paid_amount' => $salary_payment->salary->paid_amount + $amount_difference,
                    'rest_amount' => $salary_payment->salary->rest_amount - $amount_difference
                ]);
                //minu deportation
                if ($salary_payment->deportation == 'safe') {
                    $locker = Locker::find($salary_payment->locker_id);
                    if ($locker && $locker->balance >= $amount_difference) {
                        $locker->update(['balance' => $locker->balance - $amount_difference]);
                    } else {
                        DB::rollback();
                        return redirect()->back()->with([
                            'alert-type' => 'error',
                            'message' => __("words.locker-money-issue")
                        ]);
                    }
                } else {
                    $bank = Account::find($salary_payment->account_id);
                    if ($bank && $bank->balance >= $amount_difference) {
                        $bank->update(['balance' => $bank->balance - $amount_difference]);
                    } else {
                        DB::rollback();
                        return redirect()->back()->with([
                            'alert-type' => 'error',
                            'message' => __("words.account-money-issue")
                        ]);
                    }
                }
            } else {
                $amount_difference = abs($amount_difference);
                $salary_payment->salary->update([
                    'paid_amount' => $salary_payment->salary->paid_amount - $amount_difference,
                    'rest_amount' => $salary_payment->salary->rest_amount + $amount_difference
                ]);
                //plus deportation
                if ($salary_payment->deportation == 'safe') {
                    $locker = Locker::find($salary_payment->locker_id);
                    if ($locker) {
                        $locker->update(['balance' => $locker->balance + $amount_difference]);
                    } else {
                        DB::rollback();
                        return redirect()->back()->with([
                            'alert-type' => 'error',
                            'message' => __("words.locker-money-issue")
                        ]);
                    }
                } else {
                    $bank = Account::find($salary_payment->account_id);
                    if ($bank) {
                        $bank->update(['balance' => $bank->balance + $amount_difference]);
                    } else {
                        DB::rollback();
                        return redirect()->back()->with([
                            'alert-type' => 'error',
                            'message' => __("words.account-money-issue")
                        ]);
                    }
                }
            }

            DB::commit();

            $url = route('admin:employee-salary-payments.index' ,['salary_id' => $salary_payment->employee_salary_id]);

            if($request['save_type'] == 'save_and_print_receipt') {

                $url = route('admin:employee-salary-payments.index' ,['salary_id' => $salary_payment->employee_salary_id, '#show'.$salary_payment->id]);
            }

            if($request['save_type'] == 'save_and_print') {

                $url = 'admin/employees_salaries?#show'. $salary_payment->employee_salary_id;

                return  redirect($url)->with([
                    'alert-type' => 'success',
                    'message' => __("words.salary-payment-created")
                ]);
            }

            return redirect()->to($url)
            ->with([
                'alert-type' => 'success',
                'message' => __("words.salary-payment-updated")
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => __('words.try-again')
            ]);
        }
    }

    function destroy(Request $request ,$id ,$reusable = false) {
        $s_payment = ExpensesReceipt::find($id);
        if ($s_payment) {
            try {
                DB::beginTransaction();
                if ($s_payment->deportation == 'safe') {
                    $locker = Locker::find($s_payment->locker_id);
                    if ($locker) {
                        $locker->update(['balance' => $locker->balance + $s_payment->cost]);
                    } else {
                        DB::rollback();
                        return $reusable ? false : redirect()->back()->with([
                            'alert-type' => 'error',
                            'message' => __("words.locker-money-issue")
                        ]);
                    }
                } else {
                    $bank = Account::find($s_payment->account_id);
                    if ($bank) {
                        $bank->update(['balance' => $bank->balance + $s_payment->cost]);
                    } else {
                        DB::rollback();
                        return $reusable ? false : redirect()->back()->with([
                            'alert-type' => 'error',
                            'message' => __("words.account-money-issue")
                        ]);
                    }
                }
                $s_payment->salary->update([
                    'paid_amount' => $s_payment->salary->paid_amount - $s_payment->cost,
                    'rest_amount' => $s_payment->salary->rest_amount + $s_payment->cost
                ]);
                $s_payment->delete();
                DB::commit();
                return $reusable ? true : redirect()->back()->with([
                    'alert-type' => 'success',
                    'message' => __("words.salary-payment-deleted")
                ]);
            } catch (Exception $e) {
                DB::rollback();
                return $reusable ? false : redirect()->back()->with([
                    'alert-type' => 'error',
                    'message' => __("words.try-again")
                ]);
            }
        }
        return $reusable ? false : redirect()->back()->with([
            'alert-type' => 'error',
            'message' => __("words.No Data Available")
        ]);
    }

    function deleteSelected() {
        if (!request()->has('ids'))
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => __("words.select-one-least")
            ]);
        foreach(request('ids') as $id) {
            $this->destroy(new Request ,$id ,true);
        }
        return redirect()->back()->with([
            'alert-type' => 'success',
            'message' => __("words.selected-row-deleted")
        ]);
    }
}
