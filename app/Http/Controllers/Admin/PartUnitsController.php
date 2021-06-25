<?php

namespace App\Http\Controllers\Admin;

use App\Models\PartPrice;
use App\Models\PriceSegment;
use App\Models\SparePartUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PartUnitsController extends Controller
{
    public $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    public function newUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'units_count' => 'required|integer|min:0'
        ]);

        if ($validator->failed()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['units_count'] + 1;

            $partUnits = SparePartUnit::whereNotIn('id', $request['selectedUnitIds'])->select('id','unit_' . $this->lang)->get();

            $view = view('admin.parts.units.ajax_form_new_unit',
                compact('index', 'partUnits'))->render();

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['view' => $view, 'index' => $index], 200);
    }

    public function destroy (Request $request) {

        $validator = Validator::make($request->all(), [

            'price_id' => 'required|integer|exists:part_prices,id'
        ]);

        if ($validator->failed()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $price = PartPrice::find($request['price_id']);
            $price->delete();

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['message' => __('part unit deleted successfully')], 200);
    }

    public function update (Request $request) {

        $validator = Validator::make($request->all(), [

            'price_id' => 'required|integer|exists:part_prices,id',
            'quantity' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'less_selling_price' => 'required|numeric|min:0',
            'service_selling_price' => 'required|numeric|min:0',
            'less_service_selling_price' => 'required|numeric|min:0',
            'maximum_sale_amount' => 'required|numeric|min:0',
            'minimum_for_order' => 'required|numeric|min:0',
            'biggest_percent_discount' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|min:2',
        ]);

        if ($validator->failed()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $data = $request->only(['quantity', 'selling_price', 'purchase_price', 'less_selling_price',
               'service_selling_price', 'less_service_selling_price', 'maximum_sale_amount',
                'minimum_for_order', 'biggest_percent_discount', 'barcode']);

            DB::table('part_prices')->where('id',$request['price_id'])->update($data);

        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 400);
        }

        return response()->json(['message' => __('part unit updated successfully')], 200);
    }


}
