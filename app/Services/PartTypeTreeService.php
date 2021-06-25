<?php
namespace App\Services;

use App\Models\SparePart;
use stdClass;

class PartTypeTreeService {
    const view_path = 'admin.part-types';
    protected $selectTreeOptions;

    function __construct() {
        $this->selectTreeOptions = new stdClass;
        $this->selectTreeOptions->className = '';
        $this->selectTreeOptions->partId = -1;
    }

    // start : part types as ul tree
    function getPartTypesAsTree() {
        $htmlCode = "<ul class='tree'>";
        $startCounter = 1;
        SparePart::select(
            'id' ,'type_ar' ,'type_en' ,'branch_id' ,'spare_part_id'
        )
        ->whereNull('spare_part_id')
        ->with('branch')
        ->get()
        ->each(function ($type) use (&$htmlCode ,&$startCounter) {
            $htmlCode .= view(self::view_path . '.tree-li' ,['child' => $type ,'counter' => $startCounter])->render();
            $htmlCode .= '<ul style="display:none" id="ul-tree-'.$type->id.'">';
            $this->getMyChildren($type ,$htmlCode ,$startCounter);
            $htmlCode .= '</ul></li>';
            $startCounter++;
        });

        $htmlCode .= '</ul>';
        return $htmlCode;
    }

    private function getMyChildren(Sparepart $mainType ,&$htmlCode ,$depth) {
        $counter = 1;
        $mainType
        ->children()
        ->with('branch')
        ->get()
        ->each(function ($child) use (&$htmlCode ,$depth ,&$counter) {
            $depthCounter = $depth .'.'. $counter;
            $htmlCode .= view(self::view_path . '.tree-li' ,['child' => $child ,'counter' => $depthCounter])->render();
            if ($child->children) {
                $htmlCode .= '<ul style="display:none" id="ul-tree-'.$child->id.'">';
                $this->getMyChildren($child, $htmlCode, $depthCounter);
				$htmlCode .= '</ul>';
            }
            $counter++;
            $htmlCode .= '</li>';
        });
    }
    //end

    function getAllPartTypes($branchId = null) {
        $branch_id = authIsSuperAdmin() ? NULL :auth()->user()->branch_id;
        if ($branchId) {
            $branch_id = $branchId;
        }
        $htmlCode = "<option value='' class='remove'>". __('words.select-one') ."</option>";
        $startCounter = 1;
        SparePart::select(
            'id' ,'type_ar' ,'type_en' ,'branch_id' ,'spare_part_id'
        )
        ->whereNull('spare_part_id')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->with(['allParts' => function ($q) {
            $q->where('part_id' ,$this->selectTreeOptions->partId);
        }])
        ->get()
        ->each(function ($type) use (&$htmlCode ,&$startCounter) {
            $htmlCode .= view(self::view_path . '.tree-option' ,[
                'child' => $type ,'counter' => $startCounter ,'className' => optional($this->selectTreeOptions)->className,
                'is_selected' => count($type->allParts) > 0 ? 'selected' : ''
            ])->render();
            $this->subPartTypesOptions($type ,$htmlCode ,$startCounter);
            $startCounter++;
        });
        return $htmlCode;
    }

    function getSubPartTypes($className = '' ,$part_id = '') {
        $this->setSelectTreeOptions($className ,$part_id);
        $branch_id = authIsSuperAdmin() ? NULL :auth()->user()->branch_id;
        $htmlCode = "<option value='' class='remove'>". __('words.select-one') ."</option>";
        $startCounter = 1;
        SparePart::where('status', 1)->select(
            'id' ,'type_ar' ,'type_en' ,'branch_id' ,'spare_part_id'
        )
        ->whereNull('spare_part_id')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->get()
        ->each(function ($type) use (&$htmlCode ,&$startCounter) {
            $this->subPartTypesOptions($type ,$htmlCode ,$startCounter);
            $startCounter++;
        });
        return $htmlCode;
    }

    function getMainPartTypes($className = '' ,$part_id = '') {
        $this->setSelectTreeOptions($className ,$part_id);
        $branch_id = authIsSuperAdmin() ? NULL :auth()->user()->branch_id;
        $htmlCode = "<option value='' class='remove'>". __('words.select-one') ."</option>";
        $startCounter = 1;
        SparePart::where('status', 1)->select(
            'id' ,'type_ar' ,'type_en'
        )
        ->whereNull('spare_part_id')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->with(['allParts' => function ($q) {
            $q->where('part_id' ,$this->selectTreeOptions->partId);
        }])
        ->get()
        ->each(function ($type) use (&$htmlCode ,&$startCounter) {
            $htmlCode .= view(self::view_path . '.tree-option' ,[
                'child' => $type ,'counter' => $startCounter ,'className' => optional($this->selectTreeOptions)->className,
                'is_selected' => count($type->allParts) > 0 ? 'selected' : ''
            ])->render();
            $startCounter++;
        });
        return $htmlCode;
    }

