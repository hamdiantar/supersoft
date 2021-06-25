<label for="inputSymbolAR" class="control-label">{{__('Select Supplier')}}</label>

<a class="btn btn-danger   pull-left"
   style="margin-top:-5px;cursor:pointer;font-size:11px;font-weight:bold;padding:2px 2px"
   data-toggle="modal"
   onclick="setBranchIdForSupplier()" data-target="#boostrapModal-2"
   title="{{__('Add Supplier')}}">
    <i class="fa fa-plus"></i> {{__('New Supplier')}}
</a>

<select name="supplier_id" class="form-control js-example-basic-single" id="supplier_value"
        onchange="getSupplierBalance()">
    <option value="">{{__('Select Supplier')}}</option>
    @foreach($suppliers as $item)
        <option value="{{$item->id}}"
                {{isset($supplier) && $supplier->id == $item->id ? 'selected':''}}>
            {{$item->name .'-'. $item->phone_1}}
        </option>
    @endforeach
</select>
{{input_error($errors,'supplier_id')}}
