<?php

use Illuminate\Database\Seeder;

class ConcessionItemsV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            'DamagedStock' => [

                'name'=>'Damaged Stock',
                'model'=>'DamagedStock',
                'type'=>'withdrawal',
            ],

            'PositiveSettlement' => [

                'name'=>'Positive Settlement',
                'model'=>'Settlement',
                'type'=>'add',
            ],

            'NegativeSettlement' => [

                'name'=>'Negative Settlement',
                'model'=>'Settlement',
                'type'=>'withdrawal',
            ],
        ];

        foreach ($data as $item) {

            \App\Models\ConcessionTypeItem::create($item);
        }
    }
}
