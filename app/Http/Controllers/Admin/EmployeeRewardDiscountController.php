<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\EmployeeDelay;
use App\Filters\EmployeeDelayFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\EmployeeRewardDiscount;
use App\Filters\EmployeeRewardDiscountFilter;
use App\Http\Controllers\DataExportCore\Employees\Rewards;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\EmployeeDelay\EmployeeDelayRequest;
use App\Http\Requests\Admin\EmployeeRewardDiscount\EmployeeRewardDiscountRequest;

class EmployeeRewardDiscountController extends Controller
{
    /**
     * @var EmployeeRewardDiscountFilter
     */
    protected $employeeRewardDiscountFilter;

    public function __construct(EmployeeRewardDiscountFilter $employeeRewardDiscountFilter)
    {
        $this->employeeRewardDiscountFilter = $employeeRewardDiscountFilter;
//        $this->middleware('permission:view_employee_reward_discount');
//        $this->middleware('permission:create_employee_reward_discount',['only'=>['create','store']]);
//        $this->middleware('permission:update_employee_reward_discount',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_employee_reward_discount',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_employee_reward_discount')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employees = EmployeeRewardDiscount::query();

        if ($request->hasAny((new EmployeeRewardDiscount())->getFillable())) {
            $employees = $this->employeeRewardDiscountFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'employee_setting_id',
                'type' => 'type',
                'cost' => 'cost',
                'date' => 'date',
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
                $q->where('date' ,'like' ,"%$key%")
                ->orWhere('cost' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Rewards($employees->with('employeeDate') ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $employees = $employees->paginate($rows)->appends(request()->query());

        return view('admin.employee_reward_discount.index', ['employees' => $employees]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_employee_reward_discount')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employee_reward_discount.create');
    }

    public function store(EmployeeRewardDiscountRequest $request)
    {
        if (!auth()->user()->can('create_employee_reward_discount')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        EmployeeRewardDiscount::create($request->all());
        return redirect()->to('admin/employee_reward_discount')
            ->with(['message' => __('words.reward-created'), 'alert-type' => 'success']);
    }

    public function edit(EmployeeRewardDiscount $employeeRewardDiscount)
    {
        if (!auth()->user()->can('update_employee_reward_discount')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employee_reward_discount.edit', compact('employeeRewardDiscount'));
    }

    public function update(EmployeeRewardDiscountRequest $request, EmployeeRewardDiscount $employeeRewardDiscount): RedirectResponse
    {
        if (!auth()->user()->can('update_employee_reward_discount')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        $employeeRewardDiscount->update($data);
        return redirect()->to('admin/employee_reward_discount')
            ->with(['message' => __('words.reward-updated'), 'alert-type' => 'success']);
    }

    public function destroy(EmployeeRewardDiscount $employeeRewardDiscount): RedirectResponse
    {
        if (!auth()->user()->can('delete_employee_reward_discount')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employeeRewardDiscount->delete();
        return redirect()->to('admin/employee_reward_discount')
            ->with(['message' => __('words.reward-deleted'), 'alert-type' => 'success']);
    }

    function deleteSelected() {

        if (!auth()->user()->can('delete_employee_reward_discount')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!request()->has('ids'))
            return redirect()->back()->with([
                'message' => __("words.select-one-least"),
                'alert-type' => "error"
            ]);
        try {
            \DB::beginTransaction();
            foreach(request('ids') as $id) {
                $setting = EmployeeRewardDiscount::find($id);
                if ($setting) {
                    try {
                        $setting->delete();
                    } catch (\Exception $e) {}
                }
            }
            \DB::commit();
            return redirect()->back()->with([
                'message' => __("words.selected-row-deleted"),
                'alert-type' => "success"
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with([
                'message' => __("words.try-again"),
                'alert-type' => "error"
            ]);
        }
    }
}
