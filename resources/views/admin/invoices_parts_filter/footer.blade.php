<thead style="background-color: #ada3a361">
<tr>
    <th scope="col" colspan="3" style="background-color: #EBEBEB !important">

        <div class="form-group  col-md-12">
            <select name="part_id" class="form-control select2 js-example-basic-single" id="footer_main_part_type" onchange="partsFilterFooter('main_type')">
                <option value="" >{{ __('Select Main Part Type') }}</option>
                @foreach($partsTypes as $partsType)
                    @if(!$partsType->spare_part_id)
                        <option value="{{$partsType->id}}">{{$partsType->type}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </th>

    <th scope="col" colspan="3" style="background-color: #EBEBEB !important">
        <div class="form-group  col-md-12">
            <select name="part_id" class="form-control select2 js-example-basic-single" id="footer_sub_part_type" onchange="partsFilterFooter('sub_type')">
                <option value="" class="removeToNewSubTypes">{{ __('Select Sub Part Type') }}</option>
                @foreach($partsTypes as $partsType)
                    @if($partsType->spare_part_id)
                        <option value="{{$partsType->id}}" class="removeToNewSubTypes">{{$partsType->type}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </th>

    <th scope="col" colspan="3" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <select name="part_id" class="form-control select2 js-example-basic-single" id="footer_filter_parts" onchange="getPartsDetails(this.value)">
                <option value="" class="removeToNewData">{{ __('Select Part') }}</option>
                @foreach($parts as $part)
                    <option value="{{$part->id}}" class="removeToNewData">{{$part->name}}</option>
                @endforeach
            </select>
        </div>
    </th>

    <th scope="col" colspan="3" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
        <!-- <label for="partOFBarcode" class="control-label">{{__('Select Barcode')}}</label> -->
            <select name="part_id" class="form-control select2 js-example-basic-single" id="partOFBarcode" onchange="getPartsDetails(this.value)">

                <option value="" class="removeToNewData">{{ __('Select BarCode') }}</option>

                @foreach($parts as $part)
                    @if($part->barcode)
                        <option value="{{$part->id}}" class="removeToNewData">{{$part->barcode}}</option>
                    @endif
                @endforeach

            </select>
        </div>
    </th>
</tr>

</thead>
