
<a class="btn btn-danger   pull-left"
   style="margin-top:-5px;cursor:pointer;font-size:11px;font-weight:bold;padding:2px 2px"
   data-toggle="modal"
   onclick="setBranchId()" data-target="#addSupplierForm" title="{{__('Add Customer')}}">
    <i class="fa fa-plus">  </i> {{ __('New Customer')}}
</a>

<select name="customer_id" class="form-control js-example-basic-single"
        {{ isset($workCard) && $workCard->cardInvoice ? 'disabled' : ''}}
        id="customers_options" onchange="selectCustomerCar(1,'select_customer'); customerBalance()">
    <option data-customer-balance="" value="">{{__('Select Customer Name')}}</option>
    @foreach($customers as $customer)
        <option value="{{$customer->id}}" class="removeToNewData"
                data-customer-balance="{{ json_encode($customer->get_my_balance() ,true) }}"
{{--                {{isset($workCard) && $workCard->customer_id == $customer->id ? 'selected':''}}--}}
        >
            {{$customer->name .'-'. $customer->phone1}}
        </option>
    @endforeach
</select>

{{input_error($errors,'customer_id')}}