    function setSelectTreeOptions($className = '' ,$partId = NULL) {
        $this->selectTreeOptions->className = $className;
        $this->selectTreeOptions->partId = $partId;
    }

    function mainPartTypesOptions($branch_id = NULL) {
        $htmlCode = "<option value='' class='remove'>". __('words.select-one') ."</option>";
        $startCounter = 1;
        SparePart::where('status', 1)->select(
            'id' ,'type_ar' ,'type_en'
        )
        ->whereNull('spare_part_id')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->with(['allParts' => function ($q) {
            $q->where('part_id' ,$this->selectTreeOptions->partId);
        }])
        ->get()
        ->each(function ($type) use (&$htmlCode ,&$startCounter) {
            $htmlCode .= view(self::view_path . '.tree-option' ,[
                'child' => $type ,'counter' => $startCounter ,'className' => optional($this->selectTreeOptions)->className,
                'is_selected' => count($type->allParts) > 0 ? 'selected' : ''
            ])->render();
            $startCounter++;
        });
        return $htmlCode;
    }

    function getPartTypesAsSelect($branch_id = NULL ,$main_type_id = NULL ,$type_ids = NULL) {
        $htmlCode = "<option value='' class='remove'>". __('words.select-one') ."</option>";
        $startCounter = 1;
        SparePart::where('status', 1)->select(
            'id' ,'type_ar' ,'type_en' ,'branch_id' ,'spare_part_id'
        )
        ->whereNull('spare_part_id')
        ->when($branch_id ,function ($q) use ($branch_id) {
            $q->where('branch_id' ,$branch_id);
        })
        ->when($main_type_id ,function ($q) use ($main_type_id) {
            $q->where('id' ,$main_type_id);
        })
        ->when($type_ids ,function ($q) use ($type_ids) {
            $q->whereIn('id' ,$type_ids);
        })
        ->with(['allParts' => function ($q) {
            $q->where('part_id' ,$this->selectTreeOptions->partId);
        }])
        ->get()
        ->each(function ($type) use (&$htmlCode ,&$startCounter) {
            $this->subPartTypesOptions($type ,$htmlCode ,$startCounter);
            $startCounter++;
        });
        return $htmlCode;
    }

    private function subPartTypesOptions(Sparepart $mainType ,&$htmlCode ,$depth) {
        $counter = 1;
        $mainType
        ->children()
        ->with([
            'branch',
            'allParts' => function ($q) {
                $q->where('part_id' ,$this->selectTreeOptions->partId);
            }
        ])
        ->get()
        ->each(function ($child) use (&$htmlCode ,$depth ,&$counter) {
            $depthCounter = $depth .'.'. $counter;
            $htmlCode .= view(self::view_path . '.tree-option' ,[
                'child' => $child ,'counter' => $depthCounter ,'className' => optional($this->selectTreeOptions)->className,
                'is_selected' => count($child->allParts) > 0 ? 'selected' : ''
            ])->render();
            if ($child->children) {
                $this->subPartTypesOptions($child, $htmlCode, $depthCounter);
            }
            $counter++;
        });
    }

    function findAllPartTypes($branchId = null) {
        $branch_id = authIsSuperAdmin() ? NULL :auth()->user()->branch_id;
        if ($branchId) {
            $branch_id = $branchId;
        }
        $htmlCode = "<option value='' class='remove'>". __('words.select-one') ."</option>";
        $startCounter = 1;
        SparePart::select(
            'id' ,'type_ar' ,'type_en' ,'branch_id' ,'spare_part_id'
        )
            ->when($branch_id ,function ($q) use ($branch_id) {
                $q->where('branch_id' ,$branch_id);
            })
            ->with(['allParts' => function ($q) {
                $q->where('part_id' ,$this->selectTreeOptions->partId);
            }])
            ->get()
            ->each(function ($type) use (&$htmlCode ,&$startCounter) {
                $htmlCode .= view(self::view_path . '.tree-option' ,[
                    'child' => $type ,'counter' => $startCounter ,'className' => optional($this->selectTreeOptions)->className,
                    'is_selected' => count($type->allParts) > 0 ? 'selected' : ''
                ])->render();
                $this->subPartTypesOptions($type ,$htmlCode ,$startCounter);
                $startCounter++;
            });
        return $htmlCode;
    }
}
