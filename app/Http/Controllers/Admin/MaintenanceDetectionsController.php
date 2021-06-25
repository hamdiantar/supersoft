<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Maintenance\CreateRequest;
use App\Http\Requests\Admin\Maintenance\UpdateRequest;
use App\Models\Branch;
use App\Models\MaintenanceDetection;
use App\Models\MaintenanceDetectionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaintenanceDetectionsController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_maintenance_detections');
//        $this->middleware('permission:create_maintenance_detections',['only'=>['create','store']]);
//        $this->middleware('permission:update_maintenance_detections',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_maintenance_detections',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $maintenanceDetections = MaintenanceDetection::orderBy('id','DESC');

//        if(!authIsSuperAdmin())
//            $maintenanceDetections->where('branch_id', auth()->user()->branch_id);

        if($request->has('name') && $request['name'] != '')
            $maintenanceDetections->where('id',$request['name']);

        if($request->has('maintenance_type_id') && $request['maintenance_type_id'] != '')
            $maintenanceDetections->where('maintenance_type_id',$request['maintenance_type_id']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $maintenanceDetections->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $maintenanceDetections->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $maintenanceDetections->where('status',0);

        $maintenanceDetections = $maintenanceDetections->get();

        $branches = filterSetting() ?  Branch::all()->pluck('name','id') : null;
        $maintenance_types = filterSetting() ?  MaintenanceDetectionType::all()->pluck('name','id') : null;
        $maintenance = filterSetting() ?  MaintenanceDetection::all()->pluck('name','id') : null;

        return view('admin.maintenance-detections.index',
            compact('maintenanceDetections','maintenance_types','branches','maintenance'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $types = MaintenanceDetectionType::where('status',1)->get()->pluck('name','id');
        return view('admin.maintenance-detections.create',compact('types','branches'));
    }

    public function store(CreateRequest $request)
    {
        if (!auth()->user()->can('create_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            MaintenanceDetection::create($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => $e->getMessage(),'alert-type'=>'error']);
        }

        return redirect(route('admin:maintenance-detections.index'))
            ->with(['message' => __('words.maintenance-detection-created'),'alert-type'=>'success']);
    }

    public function show(MaintenanceDetection $maintenanceDetection)
    {
        if (!auth()->user()->can('view_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.maintenance-detections.show',compact('maintenanceDetection'));
    }

    public function edit(MaintenanceDetection $maintenanceDetection)
    {
        if (!auth()->user()->can('update_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $types = MaintenanceDetectionType::where('status',1)
            ->where('branch_id', $maintenanceDetection->branch_id)
            ->get()->pluck('name','id');
        return view('admin.maintenance-detections.edit',compact('types','branches','maintenanceDetection'));
    }

    public function update(UpdateRequest $request, MaintenanceDetection $maintenanceDetection)
    {
        if (!auth()->user()->can('update_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{

            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $maintenanceDetection->update($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => $e->getMessage(),'alert-type'=>'error']);
        }

        return redirect(route('admin:maintenance-detections.index'))
            ->with(['message' => __('words.maintenance-detection-updated'),'alert-type'=>'success']);
    }

    public function destroy(MaintenanceDetection $maintenanceDetection)
    {
        if (!auth()->user()->can('delete_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $maintenanceDetection->delete();

        return redirect(route('admin:maintenance-detections.index'))
            ->with(['message' => __('words.maintenance-detection-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_maintenance_detections')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            MaintenanceDetection::whereIn('id', $request->ids)->delete();
            return redirect(route('admin:maintenance-detections.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:maintenance-detections.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getMaintenanceTypesByBranch(Request $request){

        $maintenance_types = MaintenanceDetectionType::where('status',1)->where('branch_id', $request['branch_id'])
            ->get()->pluck('name','id');

        return $maintenance_types;
    }
}
