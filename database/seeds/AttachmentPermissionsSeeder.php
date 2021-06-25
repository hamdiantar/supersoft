<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AttachmentPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = ['suppliers', 'customers', 'parts'];

        foreach ($data as $item) {

            $permissionIds = [];

            $permissions = ['create_attachment', 'view_attachment', 'delete_attachment'];

            foreach ($permissions as $permission) {

                $permissionItem = Permission::create(['name' => $item . '_' . $permission]);
                $permissionIds[] = $permissionItem->id;
            }

            $module = \App\Models\Module::where('name', $item)->first();

            if ($module) {

                $module->permissions()->attach($permissionIds);
            }
        }

        $owner_role = Role::firstOrCreate(['name' => 'super-admin'], ['name' => 'user'])->syncPermissions(Permission::all());

        $user = User::findOrFail(1);

        $user->syncRoles($owner_role)->syncPermissions(Permission::all());
    }
}
