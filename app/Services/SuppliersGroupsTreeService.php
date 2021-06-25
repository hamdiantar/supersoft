<?php

namespace App\Services;

use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplierGroup;
use stdClass;

class SuppliersGroupsTreeService
{
    /**
     * @var string
     */
    const view_path = 'admin.supplier_group_tree';

    /**
     * @var stdClass
     */
    protected $selectTreeOptions;

    function __construct()
    {
        $this->selectTreeOptions = new stdClass;
        $this->selectTreeOptions->className = '';
        $this->selectTreeOptions->partId = -1;
    }

    // start : part types as ul tree
    function getSupplierGroupsAsTree()
    {
        $htmlCode = "<ul class='tree'>";
        $startCounter = 1;
        SupplierGroup::with('branch')
            ->whereNull('supplier_group_id')
            ->get()
            ->each(function ($type) use (&$htmlCode, &$startCounter) {
                $htmlCode .= view(self::view_path . '.tree-li', [
                    'child' => $type, 'counter' => $startCounter
                ])->render();

                $htmlCode .= '<ul style="display:none" id="ul-tree-' . $type->id . '">';
                $this->getMyChildren($type, $htmlCode, $startCounter);
                $htmlCode .= '</ul></li>';
                $startCounter++;
            });
        $htmlCode .= '</ul>';
        return $htmlCode;
    }

    private function getMyChildren(SupplierGroup $MainSupplierGroup, &$htmlCode, $depth)
    {
        $counter = 1;
        $MainSupplierGroup
            ->children()
            ->with('branch')
            ->get()
            ->each(function ($child) use (&$htmlCode, $depth, &$counter) {
                $depthCounter = $depth . '.' . $counter;
                $htmlCode .= view(self::view_path . '.tree-li',
                    ['child' => $child, 'counter' => $depthCounter])->render();
                if ($child->children) {
                    $htmlCode .= '<ul style="display:none" id="ul-tree-' . $child->id . '">';
                    $this->getMyChildren($child, $htmlCode, $depthCounter);
                    $htmlCode .= '</ul>';
                }
                $counter++;
                $htmlCode .= '</li>';
            });
    }

    //end

    function getAllSupplierGroup(Supplier $supplier = null)
    {
        $branch_id = authIsSuperAdmin() ? null : auth()->user()->branch_id;
        $htmlCode = "<option value='' class='remove'>" . __('words.select-one') . "</option>";
        $startCounter = 1;
        SupplierGroup::select(
            'id', 'name_ar', 'name_en', 'discount', 'discount_type', 'supplier_group_id'
        )
            ->whereNull('supplier_group_id')
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })->get()->each(function ($type) use (&$htmlCode, &$startCounter,$supplier) {
                $htmlCode .= view(self::view_path . '.tree-option', [
                    'child' => $type,
                    'counter' => $startCounter,
                    'className' => optional($this->selectTreeOptions)->className,
                    'is_selected' => ''
                ])->render();
                $this->subPartTypesOptions($supplier,$type, $htmlCode, $startCounter);
                $startCounter++;
            });
        return $htmlCode;
    }

    function getSubPartTypes($supplier = null,$className = '', $part_id = '', $mainGroupsIds = [], $branch_id = null)
    {
        $this->setSelectTreeOptions($className, $part_id);
        if (empty($branch_id)) {
            $branch_id = authIsSuperAdmin() ? null : auth()->user()->branch_id;
        }
        $htmlCode = "<option value='' class='remove'>" . __('words.select-one') . "</option>";
        $startCounter = 1;
        SupplierGroup::select(
            'id', 'name_ar', 'name_en', 'discount', 'discount_type', 'supplier_group_id'
        )->when($mainGroupsIds, function ($q) use ($mainGroupsIds) {
                $q->whereIn('id', $mainGroupsIds);
            })
            ->whereNull('supplier_group_id')
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
            ->get()
            ->each(function ($type) use (&$htmlCode, &$startCounter,$supplier) {
                $this->subPartTypesOptions($supplier,$type, $htmlCode, $startCounter);
                $startCounter++;
            });
        return $htmlCode;
    }

    function getMainPartTypes($supplier = null,$className = '', $part_id = '', $branch_id = null)
    {
        $this->setSelectTreeOptions($className, $part_id);
        if (empty($branch_id)) {
            $branch_id = authIsSuperAdmin() ? null : auth()->user()->branch_id;
        }
        $htmlCode = "<option value='' class='remove'>" . __('words.select-one') . "</option>";
        $startCounter = 1;
        SupplierGroup::whereNull('supplier_group_id')
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })->get()->each(function ($type) use (&$htmlCode, &$startCounter,$supplier) {
                $htmlCode .= view(self::view_path . '.tree-option', [
                    'child' => $type,
                    'counter' => $startCounter,
                    'className' => optional($this->selectTreeOptions)->className,
                    'supplier' => $supplier
                ])->render();
                $startCounter++;
            });
        return $htmlCode;
    }

    function setSelectTreeOptions($className = '', $partId = null)
    {
        $this->selectTreeOptions->className = $className;
        $this->selectTreeOptions->partId = $partId;
    }

    function mainPartTypesOptions($branch_id = null)
    {
        $htmlCode = "<option value='' class='remove'>" . __('words.select-one') . "</option>";
        $startCounter = 1;
        SupplierGroup::select(
            'id', 'name_ar', 'name_en'
        )
            ->whereNull('supplier_group_id')
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
//            ->with([
//                'allParts' => function ($q) {
//                    $q->where('part_id', $this->selectTreeOptions->partId);
//                }
//            ])
            ->get()
            ->each(function ($type) use (&$htmlCode, &$startCounter) {
                $htmlCode .= view(self::view_path . '.tree-option', [
                    'child' => $type,
                    'counter' => $startCounter,
                    'className' => optional($this->selectTreeOptions)->className,
                    'is_selected' => count($type->allParts) > 0 ? 'selected' : ''
                ])->render();
                $startCounter++;
            });
        return $htmlCode;
    }

    function getPartTypesAsSelect($branch_id = null, $mainGroupsIds = null)
    {
        $htmlCode = "<option value='' class='remove'>" . __('words.select-one') . "</option>";
        $startCounter = 1;
        SupplierGroup::whereNull('supplier_group_id')
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
            ->when($mainGroupsIds, function ($q) use ($mainGroupsIds) {
                $q->whereIn('id', $mainGroupsIds);
            })
            ->get()
            ->each(function ($type) use (&$htmlCode, &$startCounter) {
                $this->subPartTypesOptions(null,$type, $htmlCode, $startCounter);
                $startCounter++;
            });
        return $htmlCode;
    }

    private function subPartTypesOptions($supplier,SupplierGroup $mainType, &$htmlCode, $depth)
    {
        $counter = 1;
        $mainType
            ->children()
            ->with([
                'branch'
            ])
            ->get()
            ->each(function ($child) use (&$htmlCode, $depth, &$counter,$supplier) {
                $depthCounter = $depth . '.' . $counter;
                $htmlCode .= view(self::view_path . '.tree-options-update', [
                    'child' => $child,
                    'counter' => $depthCounter,
                    'className' => optional($this->selectTreeOptions)->className,
                    'is_selected' =>  '',
                    'supplier' =>  $supplier
                ])->render();
                if ($child->children) {
                    $this->subPartTypesOptions($supplier,$child, $htmlCode, $depthCounter);
                }
                $counter++;
            });
    }
}
