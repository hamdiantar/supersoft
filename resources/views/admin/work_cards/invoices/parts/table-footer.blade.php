<tfoot style="background-color: #ada3a361">
<tr>
    <th scope="col" colspan="3" style="background-color: #EBEBEB !important">
        <div class="form-group  col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Spare Part')}}</label> -->
            <select class="form-control select2 js-example-basic-single"
                    id="parts_types_footer_{{$maintenance->id}}" onchange="partsByTypesFooter({{$maintenance->id}})">
                <option value="">{{ __('Select Spare Part Type') }}</option>
                @foreach($sparPartsTypes as $part)
                    <option value="{{$part->id}}">{{$part->type}}</option>
                @endforeach
            </select>
        </div>
    </th>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Part')}}</label> -->
            <select  class="form-control select2 js-example-basic-single" id="part_footer_id_{{$maintenance->id}}"
                    onchange="getPartsDetails(this.value, '{{$maintenance->id}}',{{$maintenance_type->id}})">
                <option value="" class="removeOldParts_{{$maintenance->id}}">{{ __('Select Spare Part') }}</option>
                @foreach($parts as $part)
                    <option value="{{$part->id}}" class="removeOldParts_{{$maintenance->id}}">{{$part->name}}</option>
                @endforeach
            </select>
        </div>
    </th>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="partOFBarcode" class="control-label">{{__('Select Barcode')}}</label> -->
            <select  class="form-control select2 js-example-basic-single"
                    id="barcode_footer_id_{{$maintenance->id}}"
                    onchange="getPartsDetails(this.value, '{{$maintenance->id}}', {{$maintenance_type->id}})">
                <option value="" class="removeOldParts_{{$maintenance->id}}">{{ __('Select BarCode') }}</option>
                @foreach($parts as $part)
                    @if($part->barcode)
                        <option value="{{$part->id}}" class="removeOldParts_{{$maintenance->id}}">
                            {{$part->barcode}}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </th>
</tr>
</tfoot>

