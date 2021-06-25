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
                                onchange="changeBranch()"
                            {{isset($settlement) ? 'disabled':''}}>
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{$branch->id}}" {{isset($openingBalance) && $openingBalance->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                >
                                    {{$branch->name}}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                </div>

                @if(isset($openingBalance))
                    <input type="hidden" name="branch_id" value="{{$openingBalance->branch_id}}">
                @endif

            </div>

        </div>
    @endif

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                    <input type="text" name="serial_number" class="form-control" placeholder="{{__('Number')}}" disabled
                           value="{{old('serial_number', isset($openingBalance)? $openingBalance->serial_number : $number)}}">
                </div>
                {{input_error($errors,'serial_number')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                    <input type="date" name="operation_date" class="form-control" id="date"
                           value="{{old('operation_date', isset($openingBalance)? $openingBalance->operation_date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                </div>
                {{input_error($errors,'operation_date')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="time" name="operation_time" class="form-control" id="time"
                           value="{{old('operation_time', isset($openingBalance)? $openingBalance->operation_time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                </div>
                {{input_error($errors,'operation_time')}}
            </div>
        </div>

        </div>
        </div>
        </div>
        </div>
        
<div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

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

                        @foreach($subTypes as $id=>$type)
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

                    <span class="input-group-addon fa fa-gear"></span>

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

   

    @include('admin.opening_balance.table_items')

    </div>
    
    <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{ __('opening-balance.quantity') }}</th>
    <td style="background:#FFC5D7">
     <input type="text" disabled id="total_quantity" class="form-control" style="background:#FFC5D7; border:none;text-align:center !important;"
                       value="{{isset($openingBalance) ? $openingBalance->items->sum('quantity') : 0}}">
</td>
                       </tbody>
</table>


<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{ __('opening-balance.total') }}</th>
   <td style="background:#F9EFB7">
     <input type="text" disabled id="total_price" style="background:#F9EFB7;border:none;text-align:center !important;" value="{{isset($openingBalance) ? $openingBalance->total_money : 0}}"
                       class="form-control">
                       </td>
  </tbody>
</table>

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{ __('opening-balance.notes') }}</th>
      <td style="background:#D2F4F6">
        <textarea name="notes" style="background:#D2F4F6;border:none;" class="form-control" rows="4" cols="150"
                >{{old('notes', isset($openingBalance)? $openingBalance->notes :'')}}</textarea>
          
            {{input_error($errors,'notes')}}
            </td>
            </tbody>
</table>

                        </div>

