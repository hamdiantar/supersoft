
<a style="cursor:pointer" class="btn btn-wg-show hvr-radial-out  " onclick="transNumberToLetter({{$id}}, {{$expense->cost}})" data-remodal-target="show{{$id}}">
    <i class="fa fa-eye"></i> {{__('Print')}}
</a>

<div class="remodal" data-remodal-id="show{{$id}}" role="dialog" aria-labelledby="modal1Title"
     aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content" id="printThis{{$id}}" style="box-shadow:0 0 3px 1px grey;border-radius:5px;padding:10px">
            <!-- <h2 id="modal1Title">{{__('Expenses Receipts')}}</h2> -->
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
        <div class="num-w">
            <div style="border: #AAA solid 1px;border-radius:5px;padding:10px;margin-top:10px">
            <h3 class="text-center" style="font-weight:bold ">{{__('Expense receipt')}}</h3>

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
            <td>{{isset($branch) ? $branch->tax_card : ''}}</td>
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
            <div class="master-paid">
                <div>
                    <div class="real-paid">
                        <p class="text-right">{{__('An amount has been disbursed')}}: {{$expense->cost}} {{env('DEFAULT_CURRENCY')}}</p>
                        <p id="totalInLetters{{$id}}" class="text-right"></p>
                    </div>
                    <div>
                                <p class="text-right"> {{__('To the order of Mr. / Gentlemen')}} : {{$expense->receiver}}</p>
                                <p class="text-right">{{__('And for that')}}: {{__($expense->for)}}</p>
                        @if($expense->payment_type === "check")

                        <p class="text-right"> {{__('Payment Type')}} : {{ __("words.check") }}</p>
                        <p class="text-right"> {{__('Bank Name')}} : {{$expense->bank_name}}</p>
                        <p class="text-right">{{__('Check Number')}}: {{__($expense->check_number)}}</p>
                            @endif
                            </div>
                    <div class="row">
                        <div class="col-xs-4 px-2 py-1">

                            <div>
                                <h4>{{__('Manager')}}</h4>
                                <p>.........................</p>
                            </div>
                        </div>
                        <div class="col-xs-4 px-2 py-1">
                            <div>
                                <h4>{{__('The recipient name')}}</h4>
                                <p>{{ $expense->receiver }}</p>
                            </div>
                        </div>
                        <div class="col-xs-4 px-2 py-1">
                        <div>
                                <h4>{{__('cashier')}}</h4>
                                <p>.........................</p>
                            </div>
                       </div>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
    <div style="margin-top:13px">
    <button onclick="printExpenses('{{$id}}')" class="btn btn-primary waves-effect waves-light">
    <i class='fa fa-print'></i>
    {{__('Print')}}</button>
    <button data-remodal-action="cancel" class="btn btn-danger waves-effect waves-light">
    <i class='fa fa-close'></i>
    {{__('Close')}}</button>
    </div>
</div>
@section('js-print')
    <script type="application/javascript">
        function printExpenses(id) {
            var element_id = 'printThis' + id ,page_title = document.title
            print_element(element_id ,page_title)
            return true
        }
        function transNumberToLetter(id, cost) {
            $("#totalInLetters"+id).html( new Tafgeet(cost, '{{env('DEFAULT_CURRENCY')}}').parse())
        }
    </script>
@endsection
