<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmployeeData;
use App\Models\Part;
use App\Models\PurchaseInvoice;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\ServiceType;
use App\Models\SparePart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CardInvoiceFrontEndSampleController extends Controller
{
//  PARTS
    public function getPartsByType(Request $request)
    {
        if ($request->spare_part_id === 'all') {
            if (authIsSuperAdmin()) {
                $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($request) {
                    $q->where('branch_id', $request['branch_id']);
                })->get();
            }
            if (false == authIsSuperAdmin()) {
                $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($request) {
                    $q->where('branch_id', auth()->user()->branch_id);
                })->get();
            }
        } else {
            $parts = Part::where('spare_part_type_id', $request->spare_part_id)->where('status', 1)->get();
        }

        if ($parts->count() > 0) {

            $htmlParts = view('admin.work_cards.invoices.sample.parts.parts_in_card', compact( 'parts'))->render();

            return response()->json([
                'parts' => $htmlParts,
            ]);
        }

        if ($parts->count() == 0) {

            return response()->json(['parts' => '<h4>' . __('words.No Data Available') . '</h4>',]);
        }
    }

    public function getPartsByTypeInFooter(Request $request)
    {
        $partType = SparePart::findOrFail($request['part_type_id']);
        $data['parts'] = $partType->parts()->where('status', 1)->get();
        return $data;
    }

    public function partDetails(Request $request)
    {

        $items_count = $request['items_count'] + 1;
        $part = Part::findOrFail($request['id']);

        return view('admin.work_cards.invoices.sample.parts.part_details',
            compact('part', 'items_count'));
    }

    public function purchaseInvoiceData(Request $request)
    {

        $index = $request['index'];

        $invoice = PurchaseInvoice::findOrFail($request['invoice_id']);

        $invoice_item = $invoice->items()->where('part_id', $request['part_id'])->first();

        $part = Part::findOrFail($request['part_id']);

        return view('admin.work_cards.invoices.sample.parts.purchase_invoice_data',
            compact('invoice_item', 'part', 'invoice', 'index'));
    }

//  SERVICES
    public function getServicesByType(Request $request)
    {
        try {

            if ($request['service_type_id'] == 'all') {

                if (authIsSuperAdmin()) {
                    $services = Service::where('status', 1)->where('branch_id', $request['branch_id'])->get();

                } else {

                    $services = Service::where('status', 1)->get();
                }

            } else {

                $serviceType = ServiceType::findOrFail($request['service_type_id']);
                $services = $serviceType->services()->where('status', 1)->get();
            }

        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.work_cards.invoices.sample.services.ajax_services_by_type', compact('services'));
    }

    public function getServicesByTypeInFooter(Request $request)
    {

        $serviceType = ServiceType::findOrFail($request['service_type_id']);
        $data['services'] = $serviceType->services()->where('status', 1)->get()->pluck('name', 'id');
        return $data;
    }

    public function getServicesDetails(Request $request)
    {

        try {

            $items_count = $request['items_count'] + 1;

            $service = Service::findOrFail($request['service_id']);

            $employees = EmployeeData::whereHas('employeeSetting', function ($q) {
                $q->where('service_status', 1);
            })->get();

        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.work_cards.invoices.sample.services.service_details',
            compact('service', 'items_count', 'employees'));
    }

//  PACKAGES
    public function packageDetails(Request $request)
    {

        try {
            $items_count = $request['items_count'] + 1;
            $package = ServicePackage::findOrFail($request['package_id']);
            $employees = EmployeeData::whereHas('employeeSetting', function ($q) {
                $q->where('service_status', 1);
            })->get();

        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.work_cards.invoices.sample.packages.package_details',
            compact('package', 'items_count', 'employees'));

    }
}
