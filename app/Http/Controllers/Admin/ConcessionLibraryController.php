<?php

namespace App\Http\Controllers\Admin;

use App\Models\Concession;
use App\Models\ConcessionLibrary;
use App\Models\Customer;
use App\Models\CustomerLibrary;
use App\Services\LibraryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConcessionLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'item_id' => 'required|integer|exists:concessions,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {

            return response()->json( $validator->errors()->first(), 400);
        }

        try {

            $concession = Concession::find($request['item_id']);

            $library_path = $this->libraryPath($concession, 'concession');

            $director = 'concession_library/' .$library_path;

            $files = $request['files'];

            foreach ($files as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);

                $fileName = $fileData['file_name'];
                $extension = Str::lower($fileData['extension']);
                $fileOriginalName = $fileData['name'];

                $files[$index] = $this->createConcessionLibrary($concession->id, $fileName, $extension, $fileOriginalName);
            }

            $view = view('admin.concessions.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            return response()->json( __('words.back-customer'), 400);
        }

        return response()->json(['view' => $view, 'message'=> __('upload successfully')], 200);
    }

    public function getFiles (Request $request) {

        $validator = Validator::make($request->all(),[

            'id'=>'required|integer|exists:concessions,id',
        ]);

        if ($validator->fails()) {

            return response( $validator->errors()->first(),400);
        }

        try {

            $concession = Concession::find($request['id']);

            if (!$concession) {
                return response( 'concession not valid',400);
            }

            $library_path = $concession->library_path;

            $files = $concession->files;

            $view = view('admin.concessions.library', compact('files', 'library_path'))->render();

        }catch (\Exception $e) {

            return response( 'sorry, please try later',400);
        }

        return response( ['view' => $view],200);
    }

    public function destroyFile (Request $request) {

        $validator = Validator::make($request->all(),[

            'id'=>'required|integer|exists:concession_libraries,id',
        ]);

        if ($validator->fails()) {

            return response( $validator->errors()->first(),400);
        }

        try {

            $file = ConcessionLibrary::find($request['id']);

            $concession = $file->concession;

            $filePath = storage_path('app/public/concession_library/' . $concession->library_path . '/' . $file->file_name );

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
