<div class="row">

    @if(authIsSuperAdmin())
        <div class="col-md-12">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Branches')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single"
                            {{ isset($workCard) && $workCard->cardInvoice ? 'disabled' : ''}}
                            name="branch_id" onchange="getCustomersByBranch()" id="branch_id">
                        <option value="">{{__('Select Branches')}}</option>
                        @foreach($branches as $k => $v)
                            <option value="{{$k}}" {{isset($workCard) && $workCard->branch_id == $k? 'selected':''}}>
                                {{$v}}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if( isset($workCard) && $workCard->cardInvoice)
                    <input type="hidden" name="branch_id" value="{{$workCard->branch_id}}">
                @endif

                {{input_error($errors,'branch_id')}}
            </div>
        </div>
    @endif

    <div class="col-md-12">

        <div class="form-group has-feedback col-md-4" id="customer_data" style="padding-bottom: 19px;">
            <label for="inputSymbolAR" class="control-label">{{__('Customer Name')}}</label>

            @if(!isset($workCard))
                <a class="btn btn-danger   pull-left"
                   style="margin-top:-5px;cursor:pointer;font-size:11px;font-weight:bold;padding:2px 2px"
                   data-toggle="modal"
                   onclick="setBranchId()" data-target="#addSupplierForm" title="{{__('Add Customer')}}">
                    <i class="fa fa-plus"> </i> {{ __('New Customer')}}
                </a>
            @endif


            <select name="customer_id" class="form-control js-example-basic-single"
                    {{ isset($workCard) && $workCard->cardInvoice ? 'disabled' : ''}}
                    id="customers_options" onchange="selectCustomerCar(1,'select_customer'); customerBalance()">
                <option data-customer-balance="" value="">{{__('Select Customer Name')}}</option>
                @foreach($customers as $customer)
                    <option value="{{$customer->id}}" class="removeToNewData"
                            data-customer-balance="{{ json_encode($customer->get_my_balance() ,true) }}"
                        {{isset($workCard) && $workCard->customer_id == $customer->id ? 'selected':''}}>
                        {{$customer->name .'-'. $customer->phone1}}
                    </option>
                @endforeach
            </select>
            @if( isset($workCard) && $workCard->cardInvoice)
                <input type="hidden" name="customer_id" value="{{$workCard->customer_id}}">
            @endif

            {{input_error($errors,'customer_id')}}
        </div>

        <div class="form-group has-feedback col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('customer Car')}}</label>

                <a data-toggle="modal" data-target="#boostrapModal-2" title="Cars info"
                   onclick="getCustomersCars()"
                   class="btn btn-primary   pull-left"
                   style="margin-top:-5px;cursor:pointer;font-size:12px;padding:2px 2px;display: {{ isset($workCard) && $workCard->cardInvoice ? 'none' : ''}};">
                    <i class="fa fa-plus"> </i> {{__('Select Car')}}
                </a>

                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <input type="text" readonly class="form-control" id="card_car_id"
                           value="{{isset($workCard) ? optional($workCard->car)->type.'-'. optional($workCard->car)->plate_number:''}}">
                    <input type="hidden" name="car_id" class="form-control" id="customer_car_id"
                           value="{{isset($workCard) ? $workCard->car_id : ''}}">
                </div>
                {{input_error($errors,'car_id')}}
            </div>
        </div>

        @if (isset($workCard))
            @php
                $balance = $workCard->customer->get_my_balance();
            @endphp

            <div class="form-group col-md-4">
                <div class="form-group">
                    <label for="type_en" class="control-label">{{ $balance['balance_title'] }}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-money"></li></span>
                        <input class="form-control" disabled
                               value="{{ $balance['balance'] }}"
                        >
                    </div>
                </div>
            </div>
        @else
            <div class="form-group col-md-4">
                <div class="form-group">
                    <label for="type_en" class="control-label" id="balance-title">{{__('Balance')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-money"></li></span>
                        <input class="form-control" disabled id="balance"
                        >
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Receive Car status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="receive_car_status"
                        {{!isset($workCard)?'checked':''}}
                        {{isset($workCard) && $workCard->receive_car_status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Receive Status')}}</label>
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="date" class="control-label">{{__('Receive Car Date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                <input type="date" name="receive_car_date" class="form-control"
                       value="{{isset($workCard)? $workCard->receive_car_date: now()->format('Y-m-d')}}">
            </div>
            {{input_error($errors,'receive_car_date')}}
        </div>

        <div class="form-group col-md-4">
            <div class="form-group">
                <label for="type_en" class="control-label">{{__('Receive Car Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="time" name="receive_car_time" class="form-control"
                           value="{{isset($workCard)? $workCard->receive_car_time : now()->format('h:i')}}"
                    >
                </div>
                {{input_error($errors,'receive_car_time')}}
            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Delivery Car Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-2" name="delivery_car_status"
                        {{!isset($workCard)?'checked':''}}
                        {{isset($workCard) && $workCard->delivery_car_status? 'checked':''}}
                    >
                    <label for="switch-2">{{__('Delivery Status')}}</label>
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="date" class="control-label">{{__('Delivery Car Date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                <input type="date" name="delivery_car_date" class="form-control"
                       value="{{ isset($workCard) ? $workCard->delivery_car_date : now()->format('Y-m-d')}}">
            </div>
            {{input_error($errors,'delivery_car_date')}}
        </div>

        <div class="form-group col-md-4">
            <div class="form-group">
                <label for="type_en" class="control-label">{{__('Delivery Car Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="time" name="delivery_car_time" class="form-control"
                           value="{{ isset($workCard)? $workCard->delivery_car_time : now()->format('h:i')}}"
                    >
                </div>
                {{input_error($errors,'delivery_car_time')}}
            </div>
        </div>

        @if(isset($workCard))
            <div class="form-group has-feedback col-md-3" style="padding-bottom: 19px;">
                <label for="inputSymbolAR" class="control-label">{{__('Card Status')}}</label>

                <select name="status" class="form-control js-example-basic-single">
                    <option value="pending" {{$workCard->status == 'pending' ? 'selected':'' }}>{{__('Pending')}}</option>
                    <option value="processing" {{$workCard->status == 'processing' ? 'selected':'' }}>{{__('Processing')}}</option>
                    <option value="finished" {{$workCard->status == 'finished' ? 'selected':'' }}>{{__('Finished')}}</option>
                    <option value="scheduled" {{$workCard->status == 'scheduled' ? 'selected':'' }}>{{__('Scheduled')}}</option>
                </select>

                {{input_error($errors,'status')}}
            </div>
        @endif


        <div class="form-group col-md-12">
            <div class="form-group">
                <label for="car status" class="control-label">{{__('Car Status')}}</label>
                <textarea id="editor1" name="note" cols="5" rows="5"
                >{{old('note', isset($workCard) && $workCard ? $workCard->note:'')}}</textarea>
            </div>
        </div>

        <input type="hidden" id="save_with_invoice" name="save_type" value="save_with_invoice" disabled>

    </div>

    @if(isset($workCard) && $workCard->status == 'processing')
        @include('admin.work_cards.follow_up.form')
    @endif

    <div class="col-md-12">

        <div class="col-md-9">
            <div class="form-group">
                <button type="submit" onclick="saveWithInvoice()"
                        class="btn hvr-rectangle-in saveAdd-wg-btn hvr-shrink">
                    <i class="ico ico-left fa fa-save"></i>
                    {{__('Save With Invoice')}}
                </button>
                @include('admin.buttons._save_buttons')

            </div>
        </div>

    </div>
</div>


