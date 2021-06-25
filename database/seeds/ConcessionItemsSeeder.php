<?php

use Illuminate\Database\Seeder;

class ConcessionItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            'OpeningBalance' => [

                'name'=>'Opening Balance',
                'model'=>'OpeningBalance',
                'type'=>'add',
            ],
        ];

        foreach ($data as $item) {

            \App\Models\ConcessionTypeItem::create($item);
        }
    }
}
