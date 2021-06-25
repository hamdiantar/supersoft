<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::create([
            'name' => 'login-admin'
        ]);


        $collection = collect([
            'users',
            'roles',
            'logs',
            'parts',
            'services',
            'services_types',
            'maintenance_detections',
            'maintenance_detections_types',
            'suppliers',
            'supplier_groups',
            'customer_groups',
            'lockers',
            'locker_transactions',
            'locker_transfers',
            'accounts',
            'account_transfers',
            'sales_invoices',
            'sales_invoices_return',
            'quotations',
            'work_card',
            'branches',
            'currencies',
            'countries',
            'cities',
            'areas',
            'taxes',
            'shifts',
            'stores',
            'spareParts',
            'sparePartsUnit',
            'servicePackages',
            'customers',
            'expense_item',
            'expense_type',
            'expense_receipts',
            'revenue_item',
            'revenue_type',
            'revenue_receipts',
            'purchase_invoices',
            'purchase_return_invoices',
            'db-backup',
            'assets',
            'capital-balance',
            'employee_settings',
            'employees_data',
            'employees_attendance',
            'employees_delay',
            'employee_reward_discount',
            'employee-absence',
            'advances',
            'employees_salaries',
            'companies',
            'car_models',
            'car_types',
            'setting'
            // ... // List all your Models you want to have Permissions for.
        ]);

        $collection->each(function ($item, $key) {

            $ids = [];

//            special case
            if($item == 'report'){

                // create permissions for each collection item
                $p_view = Permission::create(['name' => 'view_' . $item ]);

                $p_download = Permission::create(['name' => 'download_' . $item]);

                $ids[] = $p_view->id;
                $ids[] = $p_download->id;

            }
            elseif($item == 'logs'){

                // create permissions for each collection item
                $p_view = Permission::create(['name' => 'view_' . $item ]);
                $p_delete = Permission::create(['name' => 'delete_' . $item]);

                $ids[] = $p_view->id;
                $ids[] = $p_delete->id;

            } elseif($item == 'db-backup'){

                // create permissions for each collection item
                $p_view = Permission::create(['name' => 'view_' . $item ]);
                $p_create = Permission::create(['name' => 'create_' . $item]);

                $ids[] = $p_view->id;
                $ids[] = $p_create->id;


            }
            elseif($item == 'setting'){

               // create permissions for each collection item
                $p_view = Permission::create(['name'   => 'view_' . $item ]);
                $p_update = Permission::create(['name' => 'update_' . $item]);

                $ids[] = $p_view->id;
                $ids[] = $p_update->id;
            }

            else{

                // create permissions for each collection item
                $p_view = Permission::create(['name' => 'view_' . $item ]);

                $p_create = Permission::create(['name' => 'create_' . $item]);

                $p_update = Permission::create(['name' => 'update_' . $item]);

                $p_delete = Permission::create(['name' => 'delete_' . $item]);


                $ids[] = $p_view->id;
                $ids[] = $p_create->id;
                $ids[] = $p_update->id;
                $ids[] = $p_delete->id;
            }


            //create model
            $module = \App\Models\Module::create(['name' => $item, 'display_name' => 'module_' . $item]);

            $module->permissions()->attach($ids);
        });
        // to add accountig module permissions
        // $this->add_accounting_permissions();

        $owner_role =  Role::firstOrCreate(['name' => 'super-admin'] , ['name' => 'user'] )->syncPermissions(Permission::all());

        $user = User::findOrFail(1);

        $user->syncRoles($owner_role)->syncPermissions(Permission::all());
    }

    // private function add_accounting_permissions() {
    //     $module_screens = collect([
    //         "accounts-tree-index" => [
    //             'view', 'create', 'edit', 'delete'
    //         ],
    //         "account-guide-index" => [
    //             'view'
    //         ],
    //         "account-relations" => [
    //             'view', 'create', 'edit', 'delete'
    //         ],
    //         "daily-restrictions" => [
    //             'view', 'create', 'edit', 'delete', 'print', 'export'
    //         ],
    //         "cost-centers" => [
    //             'view', 'create', 'edit', 'delete'
    //         ],
    //         "accounting-general-ledger" => [
    //             'view', 'print', 'export'
    //         ],
    //         "fiscal-years" => [
    //             'view', 'create', 'edit', 'delete'
    //         ],
    //         "trial-balance-index" => [
    //             'view', 'print', 'export'
    //         ],
    //         "balance-sheet-index" => [
    //             'view', 'print', 'export'
    //         ],
    //         "income-list-index" => [
    //             'view', 'print', 'export'
    //         ],
    //         "adverse-restrictions" => [
    //             'view', 'create'
    //         ],
    //     ]);

    //     $module_screens->each(function ($screen_actions ,$screen_name) {
    //         $permission_ids = [];
    //         foreach($screen_actions as $action) {
    //             $p = Permission::create(['name' => $action .'_'. $screen_name]);
    //             array_push($permission_ids ,$p->id);
    //         }
    //         $module = \App\Models\Module::create(['name' => $screen_name, 'display_name' => 'module_' . $screen_name]);
    //         $module->permissions()->attach($permission_ids);
    //     });
    // }
}
