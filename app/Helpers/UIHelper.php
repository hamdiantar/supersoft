<?php

use App\Model\PurchaseReturn;
use App\Models\PurchaseInvoice;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplierGroup;
use App\Services\PurchaseRequestService;
use App\Services\SuppliersGroupsTreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Services\PartTypeTreeService;

if (!function_exists('drawSelect2ByAjax')) {
    function drawSelect2ByAjax(
        $inputName = '',
        $model,
        $selectedColumns = '*',
        $searchFields = '',
        $placeholder = '' ,
        $inputValue = '',
        $limit=10 ,
        $additionalClasses = '',
        $searchUrl = 'admin/autocomplete'
    ) {
        // dd($inputValue);
        /*
        to make select 2 input is fully dynamic and select specific input  and search in specific inputs you can change data-attributes for example :

        1- $selectColumns="name"   ### to select only name column
        2- $limit="10" ### to get only 10 record
        3- $model="user" ### to get data from user model
        4- $searchFields="name" ### to make specific search in oly name column  if you want to search in more than one column you can add value like this "name,email,phone"
        5- $placeholder=" you can write placeholder you want here "
        6- you can also change url to another one by adding this option 'data-url="your_new_url"'

        */

        $input = '<select id="'.$inputName.'"  class="select2_by_ajax form-control '.$additionalClasses.'"
            data-selectedColumns="'.$selectedColumns.'" data-limit="'.$limit.'" data-model="'.$model.'" data-searchFields="'.$searchFields.'" data-value="'.$inputValue.'" data-placeholder="'.$placeholder.'" name="'.$inputName.'" data-url="'.url($searchUrl).'">

</select>';

        return $input;
    }
}

if (!function_exists('input_error')) {
    function input_error($errors, $input)
    {
        if ($errors->has($input)) {
            echo '<label id="' . $input . '" class="invalid-feedback" style="color:red; display:block !important" for="' . $input . '">' . $errors->first($input) . '</label>';
        }
    }
}


function uploadImage($image, string $directory)

{
    $time = time();
    $random = rand(1, 100000);
    $divide = $time / $random;
    $encryption = md5($divide);
    $randomName = substr($encryption, 0, 20);

    $fileName = $randomName . '.' . $image->getClientOriginalExtension();

    $img = Image::make($image->getRealPath());
    $img->stream();
    Storage::disk('local')->put("public/images/{$directory}/" . $fileName, $img, 'public');
    return $fileName;
}

function checkIfShiftActive()
{
    $branch_id = auth()->user()->branch_id;
    $branch = \DB::table('branches')->where('id',$branch_id)->first();
    if ($branch) {
        return $branch->shift_is_active;
    }
}
if (!function_exists('authIsSuperAdmin')) {
    function authIsSuperAdmin()
    {
        if (!auth()->check())
            return false;

        $auth = auth()->user();

        if ($auth->super_admin) {
            return true;
        }
        return false;
    }
}

if(!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber()
    {
        $branch_id = auth()->user()->branch_id;
        $dataMax = PurchaseInvoice::where('branch_id', $branch_id)->whereNull('deleted_at')->max('id');

        if ($dataMax === null) {
            $invoiceNumber = $branch_id.'-'. "00001";
        } else {
            $MaksID = $dataMax;
            $MaksID++;
            if ($MaksID < 10) $invoiceNumber = $branch_id.'-' . "0000" . $MaksID;
            else if ($MaksID < 100) $invoiceNumber = $branch_id.'-' . "000" . $MaksID;
            else if ($MaksID < 1000) $invoiceNumber = $branch_id.'-' . "00" . $MaksID;
            else if ($MaksID < 10000) $invoiceNumber = $branch_id.'-' . "0" . $MaksID;
            else $invoiceNumber = $MaksID;
        }
        return $invoiceNumber;
    }
}

