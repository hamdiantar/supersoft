<div class="row">
<div class="col-xs-12">

<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


    @if(authIsSuperAdmin())

        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                    <div class="input-group">

                        <span class="input-group-addon fa fa-file"></span>

                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id" onchange="changeBranch()"
                                {{isset($request_type) ? 'disabled' : ''}}
                                {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                        >
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{$branch->id}}" {{isset($purchaseRequest) && $purchaseRequest->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}>
                                    {{$branch->name}}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                    @if(isset($purchaseRequest))
                        <input type="hidden" name="branch_id" value="{{$purchaseRequest->branch_id}}">
                    @endif

                </div>

            </div>
        </div>
    @endif

    <div class="col-md-12">

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Purchase request Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                    <input type="text" name="number" class="form-control" placeholder="{{__('Purchase request Number')}}" disabled
                           value="{{old('number', isset($purchaseRequest)? $purchaseRequest->special_number : $number)}}">
                </div>
                {{input_error($errors,'number')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                    <input type="date" name="date" class="form-control" id="date"
                           {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                           value="{{old('date', isset($purchaseRequest)? $purchaseRequest->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                           value="{{old('time', isset($purchaseRequest)? $purchaseRequest->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                </div>
                {{input_error($errors,'time')}}
            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Status')}}</label>

                <div class="input-group">

                    <span class="input-group-addon fa fa-info"></span>

                    <select class="form-control js-example-basic-single" name="status">

                        <!-- <option value="">{{__('Select Status')}}</option> -->

                        @if(!isset($request_type) || ( isset($request_type) && $request_type != 'approval'))

                            <option value="under_processing"
                                {{isset($purchaseRequest) && $purchaseRequest->status == 'under_processing'? 'selected':'' }}>
                                {{ __('Under Processing') }}
                            </option>
                            <option value="ready_for_approval"
                                {{isset($purchaseRequest) && $purchaseRequest->status == 'ready_for_approval' ? 'selected':''}}>
                                {{ __('Ready For Approval') }}
                            </option>
                        @endif

                        @if(isset($request_type) && $request_type == 'approval')
                            <option value="accept_approval"
                                {{isset($purchaseRequest) && $purchaseRequest->status == 'accept_approval' ? 'selected':''}}>
                                {{ __('Accept Approval') }}
                            </option>

                            <option value="reject_approval"
                                {{isset($purchaseRequest) && $purchaseRequest->status == 'reject_approval' ? 'selected':''}}>
                                {{__('Reject Approval')}}
                            </option>
                        @endif

                    </select>
                </div>
                {{input_error($errors,'status')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Period of request from')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                    <input type="date" name="date_from" class="form-control" id="date_from"
                           {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                           value="{{old('date_from', isset($purchaseRequest)? $purchaseRequest->date_from : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                </div>
                {{input_error($errors,'date_from')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Period of request to')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                    <input type="date" name="date_to" class="form-control" id="date_to"
                           {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                           value="{{old('date_to', isset($purchaseRequest)? $purchaseRequest->date_to : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                </div>
                {{input_error($errors,'date_to')}}
            </div>
        </div>


        
    </div>

</div>


<div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


        @if(!isset($request_type) || isset($request_type) && $request_type == 'edit')

            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Main Types')}}</label>

                    <div class="input-group" id="main_types">

                        <span class="input-group-addon fa fa-cubes"></span>

                        <select class="form-control js-example-basic-single" id="main_types_select"
                                onchange="dataByMainType()">

                            <option value="">{{__('Select Type')}}</option>

                            @foreach($mainTypes as $key => $type)
                                <option value="{{$type->id}}" data-order="{{$key+1}}">
                                    {{$key+1}} . {{$type->type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Sub Types')}}</label>

                    <div class="input-group" id="sub_types">

                        <span class="input-group-addon fa fa-cube"></span>

                        <select class="form-control js-example-basic-single" id="sub_types_select"
                                onchange="dataBySubType()">

                            <option value="">{{__('Select Type')}}</option>

                            @foreach($subTypes as $id => $type)
                                <option value="{{$id}}">
                                    {{$type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Parts')}}</label>

                    <div class="input-group" id="parts">

                        <span class="input-group-addon fa fa-gears"></span>

                        <select class="form-control js-example-basic-single" id="parts_select" onchange="selectPart()">

                            <option value="">{{__('Select Part')}}</option>

                            @foreach($parts as $part)
                                <option value="{{$part->id}}">
                                    {{$part->Prices->first() ? $part->Prices->first()->barcode . ' - ' : ''}} {{$part->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif
 

    @if(isset($request_type) && $request_type == 'approval')

        <input type="hidden" value="approval" name="request_type">
    @endif

    @include('admin.purchase_requests.table_items')

    </div>
    

    
    <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Requesting Party')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-thumbs-o-up"></li></span>
                    <input type="text" name="requesting_party" class="form-control"
                           placeholder="{{__('Requesting Party')}}"
                           {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                           value="{{old('requesting_party', isset($purchaseRequest)? $purchaseRequest->requesting_party :'')}}">
                </div>
                {{input_error($errors,'requesting_party')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Requesting For')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-list"></li></span>
                    <input type="text" name="request_for" class="form-control" placeholder="{{__('Requesting For')}}"
                           {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                           value="{{old('request_for', isset($purchaseRequest)? $purchaseRequest->request_for :'')}}">
                </div>
                {{input_error($errors,'request_for')}}
            </div>
        </div>

        
    <div class="col-md-12">
        <div class="form-group">
            <label for="inputDescription" class="control-label">{{__('Description')}}</label>
            <div class="input-group">
                <textarea name="description" class="form-control" rows="4" cols="150"
                          {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                >{{old('description', isset($purchaseRequest)? $purchaseRequest->description :'')}}</textarea>
            </div>
            {{input_error($errors,'description')}}
        </div>
    </div>
    </div>
    </div>

</div>
