<?php


namespace App\Traits;


trait SupplierSubGroups
{

    public function getSubGroups($mainGroups, $levelStart = null)
    {
        $data = [];

        $level = $levelStart ? $levelStart : 1;

        foreach ($mainGroups as $group) {
            $this->getChildrenGroups($group, $level, $data);
            $level += 1;
        }

        return $data;
    }

    public function getChildrenGroups($group, $level, &$data)
    {
        $counter = 1;

        $groups = $group->children()->where('status', 1)->get();

        foreach ($groups as $group) {

            $depthCounter = $level . '.' . $counter;

            $data[$group->id] = $depthCounter . '.' . $group->name;

            if ($group->children) {
                $this->getChildrenGroups($group, $depthCounter, $data);
            }

            $counter++;
        }
    }

}
