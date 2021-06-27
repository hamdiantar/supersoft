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
                                    {{isset($settlement) ? 'disabled':''}}>
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $branch)
                                        <option
                                            value="{{$branch->id}}" {{isset($settlement) && $settlement->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{input_error($errors,'branch_id')}}
                        </div>
                    </div>

                </div>
            @endif

            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}" disabled
                                   value="{{old('number', isset($settlement)? $settlement->special_number : $number)}}">
                            <input type="hidden" name="number" value="{{$number}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date And Time')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="datetime-local" name="dateTime" class="form-control" id="date"
                                   value="{{old('dateTime', isset($settlement)? $settlement->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'dateTime')}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group ">
                        <label for="inputStore" class="control-label">{{__('Status')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-info"></span>
                            <select class="form-control js-example-basic-single" name="status" id="status">
                                <option
                                    value="pending" {{isset($concession) && $concession->status == 'pending'? 'selected':'' }}>
                                    {{__('Pending')}}
                                </option>
                                <option
                                    value="accept" {{isset($concession) && $concession->status == 'accept'? 'selected':'' }}>
                                    {{__('Accepted')}}
                                </option>
                                <option
                                    value="cancel" {{isset($concession) && $concession->status == 'cancel'? 'selected':'' }}>
                                    {{__('Cancel')}}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'status')}}
                    </div>

                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Notes')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-file"></li></span>
                            <textarea name="notes" class="form-control"></textarea>
                        </div>
                        {{input_error($errors,'notes')}}
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label">{{__('Assets Groups')}}</label>

            <div class="input-group" id="main_types">
                <span class="input-group-addon fa fa-cubes"></span>
                <select class="form-control js-example-basic-single" id="assetsGroups">
                    <option value="">{{__('Select Assets Groups')}}</option>
                    @foreach($assetsGroups as $key => $type)
                        <option value="{{$type->id}}">
                             {{$type->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label">{{__('Assets')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-cube"></span>
                <select class="form-control js-example-basic-single" id="assetsOptions">
                    <option value="">{{__('Select Assets')}}</option>
                    @foreach($assets as $asset)
                        <option value="{{$asset->id}}">
                            {{$asset->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @include('admin.assets_expenses.table_items')
</div>


<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_price"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($settlement) ? $settlement->total : 0}}" class="form-control">
            <input id="total_price_hidden" type="hidden" name="total"  value="{{isset($settlement) ? $settlement->total : 0}}">
        </td>
        </tbody>
    </table>
</div>

