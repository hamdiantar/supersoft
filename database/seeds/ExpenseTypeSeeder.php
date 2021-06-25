<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expenses_types')->insert([
            "id" => 1232,
            "type_en" => 'purchase invoice',
            "type_ar" => 'فاتوره مشتريات',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('expenses_types')->insert([
            "id" => 3435,
            "type_en" => 'sales invoice return',
            "type_ar" => 'مرتجعات فاتوره المبيعات',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('expenses_types')->insert([
            "id" => 23232,
            "type_en" => 'advances',
            "type_ar" => 'السلف',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('expenses_types')->insert([
            "id" => 34343,
            "type_en" => 'salaries',
            "type_ar" => 'مرتبات',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
