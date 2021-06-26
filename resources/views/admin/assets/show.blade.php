<div class="row small-spacing" id="asset_to_print">


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

    <h4 class="text-center">

      {{ $asset->name }}

    </h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('branch name')}}</th>
                        <td>{{$asset->branch->name }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('group')}}</th>
                        <td>{{$asset->group->name}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('type')}}</th>
                        <td>{{$assetType->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('name')}}</th>
                        <td> {{ $asset->name }}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>@if($asset->asset_status == 1)
                                        {{ __('continues') }}
                                    @elseif($asset->asset_status == 2)
                                        {{ __('sell') }}
                                    @else
                                        {{ __('ignore') }}
                                    @endif</td>
                    </tr>


                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('annual_consumtion_rate')}}</th>
                        <td>{{$asset->annual_consumtion_rate}}</td>
                    </tr>



                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('asset age')}}</th>
                        <td>{{$asset->asset_age}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('purchase date')}}</th>
                        <td>{{$asset->purchase_date}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('date of work')}}</th>
                        <td>{{$asset->date_of_work}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('purchase cost ')}}</th>
                        <td>{{$asset->purchase_cost}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('past consumtion')}}</th>
                        <td>{{$asset->past_consumtion}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('current consumtion')}}</th>
                        <td>{{$asset->current_consumtion}}</td>
                    </tr>




                    </tbody>
                </table>
            </div>

            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('book_value')}}</th>
                        <td>{{$asset->book_value}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('total_current_consumtion')}}</th>
                        <td>{{$asset->total_current_consumtion}}</td>
                    </tr>






                    </tbody>
                </table>
            </div>


        </div>
    </div>

{{--@if($assetEmployees->count())--}}
{{--    <div class="col-xs-12 wg-tb-snd">--}}
{{--        <div style="margin:10px 15px">--}}
{{--            <table class="table table-bordered">--}}
{{--                <thead>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black" colspan="5">{{__('Employees')}}</th>--}}
{{--                </tr>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('Name')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('phone')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('start_date')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('end_date')}}</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}

{{--                @foreach($assetEmployees as $index=>$assetEmployee)--}}

{{--                    <tr class="item">--}}
{{--                        <td>{{$index + 1}}</td>--}}
{{--                        <td>{{$assetEmployee->name}}</td>--}}
{{--                        <td>{{$assetEmployee->phone}}</td>--}}
{{--                        <td>{{$assetEmployee->start_date}}</td>--}}
{{--                        <td>{{$assetEmployee->end_date}}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}

{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}

{{--@if($assetInsurances->count())--}}

{{--    <div class="col-xs-12 wg-tb-snd">--}}
{{--        <div style="margin:10px 15px">--}}
{{--            <table class="table table-bordered">--}}
{{--                <thead>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black" colspan="4">{{__('insurances')}}</th>--}}
{{--                </tr>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('details')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('start_date')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('end_date')}}</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}

{{--                @foreach($assetInsurances as $index=>$assetInsurance)--}}

{{--                    <tr class="item">--}}
{{--                        <td>{{$index + 1}}</td>--}}
{{--                        <td>{{$assetInsurance->insurance_details}}</td>--}}
{{--                        <td>{{$assetInsurance->start_date}}</td>--}}
{{--                        <td>{{$assetInsurance->end_date}}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}

{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--@endif--}}

{{--@if($assetExaminations->count())--}}

{{--    <div class="col-xs-12 wg-tb-snd">--}}
{{--        <div style="margin:10px 15px">--}}
{{--            <table class="table table-bordered">--}}
{{--                <thead>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black" colspan="4">{{__('examinations')}}</th>--}}
{{--                </tr>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('details')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('start_date')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('end_date')}}</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}

{{--                @foreach($assetExaminations as $index=>$assetExamination)--}}

{{--                    <tr class="item">--}}
{{--                        <td>{{$index + 1}}</td>--}}
{{--                        <td>{{$assetExamination->examination_details}}</td>--}}
{{--                        <td>{{$assetExamination->start_date}}</td>--}}
{{--                        <td>{{$assetExamination->end_date}}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}

{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}

{{--@if($assetLicenses->count())--}}


{{--    <div class="col-xs-12 wg-tb-snd">--}}
{{--        <div style="margin:10px 15px">--}}
{{--            <table class="table table-bordered">--}}
{{--                <thead>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black" colspan="4">{{__('licenses')}}</th>--}}
{{--                </tr>--}}
{{--                <tr class="heading">--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('details')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('start_date')}}</th>--}}
{{--                    <th style="background:#CCC !important;color:black">{{__('end_date')}}</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}

{{--                @foreach($assetLicenses as $index=>$assetLicense)--}}

{{--                    <tr class="item">--}}
{{--                        <td>{{$index + 1}}</td>--}}
{{--                        <td>{{$assetLicense->license_details}}</td>--}}
{{--                        <td>{{$assetLicense->start_date}}</td>--}}
{{--                        <td>{{$assetLicense->end_date}}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}

{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endif--}}
    <div>
        {!! $asset->asset_details !!}
    </div>

</div>


