<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use App\Models\EmployeeSetting;
use App\Filters\EmployeeDataFilter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DataExportCore\Employees\Data;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\EmployeeData\EmployeeDataCreateRequest;
use App\Http\Requests\Admin\EmployeeData\EmployeeDataUpdateRequest;

class EmployeesDataController extends Controller
{
    /**
     * @var EmployeeDataFilter
     */
    protected $employeeDataFilter;

    public function __construct(EmployeeDataFilter $employeeDataFilter)
    {
        $this->employeeDataFilter = $employeeDataFilter;
//        $this->middleware('permission:view_employees_data');
//        $this->middleware('permission:create_employees_data',['only'=>['create','store']]);
//        $this->middleware('permission:update_employees_data',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_employees_data',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_employees_data')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employeesData = EmployeeData::query();

        if ($request->hasAny((new EmployeeData())->getFillable())) {
            $employeesData = $this->employeeDataFilter->filter($request);
        }
        $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'name_'.$lang,
                'setting' => 'employee_setting_id',
                'phone' => 'phone2',
                'status' => 'status',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $employeesData = $employeesData->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $employeesData = $employeesData->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $employeesData->where(function ($q) use ($key ,$lang) {
                $q->where('name_'.$lang ,'like' ,"%$key%")
                ->orWhere('phone2' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Data($employeesData ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $employeesData = $employeesData->paginate($rows)->appends(request()->query());

        return view('admin.employees_data.index', ['employeesData' => $employeesData]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_employees_data')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employees_data.create');
    }

    public function store(EmployeeDataCreateRequest $request)
    {
        if (!auth()->user()->can('create_employees_data')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if ($request->hasFile('cv')) {
            $fileName = time().'.'.$request->cv->extension();
            $request->cv->move(public_path('employees/cv'), $fileName);
            $data['cv'] = $fileName;
        }

        EmployeeData::create($data);
        return redirect()->to('admin/employees_data')
            ->with(['message' => __('words.employee-created'), 'alert-type' => 'success']);
    }

    public function edit(EmployeeData $employeeData)
    {
        if (!auth()->user()->can('update_employees_data')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employees_data.edit', compact('employeeData'));
    }

    public function update(EmployeeDataUpdateRequest $request, EmployeeData $employeeData): RedirectResponse
    {
        if (!auth()->user()->can('update_employees_data')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if ($request->hasFile('cv')) {
            $fileName = time().'.'.$request->cv->extension();
            $request->cv->move(public_path('employees/cv'), $fileName);
            $data['cv'] = $fileName;
        }
        $employeeData->update($data);
        return redirect()->to('admin/employees_data')
            ->with(['message' => __('words.employee-updated'), 'alert-type' => 'success']);
    }

    public function destroy(EmployeeData $employeeData): RedirectResponse
    {
        if (!auth()->user()->can('delete_employees_data')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $employeeData->delete();
        } catch (\Exception $e) {

            return redirect()->to('admin/employees_data')
            ->with(['message' => __('words.cant-delete-employee'), 'alert-type' => 'error']);
        }
        return redirect()->to('admin/employees_data')
            ->with(['message' => __('words.employee-deleted'), 'alert-type' => 'success']);
    }

    public function getEmpSettingByBranch(Request $request)
    {
        $emp = EmployeeSetting::where('branch_id', $request->branch_id)->get();
        $empHtml = ' <option value="">'.__('words.Select Employee Category').'</option>';
        foreach ($emp as $e) {
            $empHtml .= "<option value='$e->id'>$e->name</option>";
        }
        return response()->json([ 'empSetting' => $empHtml]);
    }

    public function viewCv(Request $request)
    {
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . 'cv' . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        @readfile($request->file);
    }

    function deleteSelected() {

        if (!auth()->user()->can('delete_employees_data')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!request()->has('ids'))
            return redirect()->back()->with([
                'message' => __("words.select-one-least"),
                'alert-type' => "error"
            ]);
        $not_deleted = [];
        try {
            \DB::beginTransaction();
            foreach(request('ids') as $id) {
                $setting = EmployeeData::find($id);
                if ($setting) {
                    try {
                        $setting->delete();
                    } catch (\Exception $e) {
                        $not_deleted[] = $setting->name;
                    }
                }
            }
            \DB::commit();
            $return = [
                'message' => __("words.selected-row-deleted"),
                'alert-type' => "success"
            ];
            if (!empty($not_deleted)) {
                $return['custom-message'] = __("words.those-employees") . " (" .
                    implode(", " ,$not_deleted) . ") " . __('words.employee-still-exists');
            }
            return redirect()->back()->with($return);
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with([
                'message' => __("words.try-again"),
                'alert-type' => "error"
            ]);
        }
    }

    function getEmployees() {
        try {
            $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
            $choosed_acc = isset($_GET['choosed_acc']) && $_GET['choosed_acc'] != '' ? $_GET['choosed_acc'] : NULL;
            $employees = EmployeeData::when($branch_id ,function ($q) use ($branch_id) {
                    $q->where('branch_id' ,$branch_id);
                })
                ->get();
            $html_select = "<option value=''>". __('words.select-one') ."</option>";
            foreach($employees as $employee) {
                $selected = $choosed_acc && $choosed_acc == $employee->id ? "selected" : "";
                $html_select .= "<option $selected value='". $employee->id ."'>". $employee->name ."</option>";
            }
            return response(['options' => $html_select]);
        } catch (\Exception $e) {
            return response(['message' => __('words.back-support')] ,400);
        }
    }
}
