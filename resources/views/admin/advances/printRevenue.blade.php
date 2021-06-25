
<div style="box-shadow:0 0 3px 1px #AAA;padding:10px;border-radius:5px">

<div style="border: #AAA solid 1px;border-top:20px solid grey;border-radius:15px 15px 5px 5px" id="advance_print">
<div class="clearfix"></div>

@if($revenue->branch)
            @php
                $branch = $revenue->branch;
                $img = $branch->img;
                $country = $branch->country->name;
                $city = $branch->city->name;
            @endphp
                <div class=" srvices-items">
                    <div class="row">
                    <div class="col-xs-12">

                    <div class="col-xs-4">

                    <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($revenue->branch)->name_ar}}</h5>
    <h5 > <i class="fa fa-phone"></i> {{optional($revenue->branch)->phone1}} </h5>
    <h5 > <i class="fa fa-globe"></i> {{optional($revenue->branch)->address}} </h5>
    <h5 > <i class="fa fa-fax"></i> {{optional($revenue->branch)->fax}}</h5>
    <h5 ><i class="fa fa-adjust"></i> {{optional($revenue->branch)->tax_card}}</h5>
                    </div>
                    </div>

                        <div class="col-xs-4">
                        <img class="img-fluid services-img" style="" src="{{url('storage/images/branches/'.$branch->logo)}}">
                        </div>
                        <div class="col-xs-4">

                            <div style="text-align: left" class="my-1">
                            <h5 >{{optional($revenue->branch)->name_en}} <i class="fa fa-home"></i> </h5>
    <h5 >{{optional($revenue->branch)->phone1}} <i class="fa fa-phone"></i> </h5>
    <h5 >{{optional($revenue->branch)->address}} <i class="fa fa-globe"></i> </h5>
    <h5 >{{optional($revenue->branch)->fax}} <i class="fa fa-fax"></i> </h5>
    <h5 >{{optional($revenue->branch)->tax_card}} <i class="fa fa-adjust"></i> </h5>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                @endif

                    </div>



<!-- <div class="row small-spacing">
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
    <h5>{{optional($revenue->branch)->name_ar}}</h5>
    <h5 >{{optional($revenue->branch)->phone1}} </h5>
    <h5 >{{optional($revenue->branch)->address}} </h5>
    <h5 >{{optional($revenue->branch)->fax}}</h5>
    <h5 >{{optional($revenue->branch)->tax_card}}</h5>
</div>
</div>

                    <div class="col-md-4">
                        <img class="img-fluid services-img" style="" src="{{url('storage/images/branches/'.$branch->logo)}}">
                    </div>
                    <div class="col-xs-4">

<div style="text-align: left" class="my-1">
    <h5 >{{optional($revenue->branch)->name_en}}</h5>
    <h5 >{{optional($revenue->branch)->phone1}} </h5>
    <h5 >{{optional($revenue->branch)->address}}</h5>
    <h5 >{{optional($revenue->branch)->fax}}</h5>
    <h5 >{{optional($revenue->branch)->tax_card}}</h5>
</div>
</div>
                </div>
            </div>
        @endif
    </div> -->



    <div class="row wg-tb-snd">

    <!-- <h4 style="text-align: center">{{__('Revenue receipt')}}</h4> -->
    <h2 id="modal1Title">{{__('Revenues Receipts')}}</h2>


      <div class="col-xs-4">
       <table class="table table-bordered">
         <tbody>
            <tr><th style="background-color:#EEE !important;color:black !important">{{__('Receipt No.')}}</th>
            <td>{{$revenue->id}}</td>
         </tr></tbody>
       </table>
      </div>
      <div class="col-xs-4">
      <table class="table table-bordered">
         <tbody>
            <tr><th style="background-color:#EEE !important;color:black !important">{{__('Tax No.')}}</th>
            <td>1234567896</td>
         </tr></tbody>
       </table>
      </div>
      <div class="col-xs-4">
      <table class="table table-bordered">
         <tbody>
            <tr><th style="background-color:#EEE !important;color:black !important">{{__('Date')}}</th>
            <td>{{$revenue->date}}</td>
         </tr></tbody>
       </table>
      </div>


      

            </div>







    <div class="num-w">

        <div class="master-paid" >
            <div>
                <div class="real-paid" style="text-align:right">
                    <p>  {{__('Received from mr')}} : {{$revenue->receiver}}</p>
                    <p>{{__('The Cost')}} : {{$revenue->cost}} ريال</p>
                    <p>{{__('And for that')}} : {{$revenue->for}}</p>
                </div>

                <div class="row" style="font-weight:bold">
                    <div class="col-xs-4 px-2 py-1">

  
                        <div >
                            <p>{{__('Manager')}}</p>
                            <p>.........................</p>
                        </div>
                    </div>

                    <div class="col-xs-4 px-2 py-1">
                        <div>
                            <p>{{__('The recipient name')}}</p>
                            <p>{{$revenue->receiver}}</p>
                        </div>

                    </div>

                    <div class="col-xs-4 px-2 py-1">

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
</div>