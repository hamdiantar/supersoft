<div class="row">

    @if(authIsSuperAdmin())
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                    <div class="input-group">

                        <span class="input-group-addon fa fa-file"></span>

                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                onchange="changeBranch()" {{isset($purchaseReceipt) ? 'disabled':''}}
                        >
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($data['branches'] as $branch)
                                <option value="{{$branch->id}}"
                                    {{isset($purchaseReceipt) && $purchaseReceipt->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                >
                                    {{$branch->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                    @if(isset($purchaseReceipt))
                        <input type="hidden" name="branch_id" value="{{$purchaseReceipt->branch_id}}">
                    @endif
                </div>

            </div>
        </div>
    @endif

    <div class="col-md-12">

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <li class="fa fa-bars"></li>
                    </span>
                    <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}" disabled
                           value="{{old('number', isset($purchaseReceipt)? $purchaseReceipt->number : $data['number'] )}}">
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                    <input type="date" name="date" class="form-control" id="date"
                           value="{{old('date', isset($purchaseReceipt) ? $purchaseReceipt->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                </div>
                {{input_error($errors,'date')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="time" name="time" class="form-control" id="time"
                           value="{{old('time',  isset($purchaseReceipt) ? $purchaseReceipt->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                </div>
                {{input_error($errors,'time')}}
            </div>
        </div>

        <div class="col-md-3 purchase_request_type"
             style="{{isset($purchaseQuotation) && $purchaseQuotation->type != 'from_purchase_request'? 'display:none':''}}">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Supply Orders')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-file-text-o"></span>

                    <select class="form-control js-example-basic-single" name="supply_order_id"
                            id="supply_order_id" onchange="selectSupplyOrder()">

                        <option value="">{{__('Select')}}</option>

                        @foreach($data['supply_orders'] as $supply_order)
                            <option value="{{$supply_order->id}}"
                                {{isset($purchaseReceipt) && $purchaseReceipt->supply_order_id == $supply_order->id? 'selected':''}}>
                                {{$supply_order->number}} - {{ optional($supply_order->supplier)->name }}
                            </option>
                        @endforeach

                    </select>
                </div>
                {{input_error($errors,'supply_order_id')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Supplier')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-user"></span>
                    <input type="text" id="supplier_id" disabled class="form-control"
                           value="{{isset($purchaseReceipt) && $purchaseReceipt->supplier ? $purchaseReceipt->supplier->name : '---'}}">
                </div>
            </div>
        </div>
    </div>

    @include('admin.purchase_receipts.table_items')

        <div class="col-md-12">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Notes')}}</label>
                <div class="input-group">
                    <textarea name="notes" rows="4" cols="150" class="form-control">{{isset($purchaseReceipt) ? $purchaseReceipt->supplier->notes : ''}}</textarea>
                </div>
            </div>
        </div>

</div>
