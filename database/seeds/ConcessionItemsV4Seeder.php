<?php

use Illuminate\Database\Seeder;

class ConcessionItemsV4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            'PurchaseReceipt' => [
                'name'=>'Purchase Receipt',
                'model'=>'PurchaseReceipt',
                'type'=>'add',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\ConcessionTypeItem::create($item);
        }
    }
}
