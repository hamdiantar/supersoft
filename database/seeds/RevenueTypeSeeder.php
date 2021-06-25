<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('revenue_types')->insert([
            "id" => 122222222,
            "type_en" => 'purchase invoice return',
            "type_ar" => 'مرتجعات فاتوره شراء',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('revenue_types')->insert([
            "id" => 122222223,
            "type_en" => 'sales invoice',
            "type_ar" => 'فاتوره مبيعات',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('revenue_types')->insert([
            "id" => 122222453,
            "type_en" => 'advances',
            "type_ar" => 'سلف',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
