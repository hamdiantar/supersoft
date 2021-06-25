<div class="row small-spacing" id="sales_invoice_print">


    <div class="print-wg-fatora">
        <div class="row">
        <div class="col-xs-4">

<div style="text-align: right ">
    <h5><i class="fa fa-home"></i> {{optional($invoice->branch)->name_ar}}</h5>
    <h5 ><i class="fa fa-phone"></i> {{optional($invoice->branch)->phone1}} </h5>
    <h5 ><i class="fa fa-globe"></i> {{optional($invoice->branch)->address}} </h5>
    <h5 ><i class="fa fa-fax"></i> {{optional($invoice->branch)->fax}}</h5>
    <h5 ><i class="fa fa-adjust"></i> {{optional($invoice->branch)->tax_card}}</h5>
</div>
</div>
            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{isset($invoice->branch->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">
            </div>
            <div class="col-xs-4">

<div style="text-align: left" class="my-1">
    <h5 >{{optional($invoice->branch)->name_en}} <i class="fa fa-home"></i> </h5>
    <h5 >{{optional($invoice->branch)->phone1}} <i class="fa fa-phome"></i> </h5>
    <h5 >{{optional($invoice->branch)->address}} <i class="fa fa-globe"></i> </h5>
    <h5 >{{optional($invoice->branch)->fax}} <i class="fa fa-fax"></i> </h5>
    <h5 >{{optional($invoice->branch)->tax_card}} <i class="fa fa-adjust"></i> </h5>
</div>
</div>
        </div>
    </div>

<h4 class="text-center">{{__('Return Sales Invoice')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">

        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Number')}}</th>
                        <td>{{$invoice->invoice_number}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$invoice->date}}</td>
                    </tr>
                    <!-- <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Type')}}</th>
                        <td>{{$invoice->type}}</td>
                    </tr> -->
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <!-- <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Time')}}</th>
                        <td>{{$invoice->time}}</td>
                    </tr> -->
                    <tr>

                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($invoice->user)->name}}</td>
                    </tr>
                    <tr>
                    <th style="background:#CCC !important;color:black" scope="row">{{__('Payment status')}}</th>
                        <td>{{$invoice->remaining == 0 ? __('Completed') : __('Not Completed')}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                    <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Name')}}</th>
                        <td>{{optional($invoice->customer)->name}}</td>
                    </tr>
                    <tr>
                    <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Phone')}}</th>
                        <td>{{optional($invoice->customer)->phone1 ?? optional($invoice->customer)->phone2}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
            <table class="table table-bordered">


                <tr class="heading">
                <th style="background:#CCC !important;color:black">{{__('Unit')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Type')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('spart')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Return Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Selling Price')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->items as $item)

                    <tr class="item">
                        <td>{{optional($item->part->sparePartsUnit)->unit}}</td>
                        <td>{{optional($item->part->sparePartsType)->type}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->return_qty}}</td>
                        <td>{{$item->selling_price}}</td>
                        <td>{{__($item->discount_type)}}</td>
                        <td>{{$item->discount}}</td>
                        <td>{{number_format($item->total_before_discount, 2)}}</td>
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
                        @foreach($taxes as $tax)
                            <tr class="item">
                                <td>{{$tax->name}}</td>
                                <td>{{__($tax->tax_type)}}</td>
                                <td>{{$tax->value}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr class="item">
                            <th style="background:#CCC !important;color:black" colspan="2">{{__('Total Tax')}}</th>
                            <td>{{$totalTax}}</td>
                            <td>{{$invoice->tax}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 10px 20px;padding:10px;border-radius:5px">

        <div class="row">

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Paid')}}</th>
                        <td>{{$invoice->paid}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Remaining')}}</th>
                        <td>{{$invoice->remaining}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Discount')}} </th>
                        <td>{{$invoice->discount}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Type')}}</th>
                        <td>{{__($invoice->discount_type)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total in Numbers')}}</th>
                        <td>{{$invoice->total}}</td>
                    </tr>
                    <tr>
                        <!-- <th style="background:#CCC !important;color:black" scope="row">{{__('Total in letters')}}</th> -->
                        <td id="totalInLetters">{{$invoice->total}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


