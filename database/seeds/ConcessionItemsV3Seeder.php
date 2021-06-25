<?php

use Illuminate\Database\Seeder;

class ConcessionItemsV3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            'AddStoreTransfer' => [

                'name'=>'Add Store Transfer',
                'model'=>'StoreTransfer',
                'type'=>'add',
            ],

            'GetStoreTransfer' => [

                'name'=>'Get Store Transfer',
                'model'=>'StoreTransfer',
                'type'=>'withdrawal',
            ],
        ];

        foreach ($data as $item) {

            \App\Models\ConcessionTypeItem::create($item);
        }
    }
}
