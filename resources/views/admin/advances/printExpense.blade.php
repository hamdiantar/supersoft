<div class="row small-spacing" id="advance_print">

<div style="border: #AAA solid 1px;border-top:20px solid grey;border-radius:15px 15px 5px 5px">
            @if($expense->branch)
                @php
                    $branch = $expense->branch;
                    $img = $branch->img;
                    $country = optional($branch->country)->name;
                    $city = optional($branch->city)->name;
                @endphp
                <div class="clearfix"></div>
                <div class=" srvices-items">
                    <div class="row">
                    <div class="col-xs-12">
                    <div class="col-xs-4">

<div style="text-align: right ">
    <h5><i class="fa fa-home"></i> {{optional($expense->branch)->name_ar}}</h5>
    <h5 ><i class="fa fa-phone"></i> {{optional($expense->branch)->phone1}} </h5>
    <h5 ><i class="fa fa-globe"></i> {{optional($expense->branch)->address}} </h5>
    <h5 ><i class="fa fa-fax"></i> {{optional($expense->branch)->fax}}</h5>
    <h5 ><i class="fa fa-adjust"></i> {{optional($expense->branch)->tax_card}}</h5>
</div>
</div>

                        <div class="col-xs-4">
                            <img class="img-fluid services-img center-block text-center"  style=" width:100px;height:100px;margin:25px auto 0 !important;text-align:center" src="{{url('storage/images/branches/'.$branch->logo)}}">
                        </div>
                        <div class="col-xs-4">

                            <div style="text-align: left" class="my-1">
                                <h5 >{{optional($expense->branch)->name_en}} <i class="fa fa-home"></i> </h5>
                                <h5 >{{optional($expense->branch)->phone1}} <i class="fa fa-phone"></i> </h5>
                                <h5 >{{optional($expense->branch)->address}} <i class="fa fa-globe"></i> </h5>
                                <h5 >{{optional($expense->branch)->fax}} <i class="fa fa-fax"></i> </h5>
                                <h5 >{{optional($expense->branch)->tax_card}} <i class="fa fa-adjust"></i> </h5>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>


<div class="row small-spacing" id="advance_print">
    <!-- <h2 id="modal1Title">{{__('Expenses Receipts')}}</h2> -->
    <!-- <div style="border: #000000 solid 1px">
        @if($expense->branch)
            @php
                $branch = $expense->branch;
                $img = $branch->img;
                $country = optional($branch->country)->name;
                $city = optional($branch->city)->name;
            @endphp
            <div class="container srvices-items">
                <div class="row">
                    <div class="col-xs-4">
                        <div style="text-align: right ">
                            <h5>{{optional($expense->branch)->name_ar}}</h5>
                            <h5 >{{optional($expense->branch)->phone1}} </h5>
                            <h5 >{{optional($expense->branch)->address}} </h5>
                            <h5 >{{optional($expense->branch)->fax}}</h5>
                            <h5 >{{optional($expense->branch)->tax_card}}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img class="img-fluid services-img" style="" src="{{url('storage/images/branches/'.$branch->logo)}}">
                    </div>
                    <div class="col-xs-4">
                        <div style="text-align: left" class="my-1">
                            <h5 >{{optional($expense->branch)->name_en}}</h5>
                            <h5 >{{optional($expense->branch)->phone1}} </h5>
                            <h5 >{{optional($expense->branch)->address}}</h5>
                            <h5 >{{optional($expense->branch)->fax}}</h5>
                            <h5 >{{optional($expense->branch)->tax_card}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div> -->
    <div class="num-w">
    <div style="border: #AAA solid 1px;border-radius:5px;padding:10px;margin-top:10px;margin:15px 10px">
    <h3 style="font-weight:bold;text-align:center"> {{__('Expense receipt')}} </h3>

   <div class="row wg-tb-snd">
      <div class="col-xs-4">
       <table class="table table-bordered">
         <tbody>
            <th style="background-color:#EEE !important;color:black !important">{{__('Receipt No.')}}</th>
            <td>{{$expense->expenses_number}}</td>
         </tbody>
       </table>
      </div>
      <div class="col-xs-4">
      <table class="table table-bordered">
         <tbody>
            <th style="background-color:#EEE !important;color:black !important">{{__('Tax No.')}}</th>
            <td>{{$branch->tax_card}}</td>
         </tbody>
       </table>
      </div>
      <div class="col-xs-4">
      <table class="table table-bordered">
         <tbody>
            <th style="background-color:#EEE !important;color:black !important">{{__('Date')}}</th>
            <td>{{$expense->date}}</td>
         </tbody>
       </table>
      </div>
      </div>

      <p class="text-right">{{__('An amount has been disbursed')}}: {{$expense->cost}} {{env('DEFAULT_CURRENCY')}}</p>
      <p class="text-right">{{__('To the order of Mr. / Gentlemen')}} : {{$expense->receiver}}</p>
      <p class="text-right">{{__('And for that')}} : {{$expense->for}}</p>

      <div class="row">
                        <div class="col-xs-4 px-2 py-1">

                            <div>
                                <h5 style="font-weight:bold">{{__('Manager')}}</h5>
                                <p>.........................</p>
                            </div>
                        </div>
                        <div class="col-xs-4 px-2 py-1">
                            <div>
                                <h5 style="font-weight:bold">{{__('The recipient name')}}</h5>
                                <p>{{ $expense->receiver }}</p>
                            </div>
                        </div>
                        <div class="col-xs-4 px-2 py-1">
                        <div>
                                <h5 style="font-weight:bold">{{__('cashier')}}</h5>
                                <p>{{$expense->receiver}}</p>
                            </div>
                       </div>
                    </div>


    </div>
    <div>

    </div>
</div>
</div>  