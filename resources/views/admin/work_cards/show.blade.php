<div class="row small-spacing" id="card_invoice_print">


    <div class="print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($work_card->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($work_card->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($work_card->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($work_card->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($work_card->branch)->tax_card}}</h5>
                </div>
            </div>
            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{isset($work_card->branch->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($work_card->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($work_card->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($work_card->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($work_card->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($work_card->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>

    @if($type != 'image')
        <h4 class="text-center">{{__('Services invoice')}}</h4>
    @else
        <h4 class="text-center">{{__('Work Card Invoice Image')}}</h4>
    @endif

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">


        <div class="row">

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Card Number')}}</th>
                        <td>{{$work_card->card_number}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{ \Illuminate\Support\Carbon::parse($work_card->created_at)->format('Y-m-d')}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Time')}}</th>
                        <td>{{optional($work_card->cardInvoice)->time}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Name')}}</th>
                        <td>{{optional($work_card->customer)->name}}</td>
                    </tr>
                    <!-- <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Phone')}}</th>
                        <td>{{optional($work_card->customer)->phone1 ?? optional($work_card->customer)->phone2}}</td>
                    </tr> -->
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{$work_card->user->name}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>{{optional($work_card->cardInvoice)->remaining == 0 ? __('completed') : __('Remaining')}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Car Type')}}</th>
                        <td>{{optional($work_card->car)->type}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Car Number')}}</th>
                        <td>{{optional($work_card->car)->plate_number}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Type')}}</th>
                        <td>{{optional($work_card->cardInvoice)->type}}</td>
                    </tr>
                    <!-- <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Car Barcode')}}</th>
                        <td>{{optional($work_card->car)->barcode}}</td>
                    </tr> -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>

<!-- @if($work_card->cardInvoice)
    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px"> -->

    <!-- <h4 class="text-center">{{__('Card Invoice')}}</h4> -->
    <!-- <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Number')}}</th>
                        <td>{{optional($work_card->cardInvoice)->invoice_number}}</td>
                    </tr>


                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{optional($work_card->cardInvoice)->date}}</td>
                    </tr>


                    </tbody>
                </table>
            </div>

        </div> -->
    <!-- </div>
    @endif -->


    @if($work_card->cardInvoice && $work_card->cardInvoice->types)
        <div class="col-xs-12 wg-tb-snd">
            <div style="margin:10px 15px">
                <table class="table table-bordered">
                    <thead>
                    <tr class="heading">
                        @if(!$work_card->cardInvoice || $work_card->cardInvoice && $work_card->cardInvoice->maintenanceDetectionTypes->count())
                            <th style="background:#CCC !important;color:black">{{__('Maintenance Detection')}}</th>
                        @endif
                        <th style="background:#CCC !important;color:black">{{__('Spare Part')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Quantity')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($work_card->cardInvoice->types as $type)

                        @foreach($type->items as $item)
                            <tr class="item">
                                @if(!$work_card->cardInvoice || $work_card->cardInvoice && $work_card->cardInvoice->maintenanceDetectionTypes->count())
                                <td>{{optional($type->maintenanceDetection)->name}}</td>
                                @endif
                                <td>
                                    @if($type->type == 'Part')
                                        {{optional($item->part)->name}}
                                    @elseif($type->type == 'Service')
                                        {{optional($item->service)->name}}
                                    @elseif($type->type == 'Package')
                                        {{optional($item->package)->name}}
                                    @else
                                        <span> --- </span>
                                    @endif
                                </td>
                                <td>{{$item->qty}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->discount_type}}</td>
                                <td>{{$item->discount}}</td>
                                <td>{{number_format($item->sub_total, 2)}}</td>
                                <td>{{number_format($item->total_after_discount, 2)}}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @endif


    @if($winchType && $winchType->cardInvoiceWinchRequest)
        <div class="col-xs-12 wg-tb-snd">
            <div style="margin:10px 15px">
            <!-- <h2 style="text-align: center">{{__('Services')}}</h2> -->
                <table class="table table-bordered">
                    <thead>
                    <tr class="heading">
                        <th style="background:#CCC !important;color:black">{{__('Branch lat')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Branch long')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Request lat')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Request long')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Distance')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('SubTotal')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                        <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="item">
                        <td>{{$winchType->cardInvoiceWinchRequest->branch_lat}}</td>
                        <td>{{$winchType->cardInvoiceWinchRequest->branch_long}}</td>
                        <td>{{$winchType->cardInvoiceWinchRequest->request_lat}}</td>
                        <td>{{$winchType->cardInvoiceWinchRequest->request_long}}</td>
                        <td>{{$winchType->cardInvoiceWinchRequest->distance}}</td>
                        <td>{{$winchType->cardInvoiceWinchRequest->price}}</td>
                        <td>{{$winchType->cardInvoiceWinchRequest->sub_total}}</td>
                        <td>{{__($winchType->cardInvoiceWinchRequest->discount_type)}}</td>
                        <td>{{$winchType->cardInvoiceWinchRequest->discount}}</td>
                        <td>{{number_format($winchType->cardInvoiceWinchRequest->total, 2)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif


    @if($work_card->cardInvoice)
        <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">

        <!-- <h4 class="text-center" style="font-weight:bold;margin-bottom:15px">{{__('Invoice Total')}}</h4> -->
            <div class="row">
                <div class="col-xs-4">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="background:#CCC !important;color:black"
                                scope="row">{{__('Total in Numbers')}}</th>
                            <td>{{$work_card->cardInvoice->total}}</td>
                        </tr>
                        <tr>
                        <!-- <th style="background:#CCC !important;color:black" scope="row">{{__('Total in letters')}}</th> -->
                            <td id="totalInLetters">{{$work_card->cardInvoice->total}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-xs-4">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Paid')}}</th>
                            <td>{{$work_card->cardInvoice->paid}}</td>
                        </tr>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Remaining')}}</th>
                            <td>{{$work_card->cardInvoice->remaining}}</td>
                        </tr>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Taxes')}}</th>
                            <td>{{$work_card->cardInvoice->tax}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-4">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Discount')}} </th>
                            <td>{{$work_card->cardInvoice->discount}}</td>
                        </tr>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Type')}}</th>
                            <td>{{$work_card->cardInvoice->discount_type}}</td>
                        </tr>
                        <tr>
                            <th style="background:#CCC !important;color:black"
                                scope="row">{{__('Customer Discount')}}</th>
                            <td>{{$work_card->cardInvoice->customer_discount}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                {{--            <div class="col-xs-12 wg-tb-snd">--}}
                {{--                <div style="margin:10px 15px">--}}
                {{--                    <table class="table table-bordered">--}}
                {{--                        <thead>--}}
                {{--                        <tr class="heading">--}}
                {{--                            <th style="background:#CCC !important;color:black">{{__('Tax Name')}}</th>--}}
                {{--                            <th style="background:#CCC !important;color:black">{{__('Tax Type')}}</th>--}}
                {{--                            <th style="background:#CCC !important;color:black">{{__('Tax Value')}}</th>--}}
                {{--                        </tr>--}}
                {{--                        </thead>--}}
                {{--                        <tbody>--}}
                {{--                        @foreach($taxes as $tax)--}}
                {{--                            <tr class="item">--}}
                {{--                                <td>{{$tax->name}}</td>--}}
                {{--                                <td>{{$tax->tax_type}}</td>--}}
                {{--                                <td>{{$tax->value}}</td>--}}
                {{--                            </tr>--}}
                {{--                        @endforeach--}}
                {{--                        <tr class="item">--}}
                {{--                            <th style="background:#CCC !important;color:black" colspan="2">{{__('Total Tax')}}</th>--}}
                {{--                            <td>{{$totalTax}}</td>--}}
                {{--                        </tr>--}}
                {{--                        </tbody>--}}
                {{--                    </table>--}}
                {{--                </div>--}}
                {{--            </div>--}}
            </div>
        </div>
    @endif

    {{-- Seetings   --}}
    <div>
        @if($setting)
            {!! $setting->SalesInvoiceTerms !!}
        @endif
    </div>

    @if($work_card->cardInvoice)
        <div>
            {!!$work_card->cardInvoice->terms!!}
        </div>
    @endif
</div>


