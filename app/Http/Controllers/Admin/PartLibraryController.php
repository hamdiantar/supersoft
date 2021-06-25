<?php

namespace App\Http\Controllers\Admin;

use App\Models\Part;
use App\Models\PartLibrary;
use App\Models\Supplier;
use App\Models\SupplierLibrary;
use App\Services\LibraryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PartLibraryController extends Controller
{
    use LibraryServices;

    public function uploadLibrary(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'part_id' => 'required|integer|exists:parts,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ]);

        if ($validator->fails()) {

            return response()->json( $validator->errors()->first(), 400);
        }

        try {

            $part = Part::find($request['part_id']);

            $library_path = $this->libraryPath($part, 'part');

            $director = 'part_library/' . $library_path;

            $files = [];



            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles($file, $director);

                $fileName = $fileData['file_name'];
                $extension = Str::lower($fileData['extension']);
                $fileOriginalName = $fileData['name'];

                $files[$index] = $this->createPartLibrary($part->id, $fileName, $extension, $fileOriginalName);
            }

            $view = view('admin.parts.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {

//            dd($e->getMessage());
            return response()->json( __('words.back-support', 400));
        }

        return response()->json( ['view'=> $view, 'message'=> __('upload successfully')], 200);
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'id' => 'required|integer|exists:parts,id',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->first(), 400);
        }

        try {

            $part = Part::find($request['id']);

            if (!$part) {
                return response('part not valid', 400);
            }

            $library_path = $part->library_path;

            $files = $part->files;

            $view = view('admin.parts.library', compact('files', 'library_path'))->render();

        } catch (\Exception $e) {
            return response('sorry, please try later', 400);
        }

        return response(['view' => $view], 200);
    }

    public function destroyFile(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'id' => 'required|integer|exists:part_libraries,id',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->first(), 400);
        }

        try {

            $file = PartLibrary::find($request['id']);

            $part = $file->part;

            $filePath = storage_path('app/public/part_library/' . $part->library_path . '/' . $file->file_name);


            if (File::exists($filePath)) {

                File::delete($filePath);
            }

            $file->delete();

        } catch (\Exception $e) {

            return response('sorry, please try later', 200);
        }

        return response(['id' => $request['id']], 200);
    }
}
