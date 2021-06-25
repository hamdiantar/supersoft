<thead style="background-color: #ada3a361">
<tr>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Service Type')}}</label> -->
            <select name="part_id" class="form-control select2 js-example-basic-single"
                    id="service_type_footer" onchange="servicesByTypesFooter()">
                <option value="">{{ __('Select Spare Part Type') }}</option>
                @foreach($servicesTypes as $servicesType)
                    <option value="{{$servicesType->id}}">{{$servicesType->name}}</option>
                @endforeach
            </select>
    </th>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Service')}}</label> -->
            <select name="service_footer_id" class="form-control select2 js-example-basic-single" id="service_footer_id"
                    onchange="getServiceDetails(this.value)">
                <option value="" class="removeOldServices">{{ __('Select Service') }}</option>
                @foreach($services as $service)
                    <option value="{{$service->id}}" class="removeOldServices">{{$service->name}}</option>
                @endforeach
            </select>
    </th>
</tr>
</thead>

