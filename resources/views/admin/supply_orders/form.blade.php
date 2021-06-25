<div class="row">

    @if(authIsSuperAdmin())
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                    <div class="input-group">

                        <span class="input-group-addon fa fa-file"></span>

                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                onchange="changeBranch()" {{isset($supplyOrder) ? 'disabled':''}}
                        >
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($data['branches'] as $branch)
                                <option value="{{$branch->id}}"
                                    {{isset($supplyOrder) && $supplyOrder->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                >
                                    {{$branch->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                    @if(isset($supplyOrder))
                        <input type="hidden" name="branch_id" value="{{$supplyOrder->branch_id}}">
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
                    <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                    <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}"
                           value="{{old('number', isset($supplyOrder)? $supplyOrder->number :'')}}">
                </div>
                {{input_error($errors,'number')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Type')}}</label>

                <div class="input-group">

                    <span class="input-group-addon fa fa-info"></span>

                    <select class="form-control js-example-basic-single" name="type" id="quotation_type"
                            onchange="quotationType()">

                        <option value="from_purchase_request"
                            {{isset($supplyOrder) && $supplyOrder->type == 'from_purchase_request'? 'selected':'' }}>
                            {{ __('From Purchase Request') }}
                        </option>

                        <option value="normal"
                            {{isset($supplyOrder) && $supplyOrder->type == 'normal'? 'selected':'' }}>
                            {{ __('Normal') }}
                        </option>

                    </select>
                </div>
                {{input_error($errors,'type')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                    <input type="date" name="date" class="form-control" id="date"
                           {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                           value="{{old('date', isset($supplyOrder) ? $supplyOrder->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                           {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                           value="{{old('time',  isset($supplyOrder) ? $supplyOrder->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                </div>
                {{input_error($errors,'time')}}
            </div>
        </div>

        <div class="col-md-3 purchase_request_type"
             style="{{isset($purchaseQuotation) && $purchaseQuotation->type != 'from_purchase_request'? 'display:none':''}}">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Purchase Requests')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-file-text-o"></span>

                    <select class="form-control js-example-basic-single" name="purchase_request_id"
                            id="purchase_request_id" onchange="quotationType()">
                        <option value="">{{__('Select')}}</option>

                        @foreach($data['purchaseRequests'] as $purchaseRequest)
                            <option value="{{$purchaseRequest->id}}"
                                {{isset($supplyOrder) && $supplyOrder->purchase_request_id == $purchaseRequest->id? 'selected':''}}>
                                {{$purchaseRequest->special_number}}
                            </option>
                        @endforeach

                    </select>
                </div>
                {{input_error($errors,'purchase_request_id')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-user"></span>

                    <select class="form-control js-example-basic-single" name="supplier_id" id="supplier_id" onchange="quotationType(); selectSupplier()">
                        <option value="">{{__('Select')}}</option>

                        @foreach($data['suppliers'] as $supplier)
                            <option value="{{$supplier->id}}"
                                    data-discount="{{$supplier->group_discount}}"
                                    data-discount-type="{{$supplier->group_discount_type}}"
                                {{isset($supplyOrder) && $supplyOrder->supplier_id == $supplier->id? 'selected':''}}>
                                {{$supplier->name}}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{input_error($errors,'supplier_id')}}
            </div>
        </div>

        <div class="col-md-3 purchase_request_type">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Purchase Quotations')}}</label>

                <div class="input-group">
                    <button type="button" onclick="getPurchaseQuotations(); quotationType()"
                            class="btn btn-primary waves-effect waves-light btn-xs">
                        {{__('Get Purchase Quotations')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light btn-xs"
                            data-toggle="modal" data-target="#purchase_quotations" style="margin-right: 10px;">
                        {{__('Show Quotations')}}
                    </button>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Status')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-info"></span>

                    <select class="form-control js-example-basic-single" name="status">

                    <!-- <option value="">{{__('Select Status')}}</option> -->

                        <option value="pending"
                            {{isset($supplyOrder) && $supplyOrder->status == 'pending'? 'selected':'' }}>
                            {{ __('processing') }}
                        </option>

                        <option value="accept"
                            {{isset($supplyOrder) && $supplyOrder->status == 'accept' ? 'selected':''}}>
                            {{ __('Accept') }}
                        </option>

                        <option value="reject"
                            {{isset($supplyOrder) && $supplyOrder->status == 'reject' ? 'selected':''}}>
                            {{ __('Reject') }}
                        </option>

                    </select>
                </div>
                {{input_error($errors,'status')}}
            </div>
        </div>

        <div class="col-md-3 out_purchase_request_type"
             style="{{isset($supplyOrder) && $supplyOrder->type == 'from_purchase_request'? 'display:none':''}}
             {{!isset($supplyOrder) ? 'display:none':''}}">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Main Types')}}</label>
                <div class="input-group" id="main_types">

                    <span class="input-group-addon fa fa-cubes"></span>

                    <select class="form-control js-example-basic-single" id="main_types_select"
                            onchange="dataByMainType()">

                        <option value="">{{__('Select Type')}}</option>

                        @foreach($data['mainTypes'] as $key => $type)
                            <option value="{{$type->id}}" data-order="{{$key+1}}">
                                {{$key+1}} . {{$type->type}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-3 out_purchase_request_type"
             style="{{isset($supplyOrder) && $supplyOrder->type == 'from_purchase_request'? 'display:none':''}}
             {{!isset($supplyOrder) ? 'display:none':''}}">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Sub Types')}}</label>
                <div class="input-group" id="sub_types">

                    <span class="input-group-addon fa fa-cube"></span>

                    <select class="form-control js-example-basic-single" id="sub_types_select"
                            onchange="dataBySubType()">

                        <option value="">{{__('Select Type')}}</option>

                        @foreach($data['subTypes'] as $id=>$type)
                            <option value="{{$id}}">
                                {{$type}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-3 out_purchase_request_type"
             style="{{isset($supplyOrder) && $supplyOrder->type == 'from_purchase_request'? 'display:none':''}}
             {{!isset($supplyOrder) ? 'display:none':''}}">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Parts')}}</label>
                <div class="input-group" id="parts">

                    <span class="input-group-addon fa fa-gears"></span>

                    <select class="form-control js-example-basic-single" id="parts_select" onchange="selectPart()">

                        <option value="">{{__('Select Part')}}</option>

                        @foreach($data['parts'] as $part)
                            <option value="{{$part->id}}">
                                {{$part->Prices->first() ? $part->Prices->first()->barcode . ' - ' : ''}} {{$part->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @include('admin.supply_orders.table_items')

    @include('admin.supply_orders.financial_details')

    <table id="purchase_quotations_selected" style="display: none">

        @if(isset($supplyOrder))
            @include('admin.supply_orders.real_purchase_quotations', ['purchaseQuotations'=> $data['purchaseQuotations'],
            'supply_order_quotations' => $supplyOrder->purchaseQuotations->pluck('id')->toArray()])
        @endif

    </table>

</div>
