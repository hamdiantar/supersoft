<div class="row small-spacing" id="concession_to_print">


    <div class="print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($purchaseReceipt->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($purchaseReceipt->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($purchaseReceipt->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($purchaseReceipt->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($purchaseReceipt->branch)->tax_card}}</h5>
                </div>
            </div>

            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{$purchaseReceipt->branch->logo_img}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($purchaseReceipt->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($purchaseReceipt->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($purchaseReceipt->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($purchaseReceipt->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($purchaseReceipt->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-center">{{__('Purchase Receipt')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Number')}}</th>
                        <td>{{$purchaseReceipt->number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$purchaseReceipt->time}} - {{$purchaseReceipt->date}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Supply Order Number')}}</th>
                        <td>{{optional($purchaseReceipt->supplyOrder)->number}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Supplier')}}</th>
                        <td>{{optional($purchaseReceipt->supplier)->name}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($purchaseReceipt->user)->name}}</td>
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
                    <th style="background:#CCC !important;color:black">{{__('Total Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Refused Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Accepted Quantity')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($purchaseReceipt->items()->get() as $index=>$item)

                    <tr class="item">
                        <td>{{$index + 1}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</td>
                        <td>{{$item->total_quantity}}</td>
                        <td>{{$item->refused_quantity}}</td>
                        <td>{{$item->accepted_quantity}}</td>
                    </tr>

                @endforeach
                </tbody>

            </table>
        </div>
    </div>



        @if($purchaseReceipt->notes)
            <div class="col-xs-12 wg-tb-snd">
                <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
                    {!! $purchaseReceipt->notes !!}
                </div>
            </div>
        @endif
</div>
