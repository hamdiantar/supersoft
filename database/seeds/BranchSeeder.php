<?php

use Illuminate\Database\Seeder;
use App\Services\AccountTreeBranchService;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $currency = \App\Models\Currency::create([

            'name_ar' => 'جنيه',
            'name_en' => 'pound',
            'symbol_ar' => 'LE',
            'symbol_en' => 'LE',
        ]);

        $country = \App\Models\Country::create([

            'name_ar' => 'مصر',
            'name_en' => 'egypt',
            'currency_id' =>  $currency->id,
        ]);

        $city = \App\Models\City::create([

            'name_ar' => 'القاهره',
            'name_en' => 'cairo',
            'country_id' => $country->id,

        ]);

        $area = \App\Models\Area::create([

            'name_ar' => 'رمسيس',
            'name_en' => 'ramses',
            'city_id' =>  $city->id,
        ]);

        $branch = \App\Models\Branch::create([

        'name_ar'            => 'الفرع الرئيسى',
            'name_en'        => 'main branch',
            'country_id'     => $country->id,
            'city_id'        => $city->id,
            'area_id'        => $area->id,
            'currency_id'    => $currency->id,
            'email'          => 'admin@admin.com',
            'address_ar'     => 'القاهره',
            'address_en'     => 'cairo',

        ]);

        (new AccountTreeBranchService())();


    }
}
