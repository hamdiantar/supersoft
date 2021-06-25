<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\CustomerLibrary;
use App\Services\LibraryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'customer_id' => 'required|integer|exists:customers,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {

            return response()->json( $validator->errors()->first(), 400);
        }

        try {

            $customer = Customer::find($request['customer_id']);

            $library_path = $this->libraryPath($customer, 'customer');

            $director = 'customer_library/' .$library_path;

            $files = $request['files'];

            foreach ($files as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);

                $fileName = $fileData['file_name'];
                $extension = Str::lower($fileData['extension']);
                $fileOriginalName = $fileData['name'];

                $files[$index] = $this->createCustomerLibrary($customer->id, $fileName, $extension, $fileOriginalName);
            }

            $view = view('admin.customers.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {

            return response()->json( __('words.back-customer'), 400);
        }

        return response()->json(['view' => $view, 'message'=> __('upload successfully')], 200);
    }

    public function getFiles (Request $request) {

        $validator = Validator::make($request->all(),[

            'id'=>'required|integer|exists:customers,id',
        ]);

        if ($validator->fails()) {

            return response( $validator->errors()->first(),400);
        }

        try {

            $customer = Customer::find($request['id']);

            if (!$customer) {
                return response( 'customer not valid',400);
            }

            $library_path = $customer->library_path;

            $files = $customer->files;

            $view = view('admin.customers.library', compact('files', 'library_path'))->render();

        }catch (\Exception $e) {

            return response( 'sorry, please try later',400);
        }

        return response( ['view' => $view],200);
    }

    public function destroyFile (Request $request) {

        $validator = Validator::make($request->all(),[

            'id'=>'required|integer|exists:customer_libraries,id',
        ]);

        if ($validator->fails()) {

            return response( $validator->errors()->first(),400);
        }

        try {

            $file = CustomerLibrary::find($request['id']);

            $customer = $file->customer;

            $filePath = storage_path('app/public/customer_library/' . $customer->library_path . '/' . $file->file_name );

            if (File::exists($filePath)){

                File::delete($filePath);
            }

            $file->delete();

        }catch (\Exception $e) {

            return response( 'sorry, please try later',400);
        }

        return response( ['id' => $request['id']],200);
    }
}
