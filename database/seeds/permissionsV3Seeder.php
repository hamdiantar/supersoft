<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class permissionsV3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collection = collect([

            'notification_setting',
            'mail_setting',
            'sms_setting',
            'maintenance_status',
        ]);

        $collection->each(function ($item, $key) {

            $ids = [];

            if($item == 'maintenance_status'){

                // create permissions for each collection item
                $p_view = Permission::create(['name' => 'view_' . $item ]);

                $ids[] = $p_view->id;

            }else {

                // create permissions for each collection item
                $p_view = Permission::create(['name' => 'view_' . $item ]);

                $p_update = Permission::create(['name' => 'update_' . $item ]);

                $ids[] = $p_view->id;
                $ids[] = $p_update->id;
            }

            //create module
            $module = \App\Models\Module::create(['name' => $item, 'display_name' => 'module_' . $item]);

            $module->permissions()->attach($ids);

            $owner_role =  Role::firstOrCreate(['name' => 'super-admin'] , ['name' => 'user'] )->syncPermissions(Permission::all());

            $user = User::findOrFail(1);

            $user->syncRoles($owner_role)->syncPermissions(Permission::all());

        });
    }
}
