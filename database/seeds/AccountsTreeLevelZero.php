<?php

use Illuminate\Database\Seeder;
use App\AccountingModule\Models\AccountsTree;

class AccountsTreeLevelZero extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo 'we just ignore this seeder ,its meaningless without branches';
        return;
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الأصول',
            'name_en' => 'Assets',
            'account_type_name_ar' => 'ميزانية',
            'account_type_name_en' => 'Budget',
            'account_nature' => 'debit',
            'code' => 1
        ]);
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الخصوم و حقوق الملكية',
            'name_en' => 'Liabilities and equity',
            'account_type_name_ar' => 'ميزانية',
            'account_type_name_en' => 'Budget',
            'account_nature' => 'debit',
            'code' => 2
        ]);
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'المصروفات',
            'name_en' => 'Expense',
            'account_type_name_ar' => 'قائمة الدخل',
            'account_type_name_en' => 'Income List',
            'account_nature' => 'credit',
            'code' => 3
        ]);
        AccountsTree::create([
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الإيرادات',
            'name_en' => 'Revenue',
            'account_type_name_ar' => 'قائمة الدخل',
            'account_type_name_en' => 'Income List',
            'account_nature' => 'credit',
            'code' => 4
        ]);
    }
}
