<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use App\Models\Part;
use App\Models\User;
use App\Models\Shift;
use App\Models\Branch;
use App\Models\Service;
use App\Models\Setting;

use App\Models\Customer;
use App\Models\Quotation;
use App\Models\SparePart;
use App\Models\TaxesFees;
use App\Models\ServiceType;
use App\Models\ServicePackage;
use App\Models\PurchaseInvoice;
use App\Models\CustomerCategory;
use App\Models\QuotationTypeItem;
use App\Services\NotificationServices;
use Illuminate\Support\Facades\DB;
use App\Services\QuotationServices;
use App\Http\Controllers\Controller;
use App\Models\QuotationWinchRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\SampleSalesInvoiceServices;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Quotations;
use App\Http\Requests\Admin\Quotation\CreateQuotationRequest;
use App\Http\Requests\Admin\Quotation\UpdateQuotationRequest;
use App\Http\Requests\Admin\salesInvoice\CreateSalesInvoiceRequest;
use Illuminate\View\View;
use Illuminate\Http\Request;
use foo\func;

class QuotationsController extends Controller
{
    use QuotationServices, SampleSalesInvoiceServices, NotificationServices;

    public function __construct()
    {
//        $this->middleware('permission:view_quotations');
//        $this->middleware('permission:create_quotations',['only'=>['create','store']]);
//        $this->middleware('permission:update_quotations',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_quotations',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('view_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $quotations = Quotation::where('status', 'approved')->with('customer');

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method :'asc';
            if (!in_array($sort_method ,['asc' ,'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'quotation-number' => 'quotation_number',
                'customer-name' => 'customer_id',
                'customer-phone' => 'customer_id',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $quotations = $quotations->orderBy($sort_fields[$sort_by] ,$sort_method);
        } else {
            $quotations = $quotations->orderBy('id', 'DESC');
        }

        if ($request->has('branch_id') && $request['branch_id'] != '')
            $quotations->where('branch_id', $request['branch_id']);

        if ($request->has('customer_id') && $request['customer_id'] != '')
            $quotations->where('customer_id', $request['customer_id']);

        if ($request->has('quotation_number') && $request['quotation_number'] != '')
            $quotations->where('id', $request['quotation_number']);

        if ($request->has('created_by') && $request['created_by'] != '')
            $quotations->where('created_by', $request['created_by']);

        if ($request->has('date_from') && $request['date_from'] != '')
            $quotations->where('date', '>=', $request['date_from']);

        if ($request->has('date_to') && $request['date_to'] != '')
            $quotations->where('date', '<=', $request['date_to']);

        if ($request->has('Chassis_number') && $request['Chassis_number'] != '')
            $quotations->where('customer_id', $request['Chassis_number']);

        if ($request->has('plate_number') && $request['plate_number'] != '')
            $quotations->where('customer_id', $request['plate_number']);

        if ($request->has('shift') && $request['shift'] != '')
            $quotations->whereHas('user', function ($q) use ($request) {

                $q->whereHas('shifts', function ($sh) use ($request) {
                    $sh->where('shift_id', $request['shift']);
                });

            });

        if ($request->has('type') && $request['type'] != '')
            $quotations->whereHas('types', function ($q) use ($request) {
                $q->where('type', $request['type']);
            });

        if ($request->has('customer_phone') && $request['customer_phone'] != '')
            $quotations->whereHas('customer', function ($q) use ($request) {
                $q->where('id', $request['customer_phone']);
            });
        if ($request->has('key')) {
            $key = $request->key;
            $quotations->where(function ($q) use ($key) {
                $q->where('quotation_number' ,'like' ,"%$key%")
                ->orWhere('created_at' ,'like' ,"%$key%")
                ->orWhere('updated_at' ,'like' ,"%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker ,['print' ,'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Quotations($quotations ,$visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $quotations = $quotations->paginate($rows)->appends(request()->query());

        $quotations_data = filterSetting() ? Quotation::get() : null;
        $branches = filterSetting() ? Branch::all()->pluck('name', 'id') : null;
        $customers = filterSetting() ? Customer::get() : null;
        $users = filterSetting() ? User::orderBy('id')->branch()->get()->pluck('name', 'id') : null;
        $cars = filterSetting() ? Car::get() : null;
        $shifts = filterSetting() ? Shift::all()->pluck('name', 'id') : null;

        return view('admin.quotations.index',
            compact('quotations', 'branches', 'quotations_data', 'customers', 'users', 'cars', 'shifts'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('create_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $branches = Branch::where('status', 1)->get()->pluck('name', 'id');

        $customers = Customer::where('status', 1);

        $taxes = TaxesFees::where('active_offers', 1);

        $customersGroups = CustomerCategory::where('status', 1);

        $setting = Setting::where('branch_id', $branch_id)->first();

        $branch_lat = $setting ? $setting->lat : '';
        $branch_long = $setting ? $setting->long : '';
        $kilo_meter_price = $setting ? $setting->kilo_meter_price : 0;


        if ($request->has('branch_id')) {
            $customers->where('branch_id', $request['branch_id']);
            $taxes->where('branch_id', $request['branch_id']);
            $customersGroups->where('branch_id', $request['branch_id']);
        }

        $customers = $customers->get();
        $taxes = $taxes->get();
        $customersGroups = $customersGroups->get();

        return view('admin.quotations.create',
            compact('branches', 'customers', 'taxes', 'customersGroups', 'branch_lat', 'branch_long', 'kilo_meter_price'));
    }

    public function store(CreateQuotationRequest $request)
    {
        if (!auth()->user()->can('create_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!$request->has('parts_box') && !$request->has('services_box') && !$request->has('packages_box') && !$request->has('winch_box')) {
            return redirect()->back()->with(['message' => __('words.sorry please select one type at least'), 'alert-type' => 'error']);
        }

        $auth = auth()->user();

        $branch_id = $auth->branch_id;

        $setting = Setting::where('branch_id', $branch_id)->first();

        if (!$setting) {
            return redirect()->back()->with(['message' => __('sorry, please prepare branch setting'), 'alert-type' => 'error']);
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            $branch_id = auth()->user()->branch_id;

            if (authIsSuperAdmin()) {
                $branch_id = $request['branch_id'];
            }

            $quotation_data = $this->prepareQuotationData($data, $branch_id, $setting);

            $quotation_data['created_by'] = auth()->id();
            $quotation_data['branch_id'] = $branch_id;

            $last_quotation = Quotation::where('branch_id', $quotation_data['branch_id'])->latest('created_at')->first();

            $quotation_data['quotation_number'] = $last_quotation ? $last_quotation->quotation_number + 1 : 1;

            $quotation = Quotation::create($quotation_data);

//           parts data
            if ($request->has('parts_box')) {

                $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($branch_id);

                $quotation_type = $this->createQuotationType($quotation->id, 'Part');

                foreach ($request['part_ids'] as $index => $part_id) {

                    $part = Part::find($part_id);

                    $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $branch_id,
                        $data['purchase_invoice_id'][$index], $part_id);

                    if (!$purchase_invoice) {
                        return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                    }

                    $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

                    if ($part->quantity < $request['sold_qty'][$index]) {
                        return redirect()->back()->with(['message' => __('words.unavailable-qnt')]);
                    }

//                  REPEAT PART ITEM
                    if ($invoice_item->purchase_qty < $data['sold_qty'][$index]) {

                        $next_purchase_invoices = $this->getNextPurchaseInvoices($setting_sell_From_invoice_status, $branch_id,
                            $purchase_invoice->id, $part_id);

                        $this->repeatPartItem($data, $next_purchase_invoices, $index, $part_id, $quotation, $quotation_type);

                        continue;
                    }

                    $item_data = $this->prepareParts($request, $index, $part_id);

                    $item_data['purchase_invoice_id'] = $purchase_invoice->id;
                    $item_data['quotation_id'] = $quotation->id;
                    $item_data['quotation_type_id'] = $quotation_type->id;

                    $part_item = QuotationTypeItem::create($item_data);
                }
            }

//           Service data
            if ($request->has('services_box')) {

                $quotation_type = $this->createQuotationType($quotation->id, 'Service');

                foreach ($request['service_ids'] as $index => $service_id) {

                    $item_data = $this->prepareServices($request, $index, $service_id);

                    $item_data['quotation_id'] = $quotation->id;
                    $item_data['quotation_type_id'] = $quotation_type->id;

                    $service_item = QuotationTypeItem::create($item_data);
                }
            }

//           Packages data
            if ($request->has('packages_box')) {
                $quotation_type = $this->createQuotationType($quotation->id, 'Package');
                foreach ($request['package_ids'] as $index => $package_id) {

                    $item_data = $this->preparePackages($request, $index, $package_id);

                    $item_data['quotation_id'] = $quotation->id;
                    $item_data['quotation_type_id'] = $quotation_type->id;

                    $package_item = QuotationTypeItem::create($item_data);
                }
            }

//           Winch data
            if ($request->has('winch_box')) {

                $quotation_type = $this->createQuotationType($quotation->id, 'Winch');

                $item_data = $this->prepareWinchRequests($request, $setting);

                $item_data['quotation_type_id'] = $quotation_type->id;

                $winch_request = QuotationWinchRequest::create($item_data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        $url = route('admin:quotations.index');

        if ($request['save_type'] == 'save_and_print') {

            $url = route('admin:quotations.index', ['print_type' => 'print', 'quotation' => $quotation->id]);
//            $url = 'admin/work-cards/?print_type=print&invoice='.$invoice->id;
        }

        return redirect($url)->with(['message' => __('words.quotation-created'), 'alert-type' => 'success']);
    }

    public function show(Request $request)
    {
        if (!auth()->user()->can('view_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $quotation = Quotation::find($request->quotationId);

        $taxes = TaxesFees::where('active_offers', 1)->where('branch_id', $quotation->branch_id)->get();

        $totalTax = TaxesFees::where('active_offers', 1)->sum('value');

        $partType = $quotation->types()->where('type', 'Part')->first();

        $quotationServiceType = $quotation->types()->where('type', 'Service')->first();

        $packageType = $quotation->types()->where('type', 'Package')->first();

        $quotationWinchType = $quotation->types()->where('type', 'Winch')->first();

        $setting = Setting::where('branch_id', auth()->user()->branch_id)->where('quotation_terms_status', 1)->first();

        $quotationData = view('admin.quotations.show',
            compact('quotation', 'partType', 'quotationServiceType', 'packageType', 'setting',
                'taxes', 'totalTax','quotationWinchType'))->render();

        return response()->json(['quotation' => $quotationData]);
    }

    public function edit(Quotation $quotation)
    {
        if (!auth()->user()->can('update_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::where('status', 1)->get()->pluck('name', 'id');

        $customers = Customer::where('status', 1)->where('branch_id', $quotation->branch_id)->get();

        $customersGroups = CustomerCategory::where('status', 1)->where('branch_id', $quotation->branch_id)->get();

        $taxes = TaxesFees::where('active_offers', 1)->where('branch_id', $quotation->branch_id)->get();

        $sparPartsTypes = SparePart::where('status', 1)->where('branch_id', $quotation->branch_id)->get();

        $parts = Part::where('status', 1)->whereHas('store', function ($q) use ($quotation) {
            $q->where('branch_id', $quotation->branch_id);
        })->get();

        $servicesTypes = ServiceType::where('status', 1)->where('branch_id', $quotation->branch_id)->get();

        $services = Service::where('status', 1)->where('branch_id', $quotation->branch_id)->get();

        $packages = ServicePackage::where('branch_id', $quotation->branch_id)->get();


        $partType = $quotation->types()->where('type', 'Part')->first();
        $quotationServiceType = $quotation->types()->where('type', 'Service')->first();
        $packageType = $quotation->types()->where('type', 'Package')->first();

        $winchType = $quotation->types()->where('type', 'Winch')->first();

        $setting = Setting::where('branch_id', $quotation->branch_id)->first();

        $branch_lat = $setting ? $setting->lat : '';
        $branch_long = $setting ? $setting->long : '';
        $kilo_meter_price = $setting ? $setting->kilo_meter_price : 0;

        return view('admin.quotations.edit',
            compact('quotation', 'branches', 'customers', 'taxes', 'partType', 'quotationServiceType',
                'packageType', 'sparPartsTypes', 'parts', 'servicesTypes', 'services', 'packages', 'customersGroups',
                'branch_lat', 'branch_long', 'kilo_meter_price', 'winchType'));
    }

    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        if (!auth()->user()->can('update_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (!$request->has('parts_box') && !$request->has('services_box') && !$request->has('packages_box') && !$request->has('winch_box')) {
            return redirect()->back()->with(['message' => __('words.sorry please select one type at least'), 'alert-type' => 'error']);
        }

        $auth = auth()->user();

        $branch_id = $auth->branch_id;

        $setting = Setting::where('branch_id', $branch_id)->first();

        if (!$setting) {

            return redirect()->back()->with(['message' => __('sorry, branch setting required'), 'alert-type' => 'error']);
        }

        try {
            DB::beginTransaction();

            $this->reset($quotation, 'update');

            $branch_id = auth()->user()->branch_id;

            if (authIsSuperAdmin()) {
                $branch_id = $quotation->branch_id;
            }

            $data = $request->all();

            $quotation_data = $this->prepareQuotationData($data, $branch_id, $setting);

            $quotation_data['branch_id'] = $branch_id;

            $quotation->update($quotation_data);

//           parts data
            if ($request->has('parts_box')) {

                $setting_sell_From_invoice_status = $this->settingSellFromInvoiceStatus($branch_id);

                $quotation_type = $this->createQuotationType($quotation->id, 'Part');

                foreach ($request['part_ids'] as $index => $part_id) {

                    $part = Part::find($part_id);

                    $purchase_invoice = $this->getPurchaseInvoice($setting_sell_From_invoice_status, $branch_id,
                        $data['purchase_invoice_id'][$index], $part_id);

                    if (!$purchase_invoice) {
                        return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                    }

                    if (!$purchase_invoice) {
                        return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                    }

                    $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

                    if ($part->quantity < $request['sold_qty'][$index]) {
                        return redirect()->back()->with(['message' => __('words.unavailable-qnt')]);
                    }

//                  REPEAT PART ITEM
                    if ($invoice_item->purchase_qty < $data['sold_qty'][$index]) {

                        $next_purchase_invoices = $this->getNextPurchaseInvoices($setting_sell_From_invoice_status, $branch_id,
                            $purchase_invoice->id, $part_id);

                        $this->repeatPartItem($data, $next_purchase_invoices, $index, $part_id, $quotation, $quotation_type);

                        continue;
                    }

                    $item_data = $this->prepareParts($request, $index, $part_id);

                    $item_data['purchase_invoice_id'] = $purchase_invoice->id;
                    $item_data['quotation_id'] = $quotation->id;
                    $item_data['quotation_type_id'] = $quotation_type->id;

                    $part_item = QuotationTypeItem::create($item_data);
                }
            }

//           Service data
            if ($request->has('services_box')) {

                $quotation_type = $this->createQuotationType($quotation->id, 'Service');

                foreach ($request['service_ids'] as $index => $service_id) {

                    $item_data = $this->prepareServices($request, $index, $service_id);

                    $item_data['quotation_id'] = $quotation->id;
                    $item_data['quotation_type_id'] = $quotation_type->id;

                    $service_item = QuotationTypeItem::create($item_data);
                }
            }

//           Packages data
            if ($request->has('packages_box')) {
                $quotation_type = $this->createQuotationType($quotation->id, 'Package');
                foreach ($request['package_ids'] as $index => $package_id) {

                    $item_data = $this->preparePackages($request, $index, $package_id);

                    $item_data['quotation_id'] = $quotation->id;
                    $item_data['quotation_type_id'] = $quotation_type->id;

                    $package_item = QuotationTypeItem::create($item_data);
                }
            }

//           Winch data
            if ($request->has('winch_box')) {

                $quotation_type = $this->createQuotationType($quotation->id, 'Winch');

                $item_data = $this->prepareWinchRequests($request, $setting);

                $item_data['quotation_type_id'] = $quotation_type->id;

                $winch_request = QuotationWinchRequest::create($item_data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.back-support'), 'alert-type' => 'error']);
        }

        $url = route('admin:quotations.index');

        if ($request['save_type'] == 'save_and_print') {

            $url = route('admin:quotations.index', ['print_type' => 'print', 'quotation' => $quotation->id]);
        }


        return redirect($url)->with(['message' => __('words.quotation-updated'), 'alert-type' => 'success']);
    }

    public function getParts(Request $request)
    {

        try {

            $sparPartsTypes = SparePart::where('status', 1);

            $parts = Part::where('status', 1);

            if (authIsSuperAdmin()) {

                $sparPartsTypes->where('branch_id', $request['branch_id']);

                $parts->whereHas('store', function ($q) use ($request) {
                    $q->where('branch_id', $request['branch_id']);
                });
            }

            $sparPartsTypes = $sparPartsTypes->get();
            $parts = $parts->get();

        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.quotations.parts.ajax_parts', compact('sparPartsTypes', 'parts'));
    }

    public function partDetails(Request $request)
    {
        $items_count = $request['items_count'] + 1;
        $part = Part::findOrFail($request['id']);

        return view('admin.quotations.parts.part_details', compact('part', 'items_count'));
    }

    public function purchaseInvoiceData(Request $request)
    {

        $invoice = PurchaseInvoice::findOrFail($request['invoice_id']);

        $invoice_item = $invoice->items()->where('part_id', $request['part_id'])->first();

        $part = Part::findOrFail($request['part_id']);

        $index = $request['index'];

        return view('admin.quotations.parts.purchase_invoice_data',
            compact('invoice_item', 'part', 'invoice','index'));
    }

    public function getServices(Request $request)
    {

        try {

            $servicesTypes = ServiceType::where('status', 1);
            $services = Service::where('status', 1);

            if (authIsSuperAdmin()) {

                $servicesTypes->where('branch_id', $request['branch_id']);
                $services->where('branch_id', $request['branch_id']);
            }

            $servicesTypes = $servicesTypes->get();
            $services = $services->get();


        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.quotations.services.ajax_services', compact('servicesTypes', 'services'));
    }

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

        return view('admin.quotations.services.ajax_services_by_type', compact('services'));
    }

    public function getServicesByTypeInFooter(Request $request)
    {

        $serviceType = ServiceType::findOrFail($request['service_type_id']);
        $data['services'] = $serviceType->services()->where('status', 1)->get()->pluck('name', 'id');
        return $data;
    }

    public function getPartsByTypeInFooter(Request $request)
    {

        $partType = SparePart::findOrFail($request['part_type_id']);
        $data['parts'] = $partType->parts()->where('status', 1)->get();
        return $data;
    }

    public function getServicesDetails(Request $request)
    {

        try {
            $items_count = $request['items_count'] + 1;
            $service = Service::findOrFail($request['service_id']);

        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.quotations.services.service_details', compact('service', 'items_count'));
    }

    public function getPackages(Request $request)
    {

        try {

            $packages = ServicePackage::orderBy('id', 'DESC');

            if (authIsSuperAdmin()) {
                $packages->where('branch_id', $request['branch_id']);
            }

            $packages = $packages->get();

        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.quotations.packages.ajax_packages', compact('packages'));
    }

    public function packageDetails(Request $request)
    {

        try {
            $items_count = $request['items_count'] + 1;
            $package = ServicePackage::findOrFail($request['package_id']);

        } catch (\Exception $e) {
            return response()->json(__('words.something-wrong'), 400);
        }

        return view('admin.quotations.packages.package_details', compact('package', 'items_count'));

    }

    public function packageInfo(Request $request)
    {

        $servicePackage = ServicePackage::findOrFail($request['id']);

        $servicesIds = unserialize($servicePackage->service_id);
        $qValues = unserialize($servicePackage->q);
        $servicesData = Service::whereIn('id', $servicesIds)->get()->toArray();

        $services = array_map(function ($q, $service) {
            return [
                'id' => $service['id'],
                'q' => $q,
                'total' => $q * $service['price'],
                'name' => $service['name_ar'],
                'hours' => $service['hours'],
                'minutes' => $service['minutes'],
                'price' => $service['price'],
                'totalHours' => $q * $service['hours'],
                'totalMin' => $q * $service['minutes'],
            ];
        }, $qValues, $servicesData);

        return view('admin.quotations.packages.package_info', compact('servicePackage', 'services'));
    }

    public function destroy(Quotation $quotation)
    {

        if (!auth()->user()->can('delete_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $this->reset($quotation, 'delete');
            $quotation->delete();

        } catch (\Exception $e) {
//            dd($e->getMessage());
            return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
        }

        try {

            $this->sendNotification('quotation_request_status','customer',
                [
                    'quotation' => $quotation,
                    'message'=> 'Your quotation request is deleted'
                ]);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('request deleted successfully'), 'alert-type' => 'success']);
        }

        return redirect()->back()->with(['message' => __('words.quotation-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_quotations')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            if (isset($request->ids) && is_array($request->ids)) {

                foreach ($request['ids'] as $id) {

                    $quotation = Quotation::find($id);
                    $this->reset($quotation, 'delete');
                    $quotation->delete();
                }

                return redirect()->back()
                    ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('words.try-again'), 'alert-type' => 'error']);
        }

        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getDistance(Request $request) {

        try {

            $auth = auth()->user();

            $branch_id = $auth->branch_id;

            $setting = Setting::where('branch_id', $branch_id)->first();

            if (!$setting) {
                return response()->json( 'sorry please update setting', 400);
            }

            $branch_lat  = $setting->lat ;
            $branch_long = $setting->long ;
            $kilo_meter_price = $setting->kilo_meter_price;

            $distance = $this->calculateDistanceBetweenTwoAddresses($branch_lat,$branch_long,$request['lat'],$request['long'],'3959');

            $total = round($kilo_meter_price * $distance, 2);

        }catch (\Exception $e){

            return response()->json( 'sorry something went wrong', 400);
        }

        return response()->json(['total'=> $total, 'distance'=> $distance], 200);
    }
}
