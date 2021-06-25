
<a style="cursor:pointer" class="btn btn-wg-show hvr-radial-out  " data-remodal-target="show{{$id}}">
    <i class="fa fa-eye"></i> {{__('Print')}}
</a>

<div class="remodal" data-remodal-id="show{{$id}}" role="dialog" aria-labelledby="modal1Title"
     aria-describedby="modal1Desc" style="max-width:850px">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content" style="box-shadow:0 0 3px 1px grey;border-radius:5px;padding:10px">
        <div id="printThis{{$id}}">
            <!-- <div class="num-w" style="margin-bottom:15px">
                <div style="border: #AAA solid 1px;border-radius:5px;padding:10px;margin-top:10px">
                    <h3 class="text-center" style="font-weight:bold ">صرف مرتب</h3>
                </div>
            </div> -->

 <div class="print-wg-fatora">
    <div class="row">
    <div class="col-xs-4">

<div style="text-align: right ">
<h5><b> {{optional($branchToPrint)->name_ar}}</b></h5>
                <h5><b>{{__('phone1')}} : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>{{__('phone2')}} : </b> {{optional($branchToPrint)->phone2}} </h5>
                <!-- <h5><b>{{__('address')}} : </b> {{optional($branchToPrint)->address_ar}} </h5> -->
                <h5><b>{{__('fax' )}} : </b> {{optional($branchToPrint)->fax}}</h5>
                <!-- <h5><b>{{__('Tax Card')}} : </b> {{optional($branchToPrint)->tax_card}}</h5> -->
</div>
</div>
<div class="col-xs-4">
    <img style="width: 100px; height: 100px"
        src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">
</div>
<div class="col-xs-4">

<div style="text-align: left" class="my-1">
<h5><b>{{optional($branchToPrint)->name_en}} </b> </h5>
                <h5><b>Phone 1 : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>Phone 2 : </b> {{optional($branchToPrint)->phone2}} </h5>
                <!-- <h5><b>Address : </b> {{optional($branchToPrint)->address_en}} </h5> -->
                <h5><b>Fax : </b> {{optional($branchToPrint)->fax}} </h5>
                <!-- <h5><b>Tax Number : </b> {{optional($branchToPrint)->tax_card}} </h5> -->
</div>
</div>
</div>
</div>
<div class="col-xs-12">
<div class="" style="box-shadow:0 0 3px 1px grey !important;border-radius:5px;padding:10px;margin-bottom:20px">
            @include('admin.employees_salaries.parts.data-container')
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
</div>
@section('js-print')
    <script type="application/javascript">
        function printExpenses(id) {
            var element_id = 'printThis' + id ,page_title = document.title
            print_element(element_id ,page_title)
            return true
        }
    </script>
@endsection