if(!function_exists('generateReturnInvoiceNumber')) {
    function generateReturnInvoiceNumber()
    {
        $branch_id = auth()->user()->branch_id;
        $dataMax = PurchaseReturn::where('branch_id', $branch_id)->whereNull('deleted_at')->max('id');

        if ($dataMax === '') {
            $invoiceNumber = $branch_id.'-'. "00001";
        } else {
            $MaksID = $dataMax;
            $MaksID++;
            if ($MaksID < 10) $invoiceNumber = $branch_id.'-' . "0000" . $MaksID;
            else if ($MaksID < 100) $invoiceNumber = $branch_id.'-' . "000" . $MaksID;
            else if ($MaksID < 1000) $invoiceNumber = $branch_id.'-' . "00" . $MaksID;
            else if ($MaksID < 10000) $invoiceNumber = $branch_id.'-' . "0" . $MaksID;
            else $invoiceNumber = $MaksID;
        }
        return $invoiceNumber;
    }
}

if(!function_exists('generateNumber')) {
    function generateNumber($branch_id, $model, $number_column)
    {
        $data = $model::where('branch_id', $branch_id)->latest()->first();
        $number = $data? $data->$number_column + 1 : 1;
        return $number;
    }
}


if (!function_exists('customCeil')) {
    function customCeil($number)
    {
        return round($number, 2);
    }
}

if (!function_exists('getIdsOfExpenseTypesNotDeletable')) {
    function getIdsOfExpenseTypesNotDeletable()
    {
        return [1232, 3435, 23232, 34343];
    }
}

if (!function_exists('getIdsOfRevnuesTypesNotDeletable')) {
    function getIdsOfRevnuesTypesNotDeletable()
    {
        return [122222222, 122222223, 122222453];
    }
}

if (!function_exists('invoiceSetting')) {
    function invoiceSetting()
    {
        $status = true;

        $setting = \App\Models\Setting::where('branch_id', auth()->user()->branch_id)->first();

        if($setting){

            $status = $setting->invoice_setting ? true : false;
        }

        return $status;
    }
}

if (!function_exists('filterSetting')) {
    function filterSetting()
    {
        $status = true;

        $setting = \App\Models\Setting::where('branch_id',auth()->user()->branch_id)->first();

        if($setting){

            $status = $setting->filter_setting ? true : false;
        }

        return $status;
    }
}

if (!function_exists('user_can_access_accounting_module')) {
    function user_can_access_accounting_module($url ,$module = NULL ,$action = NULL) {
        if ($url) {
            $module = config('accounting-module-urls')[$url];
            $action = config('accounting-module-functions')[$url];
        }
        if ($module && $action) {
            $conditions = [
                'user_role_id' => auth()->user()->role_id,
                'function' => $action,
                'module' => $module
            ];
            if ($action == 'add-edit') {
                $conditions['function'] = 'add';
                $can_add = true;
                $conditions['function'] = 'edit';
                $can_edit = true;
                return $can_add || $can_edit;
            } else {
                if ($action == 'view') unset($conditions['function']);
                $can = true;
            }
            return $can ? true : false;
        }
        return false;
    }
}

if (!function_exists('recentNotifications')) {
    function recentNotifications()
    {
        $auth = auth()->user();

        $notifications = $auth->unreadNotifications()->orderBy('id','desc')->take(10)->get();

        return $notifications;
    }
};

if (!function_exists('countNotifications')) {
    function countNotifications()
    {
        $auth = auth()->user();

        $count = $auth->notifications()->where('read_at', null)->count();

        return $count;
    }
};

if (!function_exists('recentNotificationsForCustomer')) {
    function recentNotificationsForCustomer()
    {
        $auth = auth()->guard('customer')->user();

        $notifications = $auth->unreadNotifications()->orderBy('id','desc')->take(10)->get();

        return $notifications;
    }
};

if (!function_exists('countNotificationsForCustomer')) {
    function countNotificationsForCustomer()
    {
        $auth = auth()->guard('customer')->user();

        $count = $auth->notifications()->where('read_at', null)->count();

        return $count;
    }
};

if (!function_exists('loadPartTypeSelectAsTree')) {
    function loadPartTypeSelectAsTree() {
        return (new PartTypeTreeService)->getAllPartTypes();
    }
}

if (!function_exists('loadAllSuppliersTypesAsTree')) {
    function loadAllSuppliersTypesAsTree() {
        return (new SuppliersGroupsTreeService)->getAllSupplierGroup();
    }
}

