<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class ActiveTaxesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::create(['name' => 'purchase_invoices_active_tax']);

        $module = \App\Models\Module::where('name', 'purchase_invoices')->first();

        if ($module) {

            $module->permissions()->attach($permission->id);
        }

        $owner_role = Role::firstOrCreate(['name' => 'super-admin'], ['name' => 'user'])->syncPermissions(Permission::all());

        $user = User::findOrFail(1);

        $user->syncRoles($owner_role)->syncPermissions(Permission::all());
    }
}
