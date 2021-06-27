<?php

namespace App\Http\Controllers\Admin;

use App\Services\LibraryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PurchaseInvoiceLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'item_id' => 'required|integer|exists:purchase_quotations,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseQuotation = PurchaseQuotation::find($request['item_id']);

            $library_path = $this->libraryPath($purchaseQuotation, 'purchase_quotations');

            $director = 'purchase_quotations_library/' . $library_path;

            $files = $request['files'];

            foreach ($files as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);

                $fileName = $fileData['file_name'];
                $extension = Str::lower($fileData['extension']);
                $fileOriginalName = $fileData['name'];

                $files[$index] = $this->createQuotationLibrary($purchaseQuotation->id, $fileName, $extension, $fileOriginalName);
            }

            $view = view('admin.purchase_quotations.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            return response()->json(__('words.back-customer'), 400);
        }

        return response()->json(['view' => $view, 'message' => __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:purchase_quotations,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {

            $purchaseQuotation = PurchaseQuotation::find($request['id']);

            if (!$purchaseQuotation) {
                return response('purchase  quotation not valid', 400);
            }

            $library_path = $purchaseQuotation->library_path;

            $files = $purchaseQuotation->files;

            $view = view('admin.purchase_quotations.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            return response('sorry, please try later', 400);
        }

        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:purchase_quotation_libraries,id',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->first(), 400);
        }

        try {

            $file = PurchaseQuotationLibrary::find($request['id']);

            $purchaseQuotation = $file->purchaseQuotation;

            $filePath = storage_path('app/public/purchase_quotations_library/' . $purchaseQuotation->library_path . '/' . $file->file_name);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $file->delete();

        } catch (\Exception $e) {

            return response('sorry, please try later', 400);
        }

        return response(['id' => $request['id']], 200);
    }

    public function createQuotationLibrary($quotationId, $file_name, $extension, $name)
    {
        $fileInLibrary = PurchaseQuotationLibrary::create([
            'purchase_quotation_id' => $quotationId,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }
}
