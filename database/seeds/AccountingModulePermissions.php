<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccountingModulePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module_screens = collect([
            "accounts-tree-index" => [
                'view', 'create', 'edit', 'delete'
            ],
            "account-guide-index" => [
                'view'
            ],
            "account-relations" => [
                'view', 'create', 'edit', 'delete'
            ],
            "daily-restrictions" => [
                'view', 'create', 'edit', 'delete', 'print', 'export'
            ],
            "cost-centers" => [
                'view', 'create', 'edit', 'delete'
            ],
            "accounting-general-ledger" => [
                'view', 'print', 'export'
            ],
            "fiscal-years" => [
                'view', 'create', 'edit', 'delete'
            ],
            "trial-balance-index" => [
                'view', 'print', 'export'
            ],
            "balance-sheet-index" => [
                'view', 'print', 'export'
            ],
            "income-list-index" => [
                'view', 'print', 'export'
            ],
            "adverse-restrictions" => [
                'view', 'create'
            ],
        ]);

        $all_permissions = [];

        $module_screens->each(function ($screen_actions ,$screen_name) use (&$all_permissions) {
            $permission_ids = [];
            foreach($screen_actions as $action) {
                $p = Permission::create(['name' => $action .'_'. $screen_name]);
                array_push($permission_ids ,$p->id);
                array_push($all_permissions ,$p->id);
            }
            $module = \App\Models\Module::create(['name' => $screen_name, 'display_name' => 'module_' . $screen_name]);
            $module->permissions()->attach($permission_ids);
        });
        $acc_mod_permissions = Permission::whereIn('id' ,$all_permissions)->get();

        $owner_role =  Role::firstOrCreate(['name' => 'super-admin'] , ['name' => 'user'] )->syncPermissions($acc_mod_permissions);

        $user = User::findOrFail(1);

        $user->syncRoles($owner_role)->syncPermissions($acc_mod_permissions);
    }
}
