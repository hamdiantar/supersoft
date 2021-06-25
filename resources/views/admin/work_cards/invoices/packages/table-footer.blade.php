<tfoot style="background-color: #ada3a361">
<tr>
{{--    <th scope="col" colspan="3">--}}
{{--        <div class="form-group  col-md-12">--}}
{{--            <label for="inputSymbolAR" class="control-label">{{__('Select Service Type')}}</label>--}}
{{--            <select name="part_id" class="form-control select2 js-example-basic-single"--}}
{{--                    id="service_type_footer" onchange="servicesByTypesFooter()">--}}
{{--                <option value="">{{ __('Select Spare Part Type') }}</option>--}}
{{--                @foreach($servicesTypes as $servicesType)--}}
{{--                    <option value="{{$servicesType->id}}">{{$servicesType->name}}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </th>--}}
    <th scope="col" colspan="12" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Package')}}</label> -->
            <select class="form-control select2 js-example-basic-single"
                    id="package_footer_id_{{$maintenance->id}}"
                    onchange="getPackageDetails( $('#package_footer_id_'+'{{$maintenance->id}}').val(),
                            '{{$maintenance->id}}',{{$maintenance_type->id}})">
                <option value="" >{{ __('Select Package') }}</option>
                @foreach($packages as $package)
                    <option value="{{$package->id}}">{{$package->name}}</option>
                @endforeach
            </select>
        </div>
    </th>
</tr>
</tfoot>

