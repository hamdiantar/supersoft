<thead style="background-color: #ada3a361">
<tr>
    <th scope="col" colspan="3" style="background-color: #EBEBEB !important">
        <div class="form-group  col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Spare Part')}}</label> -->
            <select name="part_type_id" class="form-control select2 js-example-basic-single"
                    id="parts_types_footer" onchange="partsByTypesFooter()">
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
            <select name="part_id" class="form-control select2 js-example-basic-single" id="part_footer_id"
                    onchange="getPartsDetails(this.value)">
                <option value="" class="removeOldParts">{{ __('Select Spare Part') }}</option>
                @foreach($parts as $part)
                    <option value="{{$part->id}}" class="removeOldParts">{{$part->name}}</option>
                @endforeach
            </select>
        </div>
    </th>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="partOFBarcode" class="control-label">{{__('Select Barcode')}}</label> -->
            <select name="part_id" class="form-control select2 js-example-basic-single" id="barcode_footer_id"
                    onchange="getPartsDetails(this.value)">
                <option value="" class="removeOldParts">{{ __('Select BarCode') }}</option>
                @foreach($parts as $part)
                    @if($part->barcode)
                        <option value="{{$part->id}}" class="removeOldParts">{{$part->barcode}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </th>
</tr>
</thead>

