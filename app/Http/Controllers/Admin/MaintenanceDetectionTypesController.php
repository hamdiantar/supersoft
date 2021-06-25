<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MaintenanceTypes\CreateRequest;
use App\Http\Requests\Admin\MaintenanceTypes\UpdateRequest;
use App\Models\Branch;
use App\Models\MaintenanceDetectionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaintenanceDetectionTypesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_maintenance_detections_types');
//        $this->middleware('permission:create_maintenance_detections_types',['only'=>['create','store']]);
//        $this->middleware('permission:update_maintenance_detections_types',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_maintenance_detections_types',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $types = MaintenanceDetectionType::orderBy('id','DESC');

//        if(!authIsSuperAdmin())
//            $types->where('branch_id', auth()->user()->branch_id);

        if($request->has('name') && $request['name'] != '')
            $types->where('id',$request['name']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $types->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $types->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $types->where('status',0);

        $types = $types->get();

        $branches = filterSetting() ?  Branch::all()->pluck('name','id') : null;
        $maintenance_types = filterSetting() ?  MaintenanceDetectionType::all()->pluck('name','id') : null;

        return view('admin.maintenance-detection-types.index',compact('types','maintenance_types','branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        return view('admin.maintenance-detection-types.create',compact('branches'));
    }

    public function store(CreateRequest $request)
    {
        if (!auth()->user()->can('create_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            MaintenanceDetectionType::create($data);

        }catch (\Exception $e){

            return redirect()->back()->with(['message' => $e->getMessage(),'alert-type'=>'error']);
        }

        return redirect(route('admin:maintenance-detection-types.index'))
            ->with(['message' => __('words.maintenance-detection-type-created'),'alert-type'=>'success']);
    }

    public function show(MaintenanceDetectionType $maintenanceDetectionType)
    {
        if (!auth()->user()->can('show_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.maintenance-detection-types.show',compact('maintenanceDetectionType'));
    }

    public function edit(MaintenanceDetectionType $maintenanceDetectionType)
    {
        if (!auth()->user()->can('update_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        return view('admin.maintenance-detection-types.edit',compact('maintenanceDetectionType','branches'));
    }

    public function update(UpdateRequest $request, MaintenanceDetectionType $maintenanceDetectionType)
    {
        if (!auth()->user()->can('update_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $maintenanceDetectionType->update($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => $e->getMessage(),'alert-type'=>'error']);
        }

        return redirect(route('admin:maintenance-detection-types.index'))
            ->with(['message' => __('words.maintenance-detection-type-updated'),'alert-type'=>'success']);
    }

    public function destroy(MaintenanceDetectionType $maintenanceDetectionType)
    {
        if (!auth()->user()->can('delete_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $maintenanceDetectionType->delete();

        return redirect(route('admin:maintenance-detection-types.index'))
            ->with(['message' => __('words.maintenance-detection-type-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_maintenance_detections_types')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            MaintenanceDetectionType::whereIn('id', $request->ids)->delete();
            return redirect(route('admin:maintenance-detection-types.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:maintenance-detection-types.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
