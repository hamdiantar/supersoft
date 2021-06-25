<div class="row small-spacing" id="concession_to_print">

    <div class="row" style="padding:0px 10px !important;">

        <div class="col-xs-4" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
            <div style="text-align: left" class="my-1">
                <h5><b>{{optional($branchToPrint)->name_en}} </b></h5>
                <h5><b>Phone 1 : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>Phone 2 : </b> {{optional($branchToPrint)->phone2}} </h5>
            <!-- <h5><b>Address : </b> {{optional($branchToPrint)->address_en}} </h5> -->
                <h5><b>Fax : </b> {{optional($branchToPrint)->fax}} </h5>
            <!-- <h5><b>Tax Number : </b> {{optional($branchToPrint)->tax_card}} </h5> -->
            </div>
        </div>
        <div class="col-xs-4 text-center" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
            <img style="width: 200px; height: 100px"
                 src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">

        </div>
        <div class="col-xs-4">
            <div style="text-align: right">
                <h5><b> {{optional($branchToPrint)->name_ar}}</b></h5>
                <h5><b>{{__('phone1')}} : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>{{__('phone2')}} : </b> {{optional($branchToPrint)->phone2}} </h5>
            <!-- <h5><b>{{__('address')}} : </b> {{optional($branchToPrint)->address_ar}} </h5> -->
                <h5><b>{{__('fax' )}} : </b> {{optional($branchToPrint)->fax}}</h5>
            <!-- <h5><b>{{__('Tax Card')}} : </b> {{optional($branchToPrint)->tax_card}}</h5> -->
            </div>
        </div>
    </div>

    <hr>

    <h4 class="text-center">{{__($purchaseRequest->type . ' Purchase Request')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Purchase request Number')}}</th>
                        <td>{{$purchaseRequest->special_number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$purchaseRequest->time}} - {{$purchaseRequest->date}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Username')}}</th>
                        <td>{{optional($purchaseRequest->user)->name}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>{{__($purchaseRequest->status)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Type')}}</th>
                        <td>{{__($purchaseRequest->type)}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Requesting Party')}}</th>
                        <td>{{__($purchaseRequest->requesting_party)}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Period of request from')}}</th>
                        <td>{{__($purchaseRequest->date_from)}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Period of request to')}}</th>
                        <td>{{__($purchaseRequest->date_to )}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Requesting For')}}</th>
                        <td>{{__($purchaseRequest->request_for)}}</td>
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
                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Name')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Unit')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Requested Qty')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Approval Quantity')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($purchaseRequest->items as $index=>$item)

                    <tr class="item">
                        <td>{{$index + 1}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->approval_quantity}}</td>
                    </tr>

                    @if($item->spareParts->count())
                        <tr class="item">
                            <td>{{__('types')}}</td>
                            <td colspan="5">

                                @foreach($item->spareParts as $sparePart)
                                    <button class="btn btn-primary btn-xs">{{ $sparePart->type }}</button>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    @if($purchaseRequest->description)
        <div class="col-xs-12 wg-tb-snd">
            <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
                {!! $purchaseRequest->description !!}
            </div>
        </div>
    @endif
</div>
