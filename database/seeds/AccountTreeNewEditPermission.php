<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AccountTreeNewEditPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $p = Permission::create(['name' => 'accounts-tree-index_account_nature_edit']);
        $module = \App\Models\Module::where(['name' => 'accounts-tree-index', 'display_name' => 'module_accounts-tree-index'])->first();
        if ($module)
            $module->permissions()->attach([$p->id]);
    }
}
