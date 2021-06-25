<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Filters\EmployeeAttendanceFilter;
use App\Http\Controllers\DataExportCore\Employees\Attendance;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\EmployeeAttendance\EmployeeAttendanceRequest;

class EmployeeAttendanceController extends Controller
{
    /**
     * @var EmployeeAttendanceFilter
     */
    protected $employeeAttendanceFilter;

    public function __construct(EmployeeAttendanceFilter $employeeAttendanceFilter)
    {
        $this->employeeAttendanceFilter = $employeeAttendanceFilter;
//        $this->middleware('permission:view_employees_attendance');
//        $this->middleware('permission:create_employees_attendance',['only'=>['create','store']]);
//        $this->middleware('permission:update_employees_attendance',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_employees_attendance',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_employees_attendance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employees = EmployeeAttendance::query();

        if ($request->hasAny((new EmployeeAttendance())->getFillable())) {
            $employees = $this->employeeAttendanceFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'employee_data_id',
                'status' => 'type',
                'date' => 'date',
                'time' => 'time',
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
                ->orWhere('time' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Attendance($employees->with('employeeDate') ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;
        $employees = $employees->paginate($rows)->appends(request()->query());

        return view('admin.employees_attendance.index', ['employees' => $employees]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_employees_attendance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employees_attendance.create');
    }

    public function store(EmployeeAttendanceRequest $request)
    {
        if (!auth()->user()->can('create_employees_attendance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        EmployeeAttendance::create($request->all());
        return redirect()->to('admin/employees_attendance')
            ->with(['message' => __('words.attendance-created'), 'alert-type' => 'success']);
    }

    public function edit(EmployeeAttendance $employeeAttendance)
    {
        if (!auth()->user()->can('update_employees_attendance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employees_attendance.edit', compact('employeeAttendance'));
    }

    public function update(EmployeeAttendanceRequest $request, EmployeeAttendance $employeeAttendance): RedirectResponse
    {

        if (!auth()->user()->can('update_employees_attendance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        $employeeAttendance->update($data);
        return redirect()->to('admin/employees_attendance')
            ->with(['message' => __('words.attendance-updated'), 'alert-type' => 'success']);
    }

    public function destroy(EmployeeAttendance $employeeAttendance): RedirectResponse
    {
        if (!auth()->user()->can('delete_employees_attendance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employeeAttendance->delete();
        return redirect()->to('admin/employees_attendance')
            ->with(['message' => __('words.attendance-deleted'), 'alert-type' => 'success']);
    }

    public function getEmpByBranch(Request $request)
    {
        $emps = EmployeeData::where('branch_id', $request->branch_id)->get();
        $empHtml = '<option value="">'.__("Select Employee").'</option>';
        foreach ($emps as $emp) {
            $totalWith = $totalDep = 0;
            foreach($emp->advances as $adv) {
                if ($adv->operation == __('withdrawal')) $totalWith += $adv->amount;
                if ($adv->operation == __('deposit')) $totalDep += $adv->amount;
            }
            $total = $totalWith - $totalDep;
            if ($total < 0) $total = 0;
            $max_advance = $emp->employeeSetting->max_advance;
            $restFromMaxAdvance = $max_advance - $total;
            $empHtml .= "<option value=".$emp->id." data-rest-amount=".$total."
                           data-rest-advance=".$restFromMaxAdvance."
                           data-max-advance=".$max_advance.">$emp->name</option>";
        }
        return response()->json(['emp' => $empHtml]);
    }

    function deleteSelected() {

        if (!auth()->user()->can('delete_employees_attendance')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!request()->has('ids'))
            return redirect()->back()->with([
                'message' => __("select-one-least"),
                'alert-type' => "error"
            ]);
        try {
            \DB::beginTransaction();
            foreach(array_unique(request('ids')) as $id) {
                $setting = EmployeeAttendance::find($id);
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
