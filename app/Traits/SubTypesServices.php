<?php


namespace App\Traits;

use App\Models\SparePart;

trait SubTypesServices
{
    public function getSubPartTypes($partMainTypes, $levelStart = null)
    {
        $data = [];

        $level = $levelStart ? $levelStart : 1;

        foreach ($partMainTypes as $type) {
            $this->getChildrenTypes($type, $level, $data);
            $level += 1;
        }

        return $data;
    }

    public function getChildrenTypes($partType, $level, &$data)
    {
        $counter = 1;

        $types = $partType->children()->where('status', 1)->get();

        foreach ($types as $type) {

            $depthCounter = $level . '.' . $counter;

            $data[$type->id] = $depthCounter . '.' . $type->type;

            if ($type->children) {
                $this->getChildrenTypes($type, $depthCounter, $data);
            }

            $counter++;
        }
    }

    public function getAllPartTypes($partMainTypes, $levelStart = null)
    {
        $data = [];

        $level = $levelStart ? $levelStart : 1;

        foreach ($partMainTypes as $type) {

            $data[$type->id] = $level . '.' . $type->type;

            $this->getChildrenTypes($type, $level, $data);
            $level += 1;
        }

        return $data;
    }
}
