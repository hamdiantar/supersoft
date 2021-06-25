<?php

namespace App\Http\Controllers\Admin;

use DB;
use Exception;
use Carbon\Carbon;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Model\EmployeeAbsence;
use App\Http\Controllers\Controller;

use App\Models\EmployeeData as Employee;
use App\Http\Requests\EmployeeAbsenceReq;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Employees\Absences;

class EmployeeAbsenceCont extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_employee-absence');
//        $this->middleware('permission:create_employee-absence',['only'=>['create','store']]);
//        $this->middleware('permission:update_employee-absence',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_employee-absence',['only'=>['destroy','deleteSelected']]);
    }

    const module_name = "admin:employee-absence";
    const view_path = "admin.employee-absence.";

    public function index()
    {

        if (!auth()->user()->can('view_employee-absence')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $date_from = isset($_GET['date_from']) && $_GET['date_from'] != "" ? $_GET['date_from'] : NULL;
        $date_to = isset($_GET['date_to']) && $_GET['date_to'] != "" ? $_GET['date_to'] : date('Y-m-d');
        $name = isset($_GET['name']) && $_GET['name'] != "" ? $_GET['name'] : NULL;
        $type = isset($_GET['type']) && $_GET['type'] != "" ? $_GET['type'] : NULL;
        $branch_id = isset($_GET['branch']) && $_GET['branch'] != "" ? $_GET['branch'] : NULL;

        if ($date_from) {
            $date_from = (new Carbon($date_from))->toDateString();
            $date_to = (new Carbon($date_to))->toDateString();
        }

        $employees = Employee::all();
        $branches = Branch::all();

        $absences = EmployeeAbsence
            ::with('employee')
            ->when($name ,function ($q) use ($name) {
                $q->where('employee_id' ,$name);
            })
            ->when($type ,function ($q) use ($type) {
                $q->where('absence_type' ,$type);
            })
            ->when($branch_id ,function ($q) use ($branch_id) {
                $q->where('branch_id' ,$branch_id);
            })
            ->when($date_from ,function ($q) use ($date_from ,$date_to) {
                $q->where('date' ,'>=' ,$date_from)->where('date' ,'<=' ,$date_to);
            });

        if (request()->has('sort_by') && request('sort_by') != '') {
            $sort_by = request('sort_by');
            $sort_method = request()->has('sort_method') ? request('sort_method') :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'employee_id',
                'type' => 'absence_type',
                'days' => 'absence_days',
                'date' => 'date',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $absences = $absences->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $absences = $absences->orderBy('id', 'DESC');
        }
        if (request()->has('key')) {
            $key = request('key');
            $absences->where(function ($q) use ($key) {
                $q->where('date' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%");
            });
        }
        if (request()->has('invoker') && in_array(request('invoker') ,['print' ,'excel'])) {
            $visible_columns = request()->has('visible_columns') ? request('visible_columns') : [];
            return (new ExportPrinterFactory(new Absences($absences ,$visible_columns), request('invoker')))();
        }
        $rows = request()->has('rows') ? request('rows') : 10;
        $absences = $absences->paginate($rows)->appends(request()->query());
        
        return view(self::view_path . 'index' ,compact('employees' ,'absences' ,'branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_employee-absence')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employees = Employee::all();
        $branches = Branch::all();
        return view(self::view_path . "create" ,compact('employees' ,'branches'));
    }

    public function store(EmployeeAbsenceReq $request)
    {

        if (!auth()->user()->can('create_employee-absence')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $curYear = date("Y");
        $dateRange = [$curYear."-01-01" ,$curYear."-12-31"];
        $takesVacation = EmployeeAbsence
            ::where('employee_id' ,$request->employee_id)
            ->whereDate('date' ,'>=' ,$dateRange[0])
            ->whereDate('date' ,'<=' ,$dateRange[1])
            ->where('absence_type' ,'vacation')
            ->sum('absence_days');

        DB::beginTransaction();
        $abs = EmployeeAbsence::create($request->all());
        if ($abs->absence_type == 'vacation') {
            if ($abs->employee && $abs->employee->employeeSetting
                && $abs->employee->employeeSetting->annual_vocation_days >= ($takesVacation + $abs->absence_days)) {
                DB::commit();
            } else {
                DB::rollback();
                return redirect()->back()->withInput()->with([
                    'alert-type' => 'error' ,
                    'message' => __('words.employee-vacation-limit')
                ]);
            }
        } else DB::commit();
        return redirect(route(self::module_name . ".index"))->with([
            'alert-type' => 'success' ,
            'message' => __('words.absence-created')
        ]);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('update_employee-absence')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $absence = EmployeeAbsence::findOrFail($id);
            $employees = Employee::all();
            $branches = Branch::all();
            return view(self::view_path . "edit" ,compact('employees' ,'absence' ,'branches'));
        } catch (Exception $e) {
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'error' ,
                'message' => __('words.No Data Available')
            ]);
        }
    }

    public function update(EmployeeAbsenceReq $request ,$id)
    {
        if (!auth()->user()->can('update_employee-absence')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $absence = EmployeeAbsence::findOrFail($id);
            DB::beginTransaction();
            $daysDiff = $request->absence_days - $absence->absence_days;
            if ($request->absence_type == 'vacation' && $daysDiff > 0) {
                $curYear = date("Y");
                $dateRange = [$curYear."-01-01" ,$curYear."-12-31"];
                $takesVacation = EmployeeAbsence
                    ::where('employee_id' ,$request->employee_id)
                    ->whereDate('date' ,'>=' ,$dateRange[0])
                    ->whereDate('date' ,'<=' ,$dateRange[1])
                    ->where('absence_type' ,'vacation')
                    ->sum('absence_days');

                $emp = Employee::find($request->employee_id);
                if ($emp && $emp->employeeSetting &&
                    $emp->employeeSetting->annual_vocation_days >= ($takesVacation + $daysDiff)) {
                } else {
                    DB::rollback();
                    return redirect()->back()->withInput()->with([
                        'alert-type' => 'error' ,
                        'message' => __('words.employee-vacation-limit')
                    ]);
                }
            }
            $absence->update($request->all());
            DB::commit();
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'success' ,
                'message' => __('words.absence-updated')
            ]);
        } catch (Exception $e) {
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'error' ,
                'message' => __('words.No Data Available')
            ]);
        }
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete_employee-absence')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            EmployeeAbsence::findOrFail($id)->delete();
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'success' ,
                'message' => __('words.absence-deleted')
            ]);
        } catch (Exception $e) {
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'error' ,
                'message' => __('words.No Data Available')
            ]);
        }
    }

    function deleteSelected()
    {
        if (!auth()->user()->can('delete_employee-absence')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!request()->has('ids')) {
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'error' ,
                'message' => __('words.select-one-least')
            ]);
        }
        try {
            foreach(request('ids') as $id) {
                $absence = EmployeeAbsence::find($id);
                if ($absence) $absence->delete();
            }
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'success' ,
                'message' => __('words.selected-row-deleted')
            ]);
        } catch (Exception $e) {
            return redirect(route(self::module_name . ".index"))->with([
                'alert-type' => 'error' ,
                'message' => __('words.No Data Available')
            ]);
        }
    }
}
