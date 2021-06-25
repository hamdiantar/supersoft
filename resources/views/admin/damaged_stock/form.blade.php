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
                                onchange="changeBranch()"  {{isset($damagedStock) ? 'disabled':''}}>
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{$branch->id}}"
                                    {{isset($damagedStock) && $damagedStock->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                >
                                    {{$branch->name}}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                    @if(isset($damagedStock))
                        <input type="hidden" name="branch_id" value="{{$damagedStock->branch_id}}">
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
                    <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}" disabled
                           value="{{old('number', isset($damagedStock)? $damagedStock->special_number : $number)}}">
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
                           value="{{old('date', isset($damagedStock)? $damagedStock->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                           value="{{old('time', isset($damagedStock)? $damagedStock->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                </div>
                {{input_error($errors,'time')}}
            </div>
        </div>

        <div class="radio primary col-md-1" style="margin-top: 37px;">
            <input type="radio" name="type" value="natural" id="natural" onchange="showEmployeePercent()"
                {{ !isset($damegeStock) ? 'checked':'' }}
                {{isset($damagedStock) && $damagedStock->type == 'natural' ? 'checked':''}} >
            <label for="natural">{{__('Natural')}}</label>
        </div>

        <div class="radio primary col-md-2" style="margin-top: 37px;">
            <input type="radio" name="type" id="un_natural" value="un_natural" onchange="showEmployeePercent()"
                {{isset($damagedStock) && $damagedStock->type == 'un_natural' ? 'checked':''}} >
            <label for="un_natural">{{__('un_natural')}}</label>
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

                        @foreach($mainTypes as $key=>$type)
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
  
   

    @include('admin.damaged_stock.table_items')

    </div>
   


    <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total quantity')}}</label>
    <td style="background:#FFC5D7">
                <input type="text" disabled id="total_quantity" style="background:#FFC5D7; border:none;text-align:center !important;" class="form-control"
                       value="{{isset($damagedStock) ? $totalQuantity : 0}}">
                       </td>
                       </tbody>
</table>


<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</label>
    <td style="background:#F9EFB7">
                <input type="text" disabled id="total_price"  style="background:#F9EFB7;border:none;text-align:center !important;" value="{{isset($damagedStock) ? $damagedStock->total : 0}}"
                       class="form-control">
                       </td>
  </tbody>
</table>

<table class="table table-bordered">
  <tbody>
    <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</label>
    <td style="background:#D2F4F6">
                <textarea name="description" style="background:#D2F4F6;border:none;" class="form-control" rows="4" cols="150"
                >{{old('description', isset($damagedStock)? $damagedStock->description :'')}}</textarea>
            {{input_error($errors,'description')}}
            </td>
            </tbody>
</table>

                        </div>
  


    <div class="col-md-12" id="employees_percent" style="{{isset($damagedStock) && $damagedStock->type == 'un_natural' ? '':'display: none;' }}">

    <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

     
       
            <label for="inputDescription" class="control-label">{{__('Employees')}}</label>

            <div id="employees_data">

                @if(isset($damagedStock))

                    @foreach($damagedStock->employees as $employees_items_count => $damaged_employee)

                        @php
                            $employees_items_count +=1;
                        @endphp

                        @include('admin.damaged_stock.employees.ajax_employee_percent')
                    @endforeach
                @endif

            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <button type="button" class="btn btn-primary fa fa-plus" onclick="newEmployeesPercent()"></button>
                </div>
            </div>

            </div>
</div>
      

    <input type="hidden" id="employees_count" value="{{isset($damagedStock) ? $damagedStock->employees->count() : 0 }}">
    <input type="hidden" id="employees_items_count"
           value="{{isset($damagedStock) ? $damagedStock->employees->count() : 0 }}">



