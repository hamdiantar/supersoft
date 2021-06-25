<thead style="background-color: #ada3a361">
<tr>
    <th scope="col" colspan="3" style="background-color: #EBEBEB !important">
        <div class="form-group  col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Spare Part')}}</label> -->
            <select name="part_id" class="form-control select2 js-example-basic-single" id="Spare_part_type">
                <option value="">{{ __('Select Spare Part Type') }}</option>
                @foreach($partsTypes as $part)
                    <option value="{{$part->id}}">{{$part->type}}</option>
                @endforeach
            </select>
        </div>
    </th>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="inputSymbolAR" class="control-label">{{__('Select Spare Part')}}</label> -->
            <select name="part_id" class="form-control select2 js-example-basic-single" id="partsSelect"
                    onchange="getPartsDetails(this.value)">
                <option value="">{{ __('Select Spare Part') }}</option>
                @foreach($parts as $part)
                    <option value="{{$part->id}}">{{$part->name}}</option>
                @endforeach
            </select>
        </div>
    </th>
    <th scope="col" colspan="4" style="background-color: #EBEBEB !important">
        <div class="form-group col-md-12">
            <!-- <label for="partOFBarcode" class="control-label">{{__('Select Barcode')}}</label> -->
{{--            <select name="part_id" class="form-control select2 js-example-basic-single" id="partOFBarcode"--}}
{{--                    onchange="getPartsDetails(this.value)">--}}
{{--                <option value="">{{ __('Select BarCode') }}</option>--}}
{{--                @foreach($parts as $part)--}}
{{--                    @if($part->barcode)--}}
{{--                        <option value="{{$part->id}}">{{$part->barcode}}</option>--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--            </select>--}}
                <div class="col-md-10">
                    <input type="text" class="form-control" id="partOFBarcodes" >
                </div>
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-sm btn-default" onclick="getPartsDetails()"> add </button>
                </div>
        </div>
    </th>
</tr>
</thead>
@section('js')
    <script type="application/javascript">
        $("#Spare_part_type").on('change', function () {
            $.ajax({
                url: "{{ route('admin:getPartsBySparePartID') }}?spare_part_type_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#partsSelect').html(data.parts);
                    $('#partOFBarcode').html(data.barcode);
                }
            });
        });
    </script>
@endsection
