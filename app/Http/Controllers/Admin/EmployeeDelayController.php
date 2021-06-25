<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\EmployeeDelay;
use App\Filters\EmployeeDelayFilter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DataExportCore\Employees\Delays;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\EmployeeDelay\EmployeeDelayRequest;

class EmployeeDelayController extends Controller
{
    /**
     * @var EmployeeDelayFilter
     */
    protected $employeeDelayFilter;

    public function __construct(EmployeeDelayFilter $employeeDelayFilter)
    {
        $this->employeeDelayFilter = $employeeDelayFilter;
//        $this->middleware('permission:view_employees_delay');
//        $this->middleware('permission:create_employees_delay',['only'=>['create','store']]);
//        $this->middleware('permission:update_employees_delay',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_employees_delay',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_employees_delay')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employees = EmployeeDelay::query();

        if ($request->hasAny((new EmployeeDelay())->getFillable())) {
            $employees = $this->employeeDelayFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'name' => 'employee_setting_id',
                'status' => 'type',
                'hours' => 'number_of_hours',
                'minutes' => 'number_of_minutes',
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
                $q->where('number_of_hours' ,'like' ,"%$key%")
                ->orWhere('number_of_minutes' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Delays($employees->with('employeeDate') ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $employees = $employees->paginate($rows)->appends(request()->query());

        return view('admin.employees_delay.index', ['employees' => $employees]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_employees_delay')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employees_delay.create');
    }

    public function store(EmployeeDelayRequest $request)
    {
        if (!auth()->user()->can('create_employees_delay')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        EmployeeDelay::create($request->all());
        return redirect()->to('admin/employees_delay')
            ->with(['message' => __('words.delay-created'), 'alert-type' => 'success']);
    }

    public function edit(EmployeeDelay $employeeDelay)
    {
        if (!auth()->user()->can('update_employees_delay')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.employees_delay.edit', compact('employeeDelay'));
    }

    public function update(EmployeeDelayRequest $request, EmployeeDelay $employeeDelay): RedirectResponse
    {
        if (!auth()->user()->can('update_employees_delay')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        $employeeDelay->update($data);
        return redirect()->to('admin/employees_delay')
            ->with(['message' => __('words.delay-updated'), 'alert-type' => 'success']);
    }

    public function destroy(EmployeeDelay $employeeDelay): RedirectResponse
    {
        if (!auth()->user()->can('delete_employees_delay')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $employeeDelay->delete();
        return redirect()->to('admin/employees_delay')
            ->with(['message' => __('words.delay-deleted'), 'alert-type' => 'success']);
    }

    function deleteSelected() {

        if (!auth()->user()->can('delete_employees_delay')) {
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
                $setting = EmployeeDelay::find($id);
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
