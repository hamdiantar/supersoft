<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\City\CityRequest;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CitiesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_cities');
//        $this->middleware('permission:create_cities',['only'=>['create','store']]);
//        $this->middleware('permission:update_cities',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_cities',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        if (!auth()->user()->can('view_cities')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $cities = City::orderBy('id' ,'desc')->get();
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_cities')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.cities.create');
    }

    public function store(CityRequest $request)
    {
        if (!auth()->user()->can('create_cities')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        City::create($request->all());
        return redirect()->to('admin/cities')
            ->with(['message' => __('words.city-created'), 'alert-type' => 'success']);
    }

    public function edit(City $city)
    {
        if (!auth()->user()->can('update_cities')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.cities.edit', compact('city'));
    }

    public function update(CityRequest $request, City $city)
    {
        if (!auth()->user()->can('update_cities')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $city->update($request->all());
        return redirect()->to('admin/cities')
            ->with(['message' => __('words.city-updated'), 'alert-type' => 'success']);
    }

    public function destroy(City $city)
    {
        if ($city->areas()->exists()) {
            return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }
        if (!auth()->user()->can('delete_cities')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $city->forceDelete();
        return redirect()->to('admin/cities')
            ->with(['message' => __('words.city-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_cities')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
           $cities = City::whereIn('id', $request->ids)->get();
            foreach ($cities as $city) {
                if ($city->areas()->exists()) {
                    return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($cities as $city) {
                $city->forceDelete();
            }
            return redirect()->to('admin/cities')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/cities')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
