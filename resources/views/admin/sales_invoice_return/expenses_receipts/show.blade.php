<a style="cursor:pointer" data-remodal-target="show{{$id}}">
    <i class="fa fa-eye fa-2x" style="color:cornflowerblue"></i>
</a>

<div class="remodal" data-remodal-id="show{{$id}}" role="dialog" aria-labelledby="modal1Title"
     aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content" id="printThis{{$id}}">
        <h2 id="modal1Title">{{__('Revenues Receipts')}}</h2>
        <div style="border: #000000 solid 1px">
            @if($revenue->branch)
                @php
                    $branch = $revenue->branch;
                    $img = $branch->img;
                    $country = $branch->country->name;
                    $city = $branch->city->name;
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
        </div>
        <div class="num-w">
            <div>
                <div class="num-content" style="">
                    <p>
                    {{__('Receipt No.')}}: {{$expense->id}} <br>
                    </p>
                </div>
                <div>
                    <h1  style="text-decoration: underline;text-align: center">{{__('Tax No.')}}</h1>
                    <div class="mx-2">
                        <p>{{__('Tax No.')}}  : {{optional($expense->branch)->tax_card}}</p>
                        <div style="top: -54px;right: 15px;float: right;position: relative;">
                            <p>{{__('Date')}}: {{$expense->date}}</p>
                        </div>
                    </div>
                </div>


            </div>
            <div class="master-paid" >
                <div>
                    <div class="real-paid" style="">
                        <p> {{__('Received from mr')}} : {{$revenue->receiver}}</p>

                    </div>
                    <div class="row">
                        <div class="col-md-6 px-2 py-1">

                            <div>
                                <p>{{__('The Cost')}} : {{$revenue->cost}} {{env('DEFAULT_CURRENCY')}}</p>
                                <p>{{__('And for that')}} : {{__($revenue->for)}}</p>

                                @if($revenue->payment_type === "check")

                                    <p class="text-right"> {{__('Payment Type')}} : {{ __("words.check") }}</p>
                                    <p class="text-right"> {{__('Bank Name')}} : {{$revenue->bank_name}}</p>
                                    <p class="text-right">{{__('Check Number')}}: {{__($revenue->check_number)}}</p>
                                @endif
                            </div>
                            <div >
                                <p>{{__('Manager')}}</p>
                                <p>.........................</p>
                            </div>
                        </div>

                        <div class="col-md-6 px-2 py-1">
                            <div>
                                <p>{{__('The recipient name')}}</p>
                                <p>{{$revenue->receiver}}</p>
                            </div>
                            <div>
                                <p>{{__('cashier')}}</p>
                                <p>.............................</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <button data-remodal-action="cancel" class="remodal-cancel">{{__('Close')}}</button>
    <button onclick="printExpenses('{{$id}}')" class="btn btn-primary">{{__('Print')}}</button>
</div>
@section('js-print')
    <script type="application/javascript">
        function printExpenses(id) {
            var element_id = "printThis" + id ,page_title = document.title
            print_element(element_id ,page_title)
        }
    </script>
@endsection
