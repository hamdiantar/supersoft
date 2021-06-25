<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expenses_items')->insert([
            "item_ar" => 'مدفوعات فاتوره مشتريات',
            "item_en" => 'purchase invoice Payments',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "expense_id" => 1232,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('expenses_items')->insert([
            "item_ar" => 'مرتجعات فاتوره مبيعات',
            "item_en" => 'sales invoice return Payments',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "expense_id" => 3435,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('expenses_items')->insert([
            "item_ar" => 'مدفوعات سلف',
            "item_en" => 'advances Payments',
            "status" => 1,
            'branch_id'=> 1,
            "expense_id" => 23232,
            "created_at" => now(),
            "updated_at" => now(),
        ]);


        DB::table('expenses_items')->insert([
            "item_ar" => 'مدفوعات مرتبات',
            "item_en" => 'salaries Payments',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "expense_id" => 34343,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
