<?php

namespace App\Http\Controllers\Admin;

use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationLibrary;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderLibrary;
use App\Services\LibraryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SupplyOrderLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'item_id' => 'required|integer|exists:supply_orders,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $supplyOrder = SupplyOrder::find($request['item_id']);

            $library_path = $this->libraryPath($supplyOrder, 'supply_orders');

            $director = 'supply_orders_library/' . $library_path;

            $files = $request['files'];

            foreach ($files as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);

                $fileName = $fileData['file_name'];
                $extension = Str::lower($fileData['extension']);
                $fileOriginalName = $fileData['name'];

                $files[$index] = $this->createSupplyOrderLibrary($supplyOrder->id, $fileName, $extension, $fileOriginalName);
            }

            $view = view('admin.supply_orders.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(__('words.back-supply-orders'), 400);
        }

        return response()->json(['view' => $view, 'message' => __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:supply_orders,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {

            $supplyOrder = SupplyOrder::find($request['id']);

            if (!$supplyOrder) {
                return response('supply order not valid', 400);
            }

            $library_path = $supplyOrder->library_path;

            $files = $supplyOrder->files;

            $view = view('admin.supply_orders.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            return response('sorry, please try later', 400);
        }

        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:supply_order_libraries,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {

            $file = SupplyOrderLibrary::find($request['id']);

            $supplyOrder = $file->supplyOrder;

            $filePath = storage_path('app/public/supply_orders_library/' . $supplyOrder->library_path . '/' . $file->file_name);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $file->delete();

        } catch (\Exception $e) {

            return response('sorry, please try later', 400);
        }

        return response(['id' => $request['id']], 200);
    }

    public function createSupplyOrderLibrary($supplyOrderId, $file_name, $extension, $name)
    {
        $fileInLibrary = SupplyOrderLibrary::create([
            'supply_order_id' => $supplyOrderId,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }
}
