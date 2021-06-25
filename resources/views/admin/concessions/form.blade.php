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
                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                onchange="changeBranch()" {{isset($concession) ? 'disabled':''}}>
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($branches as $branch)
                                <option value="{{$branch->id}}"
                                    {{isset($concession) && $concession->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                >
                                    {{$branch->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{input_error($errors,'branch_id')}}

                    @if(isset($concession))
                        <input type="hidden" name="branch_id" value="{{$concession->branch_id}}">
                    @endif
                </div>
            </div>
        </div>
    @endif

    <input type="hidden" id="concession_id_value" value="{{isset($concession)? $concession->id :''}}">

    <div class="col-md-12">

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Concession number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                    <input type="text" name="number" class="form-control" placeholder="{{__('Concession number')}}" disabled
                           value="{{isset($concession)? $concession->add_number : $number}}">
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
                           value="{{old('date', isset($concession)? $concession->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                           value="{{old('time', isset($concession)? $concession->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                </div>
                {{input_error($errors,'time')}}
            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group ">
                <label for="inputStore" class="control-label">{{__('Status')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-info"></span>
                    <select class="form-control js-example-basic-single" name="status" id="status">
                        <!-- <option value="">{{__('Select Status')}}</option> -->
                        <option
                            value="pending" {{isset($concession) && $concession->status == 'pending'? 'selected':'' }}>
                            {{__('Pending')}}
                        </option>
                        <option
                            value="accepted" {{isset($concession) && $concession->status == 'accepted'? 'selected':'' }}>
                            {{__('Accepted')}}
                        </option>
                        <option
                            value="rejected" {{isset($concession) && $concession->status == 'rejected'? 'selected':'' }}>
                            {{__('Rejected')}}
                        </option>

                    </select>
                </div>
                {{input_error($errors,'status')}}
            </div>

        </div>

        <div class="radio primary col-md-3" style="margin-top: 37px;">
            <input type="radio" name="type" value="add" id="add" onclick="showSelectedTypes('add')"
                {{ !isset($concession) ? 'checked':'' }}
                {{isset($concession) && $concession->type == 'add' ? 'checked':''}} >
            <label for="add">{{__('Add Concession')}}</label>
        </div>

        <div class="radio primary col-md-3" style="margin-top: 37px;">
            <input type="radio" name="type" id="withdrawal" value="withdrawal" onclick="showSelectedTypes('withdrawal')"
                {{isset($concession) && $concession->type == 'withdrawal' ? 'checked':''}} >
            <label for="withdrawal">{{__('Withdrawal Concession')}}</label>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Concession Type')}}</label>
                <div class="input-group" id="concession_types">

                    <span class="input-group-addon fa fa-file-text-o"></span>

                    <select class="form-control js-example-basic-single" name="concession_type_id"
                            id="concession_type_id" onchange="getConcessionItems()">

                        <option value="">{{__('Select Type')}}</option>

                        @foreach($concessionTypes as $item)
                            <option value="{{$item->id}}"
                                {{isset($concession) && $concession->concession_type_id == $item->id? 'selected':''}}>
                                {{$item->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{input_error($errors,'concession_type_id')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Doc. number')}}</label>
                <div class="input-group" id="concession_items">

                    <span class="input-group-addon fa fa-bars"></span>

                    <select class="form-control js-example-basic-single" name="item_id" id="concession_item_id" onchange="getConcessionPartsData()">
                        <option value="">{{__('Select Doc. number')}}</option>
                        @if(isset($concession))
                            @foreach($concessionTypeItems as $concessionTypeItem)
                                <option value="{{$concessionTypeItem->id}}" class="remove_items"
                                    {{$concessionTypeItem->id == $concession->concessionable_id ? 'selected':''}}>
                                    {{$concessionTypeItem->number}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{input_error($errors,'item_id')}}
            </div>
        </div>

        <input type="hidden" id="model" name="model" value="{{isset($concession) ? class_basename($concession->concessionable_type) : ''}}">

    </div>

    </div>
  

    <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    @include('admin.concessions.items_table')

    </div>
    </div>
    </div>



    <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total quantity')}}</label>
    <td style="background:#FFC5D7">
                <input type="text" disabled id="item_quantity" style="background:#FFC5D7; border:none;text-align:center !important;" class="form-control"
                       value="{{isset($concession->total_quantity) ? $concession->total_quantity : 0}}" >
                       </td>
                       </tbody>
</table>


<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</label>
    <td style="background:#F9EFB7">
                <input type="text" disabled id="total_price" style="background:#F9EFB7;border:none;text-align:center !important;" value="{{isset($concession) ? $totalPrice : 0}}" class="form-control">
                </td>
  </tbody>
</table>


<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</label>
    <td style="background:#D2F4F6">
                <textarea name="description" style="background:#D2F4F6;border:none;" class="form-control" rows="4" cols="150"
                >{{old('description', isset($concession)? $concession->description :'')}}</textarea>
            </div>
            {{input_error($errors,'description')}}
            </td>
            </tbody>
</table>

                        </div>
                        
