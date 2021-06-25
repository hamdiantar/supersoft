
<!-- <div class="print-wg-fatora"> -->
<!-- <hr> -->
<br>
    <div class="row" style="padding:0px 10px !important;">

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
    <hr>
<!-- </div> -->
