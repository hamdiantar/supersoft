<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class OpeningBalancePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $screen_actions = ['view', 'create', 'edit', 'delete'];
        $screens = [
            'opening-balance'
        ];
        foreach($screens as $screen_name) {
            $permission_ids = [];
            foreach($screen_actions as $action) {
                $p = Permission::create(['name' => $action .'_'. $screen_name]);
                array_push($permission_ids ,$p->id);
            }
            $module = \App\Models\Module::create(['name' => $screen_name, 'display_name' => 'module_' . $screen_name]);
            $module->permissions()->attach($permission_ids);
            User::findOrFail(1)->permissions()->attach($permission_ids);
        }
    }
}
