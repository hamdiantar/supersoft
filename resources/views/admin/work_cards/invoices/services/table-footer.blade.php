<tfoot style="background-color: #ada3a361">
<tr>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
        <div class="form-group  col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Service Type')}}</label> -->
            <select class="form-control select2 js-example-basic-single"
                    id="service_type_footer_{{$maintenance->id}}" onchange="servicesByTypesFooter({{$maintenance->id}})">
                <option value="">{{ __('Select Service Type') }}</option>
                @foreach($servicesTypes as $servicesType)
                    <option value="{{$servicesType->id}}">{{$servicesType->name}}</option>
                @endforeach
            </select>
        </div>
    </th>
    <th scope="col" colspan="6" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Service')}}</label> -->
            <select class="form-control select2 js-example-basic-single"
                    id="service_footer_id_{{$maintenance->id}}"
                    onchange="getServiceDetails(this.value, '{{$maintenance->id}}',{{$maintenance_type->id}})">
                <option value="" class="removeOldServices_{{$maintenance->id}}">{{ __('Select Service') }}</option>
                @foreach($services as $service)
                    <option value="{{$service->id}}" class="removeOldServices_{{$maintenance->id}}">
                        {{$service->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </th>
</tr>
</tfoot>

