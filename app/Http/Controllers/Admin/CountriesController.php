<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Country\CountryRequest;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountriesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_countries');
//        $this->middleware('permission:create_countries',['only'=>['create','store']]);
//        $this->middleware('permission:update_countries',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_countries',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {

        if (!auth()->user()->can('view_countries')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $countries = Country::orderBy('id' ,'desc')->withoutTrashed()->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_countries')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.countries.create');
    }

    public function store(CountryRequest $request)
    {
        if (!auth()->user()->can('create_countries')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        Country::create($request->all());
        return redirect()->to('admin/countries')
            ->with(['message' => __('words.country-created'), 'alert-type' => 'success']);
    }

    public function edit(Country $country)
    {
        if (!auth()->user()->can('update_countries')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        if (!auth()->user()->can('update_countries')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $country->update($request->all());
        return redirect()->to('admin/countries')
            ->with(['message' => __('words.country-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Country $country)
    {
        if ($country->cities()->exists()) {
            return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }
        if (!auth()->user()->can('delete_countries')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $country->forceDelete();
        return redirect()->to('admin/countries')
            ->with(['message' => __('words.country-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_countries')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $countries = Country::whereIn('id', $request->ids)->get();
            foreach ($countries as $country) {
                if ($country->cities()->exists()) {
                    return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($countries as $country) {
                $country->forceDelete();
            }
            return redirect()->to('admin/countries')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/countries')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
