
<a class="btn btn-danger   pull-left"
   style="margin-top:-5px;cursor:pointer;font-size:11px;font-weight:bold;padding:2px 2px"
   data-toggle="modal"
   onclick="setBranchId()" data-target="#addSupplierForm" title="{{__('Add Customer')}}">
    <i class="fa fa-plus">  </i> {{ __('Add New Customer')}}
</a>

<select name="customer_id" class="form-control js-example-basic-single">
    <option value="">{{__('Select Customer')}}</option>
    @foreach($customers as $item)

        <option value="{{$item->id}}" class="removeToNewData"
                data-customer-balance="{{ json_encode($customer->get_my_balance() ,true) }}"
                {{isset($customer) && $customer->id == $item->id ? 'selected':''}}
                {{isset($workCard) && $workCard->customer_id == $item->id ? 'selected':''}}>
            {{$item->name .'-'. $item->phone1}}
        </option>
    @endforeach
</select>

{{input_error($errors,'customer_id')}}
