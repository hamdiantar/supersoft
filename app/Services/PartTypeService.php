<?php
namespace App\Services;

use App\Models\Part;
use Exception;
use App\Models\SparePart;
use Illuminate\Support\Facades\File;

class PartTypeService {
    function edit_form(SparePart $partType) {
        if (!auth()->user()->can('update_spareParts')) throw new Exception(__('words.not-authorized'));
        if (authIsSuperAdmin()) {
            $validationClass = 'App\Http\Requests\Admin\SparePart\PartTypeSuperadminRequest';
            $formRoute = route('admin:part-types.superadmin-update' ,['id' => $partType->id]);
        } else {
            $validationClass = 'App\Http\Requests\Admin\SparePart\PartTypeNormaladminRequest';
            $formRoute = route('admin:part-types.normaladmin-update' ,['id' => $partType->id]);
        }
        return view(PartTypeTreeService::view_path . '.edit-form' ,[
            'partType' => $partType ,'validationClass' => $validationClass ,'formRoute' => $formRoute
        ])->render();
    }

    function create_form($parentId) {
        if (!auth()->user()->can('create_spareParts')) throw new Exception(__('words.not-authorized'));
        if (authIsSuperAdmin()) {
            $validationClass = 'App\Http\Requests\Admin\SparePart\PartTypeSuperadminRequest';
            $formRoute = route('admin:part-types.superadmin-store');
        } else {
            $validationClass = 'App\Http\Requests\Admin\SparePart\PartTypeNormaladminRequest';
            $formRoute = route('admin:part-types.normaladmin-store');
        }
        return view(PartTypeTreeService::view_path . '.create-form' ,[
            'parentId' => $parentId ,'validationClass' => $validationClass, 'formRoute' => $formRoute
        ])->render();
    }

    function insertToDB($data ,$image = NULL) {
        if (!auth()->user()->can('create_spareParts')) throw new Exception(__('words.not-authorized'));
        if ($image) {
            $data['image'] = uploadImage($image, 'spare-parts');
        }
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        return SparePart::create($data);
    }

    function editInDB($partTypeId ,$data ,$image = NULL) {
        if (!auth()->user()->can('update_spareParts')) throw new Exception(__('words.not-authorized'));
        $partType = SparePart::find($partTypeId);
        if (!$partType) throw new Exception(__('words.part-type-not-exists'));
        if ($image && $data['image'] != null) {
            if (File::exists(storage_path('app/public/images/spare-parts/' . $partType->image)))
                File::delete(storage_path('app/public/images/spare-parts/' . $partType->image));
            $data['image'] = uploadImage($image, 'spare-parts');
        }
        if (!authIsSuperAdmin()) $data['branch_id'] = auth()->user()->branch_id;

        return $partType->update($data);
    }

    function deleteFromDB($partTypeId) {
        if (!auth()->user()->can('delete_spareParts')) throw new Exception(__('words.not-authorized'));
        $partType = SparePart::find($partTypeId);
        if (!$partType) throw new Exception(__('words.part-type-not-exists'));
        if (Part::whereHas('spareParts' ,function ($q) use ($partTypeId) {
            $q->where('spare_part_type_id' ,$partTypeId);
        })->exists()) throw new Exception(__('words.part-type-not-deleteable'));
        if (SparePart::where('spare_part_id' ,$partTypeId)->exists()) throw new Exception(__('words.part-type-linked'));
        if (File::exists(storage_path('app/public/images/spare-parts/' . $partType->image)))
            File::delete(storage_path('app/public/images/spare-parts/' . $partType->image));
        return $partType->forceDelete();
    }
}
