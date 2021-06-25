<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionsV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collection = collect([

            'customer_request',
            'quotation_request',
        ]);

        $collection->each(function ($item, $key) {

            $ids = [];

            // create permissions for each collection item
            $p_view = Permission::create(['name' => 'view_' . $item ]);

            $p_accept = Permission::create(['name' => 'accept_' . $item ]);

            $p_reject = Permission::create(['name' => 'reject_' . $item ]);

            $p_delete = Permission::create(['name' => 'delete_' . $item]);

            $ids[] = $p_view->id;
            $ids[] = $p_accept->id;
            $ids[] = $p_reject->id;
            $ids[] = $p_delete->id;


            //create module
            $module = \App\Models\Module::create(['name' => $item, 'display_name' => 'module_' . $item]);

            $module->permissions()->attach($ids);

            $owner_role =  Role::firstOrCreate(['name' => 'super-admin'] , ['name' => 'user'] )->syncPermissions(Permission::all());

            $user = User::findOrFail(1);

            $user->syncRoles($owner_role)->syncPermissions(Permission::all());

        });
    }
}
