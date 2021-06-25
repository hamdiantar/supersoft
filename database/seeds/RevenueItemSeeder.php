<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenueItemSeeder extends  Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('revenue_items')->insert([
            "item_ar" => 'مدفوعات مرتجعات فاتوره شراء',
            "item_en" => 'purchase invoice return Payments',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "revenue_id" => 122222222,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('revenue_items')->insert([
            "item_ar" => 'مدفوعات فاتوره بيع',
            "item_en" => 'sales invoice Payments',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "revenue_id" => 122222223,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        DB::table('revenue_items')->insert([
            "item_ar" => 'مدفوعات سلف',
            "item_en" => 'advances Payments',
            "status" => 1,
            'branch_id'=> 1,
            'is_seeder'=> 1,
            "revenue_id" => 122222453,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
