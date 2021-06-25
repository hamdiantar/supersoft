<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SuppliersGroups\CreateSuppliersGroupsRequest;
use App\Http\Requests\Admin\SuppliersGroups\UpdateSupplierGroupRequest;
use App\Models\SupplierGroup;
use App\Services\SuppliersGroupsService;
use App\Services\SuppliersGroupsTreeService;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuppliersGroupsTreeController extends Controller
{
    /**
     * @var SuppliersGroupsService
     */
    protected $suppliersGroupsService;

    /**
     * @var SuppliersGroupsTreeService
     */
    protected $suppliersGroupsTreeService;

    function __construct(
        SuppliersGroupsService $suppliersGroupsService,
        SuppliersGroupsTreeService $suppliersGroupsTreeService
    ) {
        $this->suppliersGroupsService = $suppliersGroupsService;
        $this->suppliersGroupsTreeService = $suppliersGroupsTreeService;
    }

    public function index(Request $request)
    {
        $ul_tree_code = $this->suppliersGroupsTreeService->getSupplierGroupsAsTree();
        return view($this->suppliersGroupsTreeService::view_path . '.index' ,['tree' => $ul_tree_code]);
    }

    function getModalForm($action = 'create') {
        $parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : NULL;
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        if ($action != 'create' && !$id) return response(['message' => __('words.choose-supplier-group-to-edit')] ,400);
        try {
            if ($action == 'create') {
                $form_code = $this->suppliersGroupsService->create_form($parent_id);
            } else {
                $form_code = $this->suppliersGroupsService->edit_form(SupplierGroup::findOrFail($id));
            }
            return response(['html_code' => $form_code]);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()] ,400);
        }
    }

    function storeForSuperAdmin(CreateSuppliersGroupsRequest $request) {
        try {
            $this->suppliersGroupsService->insertToDB($request->all());
            return $this->indexRedirect(__('words.supplier-group-created'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function storeForNormalAdmin(CreateSuppliersGroupsRequest $request) {
        try {
            $this->suppliersGroupsService->insertToDB($request->all());
            return $this->indexRedirect(__('words.supplier-group-created'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function updateForSuperAdmin(UpdateSupplierGroupRequest $request ,$part_id) {
        try {
            $this->suppliersGroupsService->editInDB($part_id ,$request->all());
            return $this->indexRedirect(__('words.supplier-group-updated'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function updateForNormalAdmin(UpdateSupplierGroupRequest $request ,$part_id) {
        try {
            $this->suppliersGroupsService->editInDB($part_id ,$request->all());
            return $this->indexRedirect(__('words.supplier-group-updated'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function deleteSupplierGroup() {
        $part_id = isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : NULL;
        try {
            if (!$part_id) throw new Exception(__('words.supplier-group-not-exists'));
            $this->suppliersGroupsService->deleteFromDB($part_id);
            return $this->indexRedirect(__('words.supplier-group-deleted'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    private function indexRedirect($message = '' ,$alertType = 'success') {
        return redirect(route('admin:supplier-group-tree'))->with(['message' => $message, 'alert-type' => $alertType]);
    }

    function mainPartTypesSelect() {
        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        if (!authIsSuperAdmin()) $branch_id = auth()->user()->branch_id;
        return response(['options' => $this->suppliersGroupsTreeService->mainPartTypesOptions($branch_id)]);
    }

    function subPartTypesSelect() {
        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        $main_type_id = isset($_GET['type_id']) && $_GET['type_id'] != '' ? $_GET['type_id'] : NULL;
        $type_ids = isset($_GET['type_ids']) && $_GET['type_ids'] != '' ? explode("," ,$_GET['type_ids']) : NULL;

        if (!authIsSuperAdmin()) $branch_id = auth()->user()->branch_id;
        return response(['options' => $this->suppliersGroupsTreeService->getPartTypesAsSelect($branch_id ,$main_type_id ,$type_ids)]);
    }
}
