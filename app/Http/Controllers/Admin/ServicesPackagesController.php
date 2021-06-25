<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ServicePackageFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesPackage\ServicesPackageRequest;
use App\Http\Requests\Admin\ServicesPackage\UpdatePackageRequest;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\ServiceType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicesPackagesController extends Controller
{
    /**
     * @var ServicePackageFilter
     */
    protected $servicePackageFilter;

    public function __construct(ServicePackageFilter $servicePackageFilter)
    {
        $this->servicePackageFilter = $servicePackageFilter;
//        $this->middleware('permission:view_servicePackages');
//        $this->middleware('permission:create_servicePackages',['only'=>['create','store']]);
//        $this->middleware('permission:update_servicePackages',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_servicePackages',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_servicePackages')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $servicesPackages = ServicePackage::query();
        if(false == authIsSuperAdmin()) {
            $servicesPackages->where('branch_id', auth()->user()->branch_id);
        }
        if ($request->hasAny((new ServicePackage())->getFillable())) {
            $servicesPackages = $this->servicePackageFilter->filter($request);
        }
        return view('admin.services_packages.index', ['servicesPackages' => $servicesPackages->orderBy('id' ,'desc')->get()]);
    }

    public function create()
    {
        if (!auth()->user()->can('create_servicePackages')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.services_packages.create');
    }

    public function store(ServicesPackageRequest $request)
    {
        if (!auth()->user()->can('create_servicePackages')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if(false == authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        ServicePackage::create($data);
        return redirect()->to('admin/services_packages')
            ->with(['message' => __('words.service-package-created'), 'alert-type' => 'success']);
    }

    public function edit(ServicePackage $servicePackage)
    {
        if (!auth()->user()->can('update_servicePackages')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $servicesIds = unserialize($servicePackage->service_id);
        $qValues = unserialize($servicePackage->q);
        $servicesData = Service::whereIn('id', $servicesIds)->get()->toArray();
        $services = array_map(function($q, $service){
           return [
               'id' => $service['id'],
               'q' => $q,
               'total' => $q * $service['price'],
               'name' =>  $service['name_ar'],
               'hours' =>  $service['hours'],
               'minutes' =>  $service['minutes'],
               'price' =>  $service['price'],
               'totalHours' =>   $q * $service['hours'],
               'totalMin' =>  $q * $service['minutes'],
           ];
        }, $qValues, $servicesData );
        return view('admin.services_packages.edit', compact('servicePackage', 'services'));
    }

    public function update(UpdatePackageRequest $request, ServicePackage $servicePackage)
    {
        if (!auth()->user()->can('update_servicePackages')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if(false == authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        $servicePackage->update($data);
        return redirect()->to('admin/services_packages')
            ->with(['message' => __('words.service-package-updated'), 'alert-type' => 'success']);
    }

    public function destroy(ServicePackage $servicePackage)
    {
        if (!auth()->user()->can('delete_servicePackages')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $servicePackage->delete();
        return redirect()->to('admin/services_packages')
            ->with(['message' => __('words.data-archived-success'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_servicePackages')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids) && isset($request->archive)) {
            ServicePackage::whereIn('id', $request->ids)->delete();
            return back()->with(['message' => __('words.selected-row-archived'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->restore)) {
            ServicePackage::withTrashed()->whereIn('id', $request->ids)->restore();
            return back()->with(['message' => __('words.selected-row-restored'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->forcDelete)) {
            ServicePackage::withTrashed()->whereIn('id', $request->ids)->forceDelete();
            return back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        }

        return redirect()->to('admin/services_packages')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getServicesByServiceTypeId(Request $request)
    {
        if ($request->service_id == 'all') {
            if (authIsSuperAdmin()) {
                $services = Service::where('status', 1)->get();
            }
            if (false == authIsSuperAdmin()) {
                $services = Service::where('status', 1)->where('branch_id', auth()->user()->branch_id )->get();
            }
        }
        if ($request->service_id !== 'all') {
            if (authIsSuperAdmin()) {
                $services = Service::where('type_id', $request->service_id)->where('status', 1)->get();
            }
            if (false == authIsSuperAdmin()) {
                $services = Service::where('type_id', $request->service_id)->
                where('status', 1)->where('branch_id', auth()->user()->branch_id )->get();
            }
        }
        if ($request->branch_id) {
            $services = Service::where('branch_id', $request->branch_id)->where('status', 1)->get();
            $servicesTypes = ServiceType::where('branch_id', $request->branch_id)->where('status', 1)->get();
            $types = '';
            if ($servicesTypes->count() > 0) {
                foreach ($servicesTypes as $service) {
                    $types .= '<li class="nav-item hvr-shrink">
                           <a class="nav-link active active bg-danger text-white" href="#"
                           onclick="getServiceItemsById(' . $service->id . ')"
                            id="service_type">'.$service->name.'</a> </li>';
                }
            }
            if ($services->count() > 0) {
                $htmlServiceName= '';
                foreach ($services as $service) {
                    $htmlServiceName .= '<li class="nav-item">
                                    <a class="nav-link active" onclick="getServiceDetails(' . $service->id . ')" href="#"  id="service_details">
                                 ' . $service->name . '
                                 </a></li>';
                }
            }
            if ($services->count() == 0) {
                $htmlServiceName = '<h4>'.__('No Services').'</h4>';
            }
            if ($servicesTypes->count() == 0) {
                $types = '<h4>'.__('No Services Types').'</h4>';
            }
            return response()->json([
                                        'servicesNames' => $htmlServiceName,
                                        'types' => $types,
                                    ]);
        }
        if ($services->count() > 0) {
            $htmlServiceName= '';
            foreach ($services as $service) {
                $htmlServiceName .= '<li class="nav-item">
                                    <a class="nav-link active" onclick="getServiceDetails(' . $service->id . ')" href="#"  id="service_details">
                                 ' . $service->name . '
                                 </a></li>';
            }
            return response()->json([
                'servicesNames' => $htmlServiceName,
            ]);
        }
        if ($services->count() == 0) {
            return response()->json([
                'servicesNames' => '<h4>'.__('No Services').'</h4>',
            ]);
        }
    }

    public function getServiceDetails(Request $request)
    {
        $service = Service::find($request->service_id);
        $htmlServices = '<tr data-id='.$service->id.' id='.$service->id.'>
                        <input type="hidden" name="service_id[]" value='.$service->id.'>
                        <td>' . $service->name . '</td>' . '
                        <td id="servicePrice-'.$service->id.'">' . $service->price . '</td>
                        <td id="serviceH-'.$service->id.'" value='.$service->hours.'>' . $service->hours . '</td>
                         <td style="display: none"><input type="hidden" class="form-control" value="0"  id="totalH-'.$service->id.'"></td>
                        <td id="serviceM-'.$service->id.'" value='.$service->minutes.'>' . $service->minutes. '</td>
                        <td style="display: none"><input type="hidden" class="form-control" value="0"  id="totalM-'.$service->id.'"></td>
                        <td><input type="text" class="form-control" value="0" name="q[]" onkeyup="setServiceValues('.$service->id.')" id="quantity-'.$service->id.'"></td>
                        <td><input type="text" class="form-control" value="0" readonly id="total-'.$service->id.'"></td>
                        <td><i class="fa fa-trash fa-2x" onclick="removeServiceFromTable('.$service->id.')" style="color:#F44336; cursor: pointer"></i></td>
                        </tr>';
        return response()->json([
                'services' => $htmlServices
        ]);
    }

    function getServicesByBranch(Request $request)
    {
//        $htmlServices = '<tr data-id='.$service->id.' id='.$service->id.'>
//                        <input type="hidden" name="service_id[]" value='.$service->id.'>
//                        <td>' . $service->name . '</td>' . '
//                        <td id="servicePrice-'.$service->id.'">' . $service->price . '</td>
//                        <td id="serviceH-'.$service->id.'" value='.$service->hours.'>' . $service->hours . '</td>
//                         <td style="display: none"><input type="hidden" class="form-control" value="0"  id="totalH-'.$service->id.'"></td>
//                        <td id="serviceM-'.$service->id.'" value='.$service->minutes.'>' . $service->minutes. '</td>
//                        <td style="display: none"><input type="hidden" class="form-control" value="0"  id="totalM-'.$service->id.'"></td>
//                        <td><input type="text" class="form-control" value="0" name="q[]" onkeyup="setServiceValues('.$service->id.')" id="quantity-'.$service->id.'"></td>
//                        <td><input type="text" class="form-control" value="0" readonly id="total-'.$service->id.'"></td>
//                        <td><i class="fa fa-minus-circle fa-2x" onclick="removeServiceFromTable('.$service->id.')" style="color:#F44336; cursor: pointer"></i></td>
//                        </tr>';
//        return response()->json([
//                                    'services' => $htmlServices
//                                ]);
    }

    public function servicePackageArchive(Request $request): View
    {
        $servicesPackages = ServicePackage::onlyTrashed();
        if(false == authIsSuperAdmin()) {
            $servicesPackages->where('branch_id', auth()->user()->branch_id);
        }
        if ($request->hasAny((new ServicePackage())->getFillable())) {
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $servicesPackages = $servicesPackages->where('branch_id', $request->branch_id);
            }

            if ($request->has('name') && $request->name != '' && $request->name != null) {
                $servicesPackages = $servicesPackages->where('name_ar', 'like', '%' . $request->name . '%');
            }
        }
        return view('admin.services_packages.archive', ['servicesPackages' => $servicesPackages->orderBy('id' ,'desc')->get()]);

    }

    public function restoreDelete(int $id)
    {
        try {
            $servicePackage = ServicePackage::withTrashed()->findOrFail($id);
            $servicePackage->restore();
            return back()->with(['message' => __('words.data-restored-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-restore-date-from-archive'), 'alert-type' => 'error']);
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $servicePackage = ServicePackage::withTrashed()->findOrFail($id);
            $servicePackage->forceDelete();
            return back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-forced-deleted'), 'alert-type' => 'error']);
        }
    }
}
