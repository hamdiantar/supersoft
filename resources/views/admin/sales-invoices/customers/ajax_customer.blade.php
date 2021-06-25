<label for="inputSymbolAR" class="control-label">{{__('Select Customer')}}</label>

<a class="btn btn-danger hvr-shrink pull-left" style="margin-top:-5px;cursor:pointer;font-size:11px;font-weight:bold;padding:2px 2px" data-toggle="modal"
   data-target="#addSupplierForm" title="{{__('Add Customer')}}">
    <i class="fa fa-plus">  </i> {{__('Add New Customer')}}
</a>

<select name="customer_id" class="form-control js-example-basic-single" id="customer_value" onchange="getCustomersBalance()">
    <option value="">{{__('Select Customer')}}</option>
    @foreach($customers as $item)
        <option value="{{$item->id}}" {{isset($customer) && $customer->id == $item->id ? 'selected':''}} data-points="{{$customer->points}}" >
            {{$item->name .'-'. $item->phone1}}
        </option>
    @endforeach
</select>
{{input_error($errors,'customer_id')}}
