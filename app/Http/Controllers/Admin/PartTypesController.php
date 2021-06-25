<?php
namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\SparePart;
use App\Services\PartTypeService;
use App\Http\Controllers\Controller;
use App\Services\PartTypeTreeService;
use App\Http\Requests\Admin\SparePart\PartTypeSuperadminRequest;
use App\Http\Requests\Admin\SparePart\PartTypeNormaladminRequest;

class PartTypesController extends Controller
{
    protected $partTypeService;
    protected $partTypeTreeService;

    function __construct() {
        $this->partTypeService = new PartTypeService;
        $this->partTypeTreeService = new PartTypeTreeService;
    }

    function index() {
        $ul_tree_code = $this->partTypeTreeService->getPartTypesAsTree();
        return view($this->partTypeTreeService::view_path . '.index' ,['tree' => $ul_tree_code]);
    }

    function getModalForm($action = 'create') {
        $parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : NULL;
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        if ($action != 'create' && !$id) return response(['message' => __('words.choose-part-type-to-edit')] ,400);
        try {
            if ($action == 'create') {
                $form_code = $this->partTypeService->create_form($parent_id);
            } else {
                $form_code = $this->partTypeService->edit_form(SparePart::findOrFail($id));
            }
            return response(['html_code' => $form_code]);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()] ,400);
        }
    }

    function storeForSuperAdmin(PartTypeSuperadminRequest $request) {
        try {
            $this->partTypeService->insertToDB($request->all(), $request->has('image') ? $request->image : null);
            return $this->indexRedirect(__('words.spare-part-created'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function storeForNormalAdmin(PartTypeNormaladminRequest $request) {
        try {
            $this->partTypeService->insertToDB($request->all(), $request->has('image') ? $request->image : null);
            return $this->indexRedirect(__('words.spare-part-created'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function updateForSuperAdmin(PartTypeSuperadminRequest $request ,$part_id) {
        try {
            $this->partTypeService->editInDB($part_id ,$request->all(), $request->has('image') ? $request->image : null);
            return $this->indexRedirect(__('words.spare-part-updated'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function updateForNormalAdmin(PartTypeNormaladminRequest $request ,$part_id) {
        try {
            $this->partTypeService->editInDB($part_id ,$request->all(), $request->has('image') ? $request->image : null);
            return $this->indexRedirect(__('words.spare-part-updated'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    function deletePart() {
        $part_id = isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : NULL;
        try {
            if (!$part_id) throw new Exception(__('words.part-type-not-exists'));
            $this->partTypeService->deleteFromDB($part_id);
            return $this->indexRedirect(__('words.spare-part-deleted'));
        } catch (Exception $e) {
            return $this->indexRedirect($e->getMessage() ,'error');
        }
    }

    private function indexRedirect($message = '' ,$alertType = 'success') {
        return redirect(route('admin:part-types'))->with(['message' => $message, 'alert-type' => $alertType]);
    }

    function mainPartTypesSelect() {
        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        if (!authIsSuperAdmin()) $branch_id = auth()->user()->branch_id;
        return response(['options' => $this->partTypeTreeService->mainPartTypesOptions($branch_id)]);
    }

    function subPartTypesSelect() {
        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        $main_type_id = isset($_GET['type_id']) && $_GET['type_id'] != '' ? $_GET['type_id'] : NULL;
        $type_ids = isset($_GET['type_ids']) && $_GET['type_ids'] != '' ? explode("," ,$_GET['type_ids']) : NULL;

        if (!authIsSuperAdmin()) $branch_id = auth()->user()->branch_id;
        return response(['options' => $this->partTypeTreeService->getPartTypesAsSelect($branch_id ,$main_type_id ,$type_ids)]);
    }

    function getAllParts() {
        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        if (!authIsSuperAdmin()) $branch_id = auth()->user()->branch_id;
        return response(['options' => $this->partTypeTreeService->findAllPartTypes($branch_id)]);
    }
}
