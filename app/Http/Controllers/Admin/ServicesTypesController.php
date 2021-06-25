<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServicesTypes\CreateRequest;
use App\Http\Requests\Admin\ServicesTypes\UpdateServiceTypeRequest;
use App\Models\Branch;
use App\Models\ServiceType;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServicesTypesController extends Controller
{
    /**
     * @var string
     */
    public static $viewPath = 'admin.services-types.';

    public function __construct()
    {
//        $this->middleware('permission:view_services_types');
//        $this->middleware('permission:create_services_types',['only'=>['create','store']]);
//        $this->middleware('permission:update_services_types',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_services_types',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $types = ServiceType::orderBy('id', 'DESC');

//        if(!authIsSuperAdmin())
//            $types->where('branch_id', auth()->user()->branch_id);

        if ($request->has('name') && $request['name'] != '') {
            $types->where('id', $request['name']);
        }

        if ($request->has('branch_id') && $request['branch_id'] != '') {
            $types->where('branch_id', $request['branch_id']);
        }

        if ($request->has('active') && $request['active'] != '') {
            $types->where('status', 1);
        }

        if ($request->has('inactive') && $request['inactive'] != '') {
            $types->where('status', 0);
        }

        $types = $types->get();

        $branches = filterSetting() ? Branch::all()->pluck('name', 'id') : null;
        $services_types = filterSetting() ? ServiceType::all()->pluck('name', 'id') : null;

        return view('admin.services-types.index', compact('types', 'services_types', 'branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name', 'id');
        return view('admin.services-types.create', compact('branches'));
    }

    public function store(CreateRequest $request)
    {
        if (!auth()->user()->can('create_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $data = $request->validated();

            $data['status'] = 0;

            if ($request->has('status')) {
                $data['status'] = 1;
            }

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            ServiceType::create($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:services-types.index'))
            ->with(['message' => __('words.service-types-created'), 'alert-type' => 'success']);
    }

    public function show(ServiceType $services_type)
    {
        if (!auth()->user()->can('show_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.services-types.show', compact('services_type'));
    }

    public function edit(ServiceType $services_type)
    {
        if (!auth()->user()->can('update_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name', 'id');
        return view('admin.services-types.edit', compact('services_type', 'branches'));
    }

    public function update(UpdateServiceTypeRequest $request, ServiceType $services_type)
    {
        if (!auth()->user()->can('update_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            $data = $request->validated();

            $data['status'] = 0;

            if ($request->has('status')) {
                $data['status'] = 1;
            }

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $services_type->update($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:services-types.index'))
            ->with(['message' => __('words.service-types-updated'), 'alert-type' => 'success']);
    }

    public function serviceTypeArchive(): View
    {
        $branches = filterSetting() ? Branch::all()->pluck('name', 'id') : null;
        $types = ServiceType::onlyTrashed()->get();
        $services_types = filterSetting() ? ServiceType::all()->pluck('name', 'id') : null;
        return view(self::$viewPath . 'archive', compact('types', 'branches', 'services_types'));
    }

    public function destroy(ServiceType $services_type)
    {
        if (!auth()->user()->can('delete_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $services_type->delete();
        return redirect(route('admin:services-types.index'))
            ->with(['message' => __('words.data-archived-success'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_services_types')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        if (isset($request->ids) && isset($request->archive)) {
            ServiceType::whereIn('id', $request->ids)->delete();
            return back()->with(['message' => __('words.selected-row-archived'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->restore)) {
            ServiceType::withTrashed()->whereIn('id', $request->ids)->restore();
            return back()->with(['message' => __('words.selected-row-restored'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->forcDelete)) {
            ServiceType::withTrashed()->whereIn('id', $request->ids)->forceDelete();
            return back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:services-types.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function restoreDelete(int $id)
    {
        try {
            $services_type = ServiceType::withTrashed()->findOrFail($id);
            $services_type->restore();
            return back()->with(['message' => __('words.data-restored-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-restore-date-from-archive'), 'alert-type' => 'error']);
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $services_type = ServiceType::withTrashed()->findOrFail($id);
            $services_type->forceDelete();
            return back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-forced-deleted'), 'alert-type' => 'error']);
        }
    }
}
