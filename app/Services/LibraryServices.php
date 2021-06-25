<?php


namespace App\Services;

use App\Models\ConcessionLibrary;
use App\Models\CustomerLibrary;
use App\Models\PartLibrary;
use App\Models\SupplierLibrary;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

trait LibraryServices
{
    public function libraryPath($user, $type)
    {

        $libraryPath = $user->library_path;

        if (!$libraryPath) {

            $libraryPath = Str::slug($user->id);

            if (in_array($type, ['part','customer','supplier'])) {

                $libraryPath = Str::slug($user->name_en . '-' . $user->id);
            }

            $user->library_path = Str::slug($libraryPath);

            $user->save();
        }

        $dirPath = 'app/public/' . $type . '_library/';

        $path = storage_path($dirPath);

        if (!\File::exists($path)) {

            \File::makeDirectory($path, 0777, true, true);
        }

        $path = $path . $libraryPath;

        if (!\File::exists($path)) {

            \File::makeDirectory($path, 0777, true, true);
        }

        return $libraryPath;
    }

    public function uploadFiles($file, $directory)
    {
        $time = time();
        $random = rand(1, 100000);
        $divide = $time / $random;
        $encryption = md5($divide);
        $randomName = substr($encryption, 0, 20);

        $fileName = $randomName . '.' . $file->getClientOriginalExtension();

        $path = "public/{$directory}/";

        $file->storeAs($path, $fileName);

        $data = [];

        $data['file_name'] = $fileName;
        $data['extension'] = $file->getClientOriginalExtension();
        $data['name'] = $file->getClientOriginalName();

        return $data;
    }

    public function createSupplierLibrary($supplier_id, $file_name, $extension, $name)
    {
        $fileInLibrary = SupplierLibrary::create([
            'supplier_id' => $supplier_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name'=> $name
        ]);

        return $fileInLibrary;
    }

    public function createCustomerLibrary($customer_id, $file_name, $extension, $name)
    {
        $fileInLibrary = CustomerLibrary::create([
            'customer_id' => $customer_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }

    public function createPartLibrary($part_id, $file_name, $extension, $name)
    {
        $fileInLibrary = PartLibrary::create([
            'part_id' => $part_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }

    public function createConcessionLibrary ($concession_id, $file_name, $extension, $name) {

        $fileInLibrary = ConcessionLibrary::create([
            'concession_id' => $concession_id,
            'file_name' => $file_name,
            'extension' => $extension,
            'name' => $name,
        ]);

        return $fileInLibrary;
    }
}
