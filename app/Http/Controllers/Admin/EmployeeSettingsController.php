<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shift;
use App\Models\Branch;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\EmployeeSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Filters\EmployeeSettingFilter;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Employees\Settings;
use App\Http\Requests\Admin\EmployeeSettings\EmployeeSettingCreateRequest;

class EmployeeSettingsController extends Controller
{
    /**
     * @var EmployeeSettingFilter
     */
    protected $employeeSettingFilter;

    public function __construct(EmployeeSettingFilter $employeeSettingFilter)
    {
        $this->employeeSettingFilter = $employeeSettingFilter;
//        $this->middleware('permission:view_employee_settings');
//        $this->middleware('permission:create_employee_settings',['only'=>['create','store']]);
//        $this->middleware('permission:update_employee_settings',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_employee_settings',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_employee_settings')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employees = EmployeeSetting::query();

        if ($request->hasAny((new EmployeeSetting())->getFillable())) {
            $employees = $this->employeeSettingFilter->filter($request);
        }
        $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'name_'.$lang,
                'type' => 'type_account',
                'status' => 'status',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $employees = $employees->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $employees = $employees->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $employees->where(function ($q) use ($key ,$lang) {
                $q->where('name_'.$lang ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Settings($employees ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $employees = $employees->paginate($rows)->appends(request()->query());

        return view('admin.employeeSettings.index', ['employees' => $employees]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_employee_settings')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $shifts = Shift::get();
        $branches = Branch::all();
        return view('admin.employeeSettings.create', compact('shifts', 'branches'));
    }

    public function store(EmployeeSettingCreateRequest $request): RedirectResponse
    {
        if (!auth()->user()->can('create_employee_settings')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();

        if (!isset($data['shift_id'])) $data['shift_id'] = 0;

        EmployeeSetting::create($data);

        return redirect()->to('admin/employee_settings')
            ->with(['message' => __('words.employee-setting-created'), 'alert-type' => 'success']);
    }

    public function edit(EmployeeSetting $employeeSetting)
    {
        if (!auth()->user()->can('update_employee_settings')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $shifts = Shift::get();
        return view('admin.employeeSettings.edit', compact('shifts', 'employeeSetting'));
    }

    public function update(EmployeeSettingCreateRequest $request, EmployeeSetting $employeeSetting): RedirectResponse
    {
        if (!auth()->user()->can('update_employee_settings')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!isset($data['shift_id'])) $data['shift_id'] = 0;

        $data['service_status'] = 0;

        if($request['service_status']){
            $data['service_status'] = 1;
        }

        $employeeSetting->update($data);
        return redirect()->to('admin/employee_settings')
            ->with(['message' => __('words.employee-setting-updated'), 'alert-type' => 'success']);
    }

    public function destroy(EmployeeSetting $employeeSetting): RedirectResponse
    {
        if (!auth()->user()->can('delete_employee_settings')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $employeeSetting->delete();
        } catch (\Exception $e) {
            return redirect()->to('admin/employee_settings')
            ->with(['message' => __('words.cant-delete-employee-setting'), 'alert-type' => 'error']);
        }
        return redirect()->to('admin/employee_settings')
            ->with(['message' => __('words.employee-setting-deleted'), 'alert-type' => 'success']);
    }

    public function getShiftsByBranch(Request $request)
    {
        $shifts = Shift::where('branch_id', $request->branch_id)->get();
        $shiftHtml = ' <option value="">'.__('words.select-one').'</option>';
        foreach ($shifts as $shift) {
            $shiftHtml .= "<option value='$shift->id'>$shift->name</option>";
        }

        return response()->json(['shifts' => $shiftHtml]);
    }

    function deleteSelected() {

        if (!auth()->user()->can('delete_employee_settings')) {
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
                $setting = EmployeeSetting::find($id);
                if ($setting) {
                    try {
                        $setting->delete();
                    } catch (\Exception $e) {
                        $not_deleted[] = $setting->name;
                    }
                }
            }
            $return = [
                'message' => __("words.selected-row-deleted"),
                'alert-type' => "success"
            ];
            if (!empty($not_deleted)) {
                $return['custom-message'] = __("words.those-settings") . " (" . implode(", " ,$not_deleted) .
                    ") ". __('words.employee-setting-still-exists');
            }
            \DB::commit();
            return redirect()->back()->with($return);
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with([
                'message' => __("words.try-again"),
                'alert-type' => "error"
            ]);
        }
    }
}
