<div class="row small-spacing" id="concession_to_print">

<div style="border: #AAA solid 1px;border-radius:5px 5px 5px 5px;min-height:130px">



<div class="col-xs-4" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
    <div style="text-align: left" class="my-1">
        <h5><b>{{optional($branchToPrint)->name_en}} </b> </h5>
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

    <h4 class="text-center">

    @if($concession->type == 'add' )
      {{ __('Add Concession') }}
    @else
    {{ __('Withdrawal Concession') }} </span>
    @endif

    </h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Number')}}</th>
                        <td>{{$concession->type == 'add' ? $concession->add_number : $concession->withdrawal_number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Time & Date')}}</th>
                        <td>{{$concession->time}} - {{$concession->date}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Item Number')}}</th>
                        <td>{{optional($concession->concessionable)->number}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Execution Status')}}</th>
                        <td>

                        @if($concession->concessionExecution)


                                    @if($concession->concessionExecution ->status == 'pending' )
                                    {{__('Processing')}}

                                    @elseif($concession->concessionExecution ->status == 'finished' )
                                    {{__('Finished')}}

                                    @elseif($concession->concessionExecution ->status == 'late' )
                                    {{__('Late')}}
                                    @endif


                                    @else
                                    {{__('Not determined')}}

                                    @endif

                        </td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>{{__($concession->status)}}</td>
                    </tr>


                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($concession->user)->name}}</td>
                    </tr>



                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Type')}}</th>
                        <td>{{__($concession->type . ' concession')}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Concession Type')}}</th>
                        <td>{{optional($concession->concessionType)->name}}</td>
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
                    <th style="background:#CCC !important;color:black">{{__('Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($concession->concessionItems as $index=>$item)

                    <tr class="item">
                        <td>{{$index + 1}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->price}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total Quantity')}}</th>
                        <td>{{$concession->total_quantity}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total Price')}}</th>
                        <td>{{$concession->total}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div>
        {!! $concession->description !!}
    </div>
</div>


