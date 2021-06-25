<?php

namespace App\Http\Controllers\Admin;

use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationLibrary;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseReceiptLibrary;
use App\Services\LibraryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PurchaseReceiptLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'item_id' => 'required|integer|exists:purchase_receipts,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseReceipt = PurchaseReceipt::find($request['item_id']);

            $library_path = $this->libraryPath($purchaseReceipt, 'purchase_receipts');

            $director = 'purchase_receipts_library/' . $library_path;

            $files = $request['files'];

            foreach ($files as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);

                $fileName = $fileData['file_name'];
                $extension = Str::lower($fileData['extension']);
                $fileOriginalName = $fileData['name'];

                $files[$index] = $this->createPurchaseReceiptLibrary($purchaseReceipt->id, $fileName, $extension, $fileOriginalName);
            }

            $view = view('admin.purchase_receipts.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            return response()->json(__('words.back-customer'), 400);
        }

        return response()->json(['view' => $view, 'message' => __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:purchase_receipts,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {

            $purchaseReceipt = PurchaseReceipt::find($request['id']);

            if (!$purchaseReceipt) {
                return response('purchase Receipt not valid', 400);
            }

            $library_path = $purchaseReceipt->library_path;

            $files = $purchaseReceipt->files;

            $view = view('admin.purchase_receipts.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            return response('sorry, please try later', 400);
        }

        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:purchase_receipt_libraries,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {

            $file = PurchaseReceiptLibrary::find($request['id']);

            $purchaseReceipt = $file->purchaseReceipt;

            $filePath = storage_path('app/public/purchase_receipts_library/' . $purchaseReceipt->library_path . '/' . $file->file_name);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $file->delete();

        } catch (\Exception $e) {
            return response('sorry, please try later', 400);
        }

        return response(['id' => $request['id']], 200);
    }

    public function createPurchaseReceiptLibrary($receiptId, $file_name, $extension, $name)
    {
        $fileInLibrary = PurchaseReceiptLibrary::create([
            'purchase_receipt_id' => $receiptId,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }
}
