<?php
namespace App\Services;

use App\AccountingModule\Models\AccountsTree;
use App\Models\Branch;

class AccountTreeBranchService {
    // protected $branch_id;

    // function __constructor($branch_id) {
    //     $this->branch_id = $branch_id;
    // }

    function __invoke()
    {

        $branch_id = Branch::first() ? Branch::first()->id : null;

        $accounts[] = [
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الأصول',
            'name_en' => 'Assets',
            'account_type_name_ar' => 'ميزانية',
            'account_type_name_en' => 'Budget',
            'account_nature' => 'debit',
            'code' => 1,
            'branch_id' => $branch_id
        ];
        $accounts[] = [
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الخصوم و حقوق الملكية',
            'name_en' => 'Liabilities and equity',
            'account_type_name_ar' => 'ميزانية',
            'account_type_name_en' => 'Budget',
            'account_nature' => 'debit',
            'code' => 2,
            'branch_id' => $branch_id
        ];
        $accounts[] = [
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'المصروفات',
            'name_en' => 'Expense',
            'account_type_name_ar' => 'قائمة الدخل',
            'account_type_name_en' => 'Income List',
            'account_nature' => 'credit',
            'code' => 3,
            'branch_id' => $branch_id
        ];
        $accounts[] = [
            'tree_level' => 0,
            'accounts_tree_id' => 0,
            'name_ar' => 'الإيرادات',
            'name_en' => 'Revenue',
            'account_type_name_ar' => 'قائمة الدخل',
            'account_type_name_en' => 'Income List',
            'account_nature' => 'credit',
            'code' => 4,
            'branch_id' => $branch_id
        ];

        AccountsTree::insert($accounts);
    }
}