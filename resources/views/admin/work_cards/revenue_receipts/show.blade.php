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
                        <div class="col-md-4">

                            <div style="text-align: right " class="my-1">
                                <h3 >{{$branch->name_ar}}</h3>
                                <h3 >{{$branch->name_en}}</h3>
                                <h3 >{{optional($revenue->createdBy)->name}}</h3>
                                <h3 >{{$branch->address}} </h3>
                                <h3 >{{__('phone')}} : {{$branch->phone1}} </h3>
                                <h3 >{{__('phone')}} : {{$branch->phone2}}  </h3>
                                <h3 >{{__('Fax')}} : {{$branch->fax}}</h3>
                                <h3 >{{__('Mailbox number')}} : {{$branch->mailbox_number}}</h3>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <img class="img-fluid services-img" style="" src="{{url('storage/images/branches/'.$branch->logo)}}">
                        </div>
                        <div class="col-md-4">

                            <div style="text-align: left" class="my-1">
                                <h3>{{$branch->name_ar}}</h3>
                                <h3 >{{$branch->name_en}}</h3>
                                <h3 >{{$branch->address}} </h3>
                                <h3 >{{__('phone')}} : {{$branch->phone1}} </h3>
                                <h3 >{{__('phone')}} : {{$branch->phone2}}  </h3>
                                <h3 >{{__('Fax')}} : {{$branch->fax}}</h3>
                                <h3 >{{__('Mailbox number')}} : {{$branch->mailbox_number}}</h3>
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
                        {{__('رقم السند')}} : {{$revenue->id}} <br>
                    </p>
                </div>
                <div>
                    <h1  style="text-decoration: underline;text-align: center">سند قبض</h1>
                    <div class="mx-2">
                        <p>الرقم الضريبي : 123456789632514</p>
                        <div style="top: -54px;right: 15px;float: right;position: relative;">
                            <p>التاريخ: {{$revenue->date}}</p>
                        </div>
                    </div>
                </div>


            </div>
            <div class="master-paid" >
                <div>
                    <div class="real-paid" style="">
                        <p> أستلمت من السيد : {{$revenue->receiver}}</p>

                    </div>
                    <div class="row">
                        <div class="col-md-6 px-2 py-1">

                            <div>
                                <p>المبلغ: {{$revenue->cost}} ريال</p>
                                <p>وذلك لأجل: {{$revenue->for}}</p>
                            </div>
                            <div >
                                <p>المدير</p>
                                <p>.........................</p>
                            </div>
                        </div>

                        <div class="col-md-6 px-2 py-1">
                            <div>
                                <p>اسم المستلم</p>
                                <p>{{$revenue->receiver}}</p>
                            </div>
                            <div>
                                <p>أمين الصندوق</p>
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
            var element_id = 'printThis' + id ,page_title = document.title
            print_element(element_id ,page_title)
        }
    </script>
@endsection