if (!function_exists('mainPartTypeSelectAsTree')) {
    function mainPartTypeSelectAsTree($className = '' ,$part_id = NULL) {
        return (new PartTypeTreeService)->getMainPartTypes($className ,$part_id);
    }
}

if (!function_exists('subPartTypeSelectAsTree')) {
    function subPartTypeSelectAsTree($className = '' ,$part_id = NULL) {
        return (new PartTypeTreeService)->getSubPartTypes($className ,$part_id);
    }
}

if (!function_exists('mainSuppliersGroupsSelectAsTree')) {
    function mainSuppliersGroupsSelectAsTree($supplier = null,$className = '' ,$part_id = NULL, $branchId = null) {
        return (new SuppliersGroupsTreeService)->getMainPartTypes($supplier,$className ,$part_id,$branchId);
    }
}

if (!function_exists('subSupplierGroupsSelectAsTree')) {
    function subSupplierGroupsSelectAsTree($supplier = null,$className = '' ,$part_id = NULL, $mainGroupIds = [], $branchId = null) {
        return (new SuppliersGroupsTreeService)->getSubPartTypes($supplier,$className ,$part_id, $mainGroupIds, $branchId);
    }
}
if (!function_exists('setActivationClass')) {
    function setActivationClass($routes) {
        if (is_array($routes)) {
            $isCurrentUrl = false;
            foreach ($routes as $route) {
                if (request()->fullUrl() === $route) {
                    $isCurrentUrl = true;
                }
            }
            return $isCurrentUrl ? "current active open" : "";
        }
        return request()->fullUrl() === $routes ? "current" : "";
    }
}

if (!function_exists('partTypes')) {
    function partTypes($part) {

        $purchaseRequestServices = new PurchaseRequestService();

        $partMainTypes = $part->spareParts()->where('status', 1)
            ->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

       return $purchaseRequestServices->getPartTypes($partMainTypes, $part->id);
    }
}

if (!function_exists('taxValueCalculated')) {
    function taxValueCalculated($totalAfterDiscount, $subTotal, $tax) {

        $value = 0;

        if ($tax) {

            if ($tax->execution_time == 'after_discount') {

                $totalUsedInCalculate = $totalAfterDiscount;
            }else {

                $totalUsedInCalculate = $subTotal;
            }

            if ($tax->tax_type == 'amount') {

                $value = $tax->value;

            } else {

                $value = $totalUsedInCalculate * $tax->value / 100;
            }
        }

        return $value;
    }
}

if (!function_exists('getSparePartsIds')) {
     function getSparePartsIds(Sparepart $mainType)
    {
        session()->push('ids', $mainType->id);
        $mainType->children()->get()->each(function ($child) use (&$ids) {
            if ($child->children) {
                getSupPartsByMainPart($child);
            }
            session()->push('ids', $child->id);
        });
    }
}


if (!function_exists('getSupPartsByMainPart')) {
    function getSupPartsByMainPart(Sparepart $sparePart)
    {
        $mainPartsIds = $sparePart->allParts->pluck('id')->toArray();
        session()->push('allPartsIds', $mainPartsIds);
        $sparePart->children()->get()->each(function ($child) use ($sparePart) {
            $ids = $child->allParts->pluck('id')->toArray();
            if ($child->children) {
                getSupPartsByMainPart($child);
            }
            session()->push('allPartsIds', $ids);
        });
    }
}
if (!function_exists('whereBetween')) {
    function whereBetween(&$eloquent, $columnName, $form, $to)
    {
        if (!empty( $form ) && empty( $to )) {
            $eloquent->whereRaw( "$columnName >= ?", [$form] );
        } elseif (empty( $form ) && !empty( $to )) {
            $eloquent->whereRaw( "$columnName <= ?", [$to] );
        } elseif (!empty( $form ) && !empty( $to )) {
            $eloquent->where( function ($query) use ($columnName, $form, $to) {
                $query->whereRaw( "$columnName BETWEEN ? AND ?", [$form, $to] );
            } );
        }
    }
}
