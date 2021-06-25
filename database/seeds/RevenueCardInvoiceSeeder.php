<?php

use App\Models\RevenueType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenueCardInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $revenueTypeCardInvoice = RevenueType::create(
            [
                "type_en" => 'card invoice',
                "type_ar" => 'فاتوره صيانة',
                "status" => 1,
                'branch_id' => 1,
                'is_seeder' => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ]
        );

        DB::table('revenue_items')->insert(
            [
                "item_ar" => 'مدفوعات فاتوره صيانة',
                "item_en" => 'card invoice Payments',
                "status" => 1,
                'branch_id' => 1,
                'is_seeder' => 1,
                "revenue_id" => $revenueTypeCardInvoice->id,
                "created_at" => now(),
                "updated_at" => now(),
            ]
        );
    }
}
