@if (authIsSuperAdmin())
    <div class="form-group has-feedback col-md-12">
        <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
        <select name="branch_id" class="form-control select2 js-example-basic-single" disabled>
            <option value="">{{__('Select Branch')}}</option>
            @foreach($branches as $k=>$v)
                <option value="{{$k}}" {{isset($quotation) && $quotation->branch_id == $k ? "selected" : ""}}>
                    {{$v}}
                </option>
            @endforeach
        </select>

        <input type="hidden" id="branch_id" value="{{$quotation->branch_id}}">

        {{input_error($errors,'branch_id')}}
    </div>
@endif

<div class="form-group  col-md-3">
    <label for="invoice_number" class="control-label">{{__('Quotation Number')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
        <input type="text" name="quotation_number" class="form-control" disabled value="{{$quotation->quo_number}}"
               id="quotation_number" placeholder="{{__('Quotation Number')}}">
    </div>
    {{input_error($errors,'quotation_number')}}
</div>

<div class="form-group col-md-3">
    <div class="form-group">
        <label for="type_en" class="control-label">{{__('Time')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
            <input type="time" name="time" class="form-control"
                   value="{{isset($quotation) && $quotation->time ? $quotation->time : now()->format('h:i')}}"
                   id="time" placeholder="{{__('Time')}}">
        </div>
        {{input_error($errors,'time')}}
    </div>
</div>

<div class="form-group col-md-3">
    <label for="date" class="control-label">{{__('Date')}}</label>
    <div class="input-group">
        <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
        <input type="date" name="date" class="form-control"
               value="{{ isset($quotation) && $quotation->date ? $quotation->date : now()->format('Y-m-d')}}"
               id="date" placeholder="{{__('Date')}}">
    </div>
    {{input_error($errors,'date')}}
</div>
