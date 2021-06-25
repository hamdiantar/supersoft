<?php

namespace App\Services;

use App\Models\SupplierGroup;
use Exception;

class SuppliersGroupsService
{
    function edit_form(SupplierGroup $supplierGroup)
    {
        if (!auth()->user()->can('update_supplier_groups')) {
            throw new Exception(__('words.not-authorized'));
        }

        if (authIsSuperAdmin()) {
            $validationClass = 'App\Http\Requests\Admin\SuppliersGroups\UpdateSupplierGroupRequest';
            $formRoute = route('admin:supplier-group.superadmin-update', ['id' => $supplierGroup->id]);
        } else {
            $validationClass = 'App\Http\Requests\Admin\SuppliersGroups\UpdateSupplierGroupRequest';
            $formRoute = route('admin:supplier-group.normaladmin-update', ['id' => $supplierGroup->id]);
        }
        return view(SuppliersGroupsTreeService::view_path . '.edit-form', [
            'suppliers_group' => $supplierGroup,
            'validationClass' => $validationClass,
            'formRoute' => $formRoute
        ])->render();
    }

    function create_form($parentId)
    {
        if (!auth()->user()->can('create_supplier_groups')) {
            throw new Exception(__('words.not-authorized'));
        }
        if (authIsSuperAdmin()) {
            $validationClass = 'App\Http\Requests\Admin\SuppliersGroups\CreateSuppliersGroupsRequest';
            $formRoute = route('admin:supplier-group.superadmin-store');
        } else {
            $validationClass = 'App\Http\Requests\Admin\SuppliersGroups\CreateSuppliersGroupsRequest';
            $formRoute = route('admin:supplier-group.normaladmin-store');
        }
        return view(SuppliersGroupsTreeService::view_path . '.create-form', [
            'parentId' => $parentId,
            'validationClass' => $validationClass,
            'formRoute' => $formRoute
        ])->render();
    }

    function insertToDB($data, $image = null)
    {
        if (!auth()->user()->can('create_supplier_groups')) {
            throw new Exception(__('words.not-authorized'));
        }
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        return SupplierGroup::create($data);
    }

    function editInDB($partTypeId, $data)
    {
        if (!auth()->user()->can('update_supplier_groups')) {
            throw new Exception(__('words.not-authorized'));
        }
        $partType = SupplierGroup::find($partTypeId);
        if (!$partType) {
            throw new Exception(__('words.part-type-not-exists'));
        }
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        return $partType->update($data);
    }

    function deleteFromDB($partTypeId)
    {
        if (!auth()->user()->can('delete_supplier_groups')) {
            throw new Exception(__('words.not-authorized'));
        }
        $supplierGroup = SupplierGroup::find($partTypeId);
        if (!$supplierGroup) {
            throw new Exception(__('words.part-type-not-exists'));
        }
        if (SupplierGroup::where('supplier_group_id', $partTypeId)->exists()) {
            throw new Exception(__('words.supplier-group-linked'));
        }
        return $supplierGroup->delete();
    }
}
