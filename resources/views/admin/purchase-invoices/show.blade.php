<div class="row small-spacing" id="purchase_invoice_print">


    <div class="print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($purchase_invoice->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($purchase_invoice->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($purchase_invoice->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($purchase_invoice->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($purchase_invoice->branch)->tax_card}}</h5>
                </div>
            </div>
            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{isset($purchase_invoice->branch->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($purchase_invoice->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($purchase_invoice->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($purchase_invoice->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($purchase_invoice->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($purchase_invoice->branch)->tax_card}}<i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-center">{{__('Purchase Invoice')}}</h4>
    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">


        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Number')}}</th>
                        <td>{{$purchase_invoice->invoice_number}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$purchase_invoice->date}}</td>
                    </tr>
                    <!-- <tr>
                <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Type')}}</th>
                <td>{{__($purchase_invoice->type)}}</td>
                </tr> -->
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <!-- <tr>
                <th style="background:#CCC !important;color:black" scope="row">{{__('Time')}}</th>
                <td>{{$purchase_invoice->time}}</td>
                </tr> -->
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{auth()->user()->name}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Payment status')}}</th>
                        <td>{{$purchase_invoice->remaining == 0 ? __('Completed') : __('Not Completed')}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Supplier Name')}}</th>
                        <td>{{optional($purchase_invoice->supplier)->name}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Supplier Phone')}}</th>
                        <td>{{optional($purchase_invoice->supplier)->phone_1}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
            <table class="table table-bordered">

                <thead>
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('Unit')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Price Segment')}}</th>
                    {{--                    <th style="background:#CCC !important;color:black">{{__('Type')}}</th>--}}
                    <th style="background:#CCC !important;color:black">{{__('spart')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Purchase Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Purchase Price')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Store')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Tax')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total')}}</th>

                </tr>
                </thead>
                <tbody>
                @foreach($purchase_invoice->items as $item)

                    <tr class="item">
                        <td>{{optional($item->partPrice->unit)->unit}}</td>
                        <td>
                            @if($item->partPrice->getPivotPrice($item->part_price_segment_id))
                                {{$item->partPrice->getPivotPrice($item->part_price_segment_id)->name}}
                            @else
                                <span>---</span>
                            @endif
                        </td>
                        {{--                        <td>{{optional($item->part->sparePartsType)->type}}</td>--}}
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->purchase_qty}}</td>
                        <td>{{$item->purchase_price}}</td>
                        <td>{{optional($item->store)->name}}</td>
                        <td>{{__($item->discount_type)}}</td>
                        <td>{{$item->discount}}</td>
                        <td>{{number_format(($item->total_after_discount - $item->tax),2)}}</td>
                        <td>{{number_format($item->tax, 2)}}</td>
                        <td>{{number_format($item->total_after_discount, 2)}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
            <table class="table table-bordered">
                <thead>
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('Tax Name')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Tax Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Tax Value')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Invoice Tax')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($purchase_invoice->taxes as $tax)
                    <tr class="item">
                        <td>{{$tax->name}}</td>
                        <td>{{__($tax->tax_type)}}</td>
                        <td>{{$tax->value}}</td>
                        <td><span>---</span></td>
                    </tr>
                @endforeach

                <tr class="item">
                    <th style="background:#CCC !important;color:black" colspan="2">{{__('Total Tax')}}</th>
{{--                    <td>{{$totalTax}}</td>--}}
                    <td>{{$purchase_invoice->taxes()->sum('value')}}</td>
                    <td>{{$purchase_invoice->tax}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">

    <!-- <h4 class="text-center" style="font-weight:bold;margin-bottom:15px">{{__('Buy Invoice')}}</h4> -->
        <div class="row">

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Type')}}</th>
                        <td>{{__($purchase_invoice->discount_type)}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Discount')}} </th>
                        <td>{{$purchase_invoice->discount}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>


            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Paid')}}</th>
                        <td>{{$purchase_invoice->paid}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Remaining')}}</th>
                        <td>{{$purchase_invoice->remaining}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total in Numbers')}}</th>
                        <td>{{$purchase_invoice->total}}</td>
                    </tr>
                    <tr>
                    <!-- <th style="background:#CCC !important;color:black" scope="row">{{__('Total in letters')}}</th> -->
                        <td id="totalInLetters">{{$purchase_invoice->total}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


