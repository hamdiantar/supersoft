<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Services\CreateRequest;
use App\Http\Requests\Admin\Services\UpdateServiceRequest;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServiceType;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServicesController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_services');
//        $this->middleware('permission:create_services',['only'=>['create','store']]);
//        $this->middleware('permission:update_services',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_services',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $services = Service::with('branch','ServiceType')->orderBy('id','DESC');

//        if(!authIsSuperAdmin())
//            $services->where('branch_id', auth()->user()->branch_id);

        if($request->has('name') && $request['name'] != '')
            $services->where('id',$request['name']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $services->where('branch_id',$request['branch_id']);

        if($request->has('type_id') && $request['type_id'] != '')
            $services->where('type_id',$request['type_id']);

        if($request->has('active') && $request['active'] != '')
            $services->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $services->where('status',0);

        $services = $services->get();


        $services_search = filterSetting() ?  Service::all()->pluck('name','id') : null;
        $types = filterSetting() ?  ServiceType::all()->pluck('name','id') : null;
        $branches = filterSetting() ?  Branch::all()->pluck('name','id') : null;

        return view('admin.services.index',compact('services','branches','services_search','types'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $types = ServiceType::where('status',1)->get()->pluck('name','id');
        return view('admin.services.create',compact('branches','types'));
    }

    public function store(CreateRequest $request)
    {
        if (!auth()->user()->can('create_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
               $data['branch_id'] = auth()->user()->branch_id;

            Service::create($data);

        }catch (\Exception $e){

//            dd($e->getMessage());

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:services.index'))
            ->with(['message' => __('words.service-created'),'alert-type'=>'success']);
    }

    public function show(Service $service)
    {
        if (!auth()->user()->can('view_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.services.show',compact('service'));
    }

    public function edit(Service $service)
    {
        if (!auth()->user()->can('update_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $types = ServiceType::where('status',1)->where('branch_id', $service->branch_id)->get()->pluck('name','id');
        return view('admin.services.edit',compact('service','branches','types'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        if (!auth()->user()->can('update_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();

            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $service->update($data);

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:services.index'))
            ->with(['message' => __('words.service-updated'),'alert-type'=>'success']);
    }

    public function destroy(Service $service)
    {
        if (!auth()->user()->can('delete_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $service->delete();
        return redirect(route('admin:services.index'))
            ->with(['message' => __('words.data-archived-success'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_services')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids) && isset($request->archive)) {
            Service::whereIn('id', $request->ids)->delete();
            return back()->with(['message' => __('words.selected-row-archived'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->restore)) {
            Service::withTrashed()->whereIn('id', $request->ids)->restore();
            return back()->with(['message' => __('words.selected-row-restored'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->forcDelete)) {
            Service::withTrashed()->whereIn('id', $request->ids)->forceDelete();
            return back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        }

        return redirect(route('admin:services.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getServicesTypesByBranch(Request $request){

        $services_types = ServiceType::where('status',1)->where('branch_id', $request['branch_id'])->get()->pluck('name','id');
        return $services_types;
    }

    public function serviceArchive(Request $request): View
    {
        $services = Service::onlyTrashed()->with('branch','ServiceType')->orderBy('id','DESC');
        if($request->has('name') && $request['name'] != '')
            $services->where('id',$request['name']);

        if($request->has('branch_id') && $request['branch_id'] != '')
            $services->where('branch_id',$request['branch_id']);

        if($request->has('type_id') && $request['type_id'] != '')
            $services->where('type_id',$request['type_id']);

        if($request->has('active') && $request['active'] != '')
            $services->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '')
            $services->where('status',0);

        $services = $services->get();
        $services_search = filterSetting() ?  Service::all()->pluck('name','id') : null;
        $types = filterSetting() ?  ServiceType::all()->pluck('name','id') : null;
        $branches = filterSetting() ?  Branch::all()->pluck('name','id') : null;
        return view('admin.services.archive',compact('services','branches','services_search','types'));
    }

    public function restoreDelete(int $id)
    {
        try {
            $Service = Service::withTrashed()->findOrFail($id);
            $Service->restore();
            return back()->with(['message' => __('words.data-restored-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-restore-date-from-archive'), 'alert-type' => 'error']);
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $Service = Service::withTrashed()->findOrFail($id);
            $Service->forceDelete();
            return back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-forced-deleted'), 'alert-type' => 'error']);
        }
    }
}
