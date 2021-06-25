<div class="col-md-4">
    <div class="form-group has-feedback">
        <label for="invoice_number" class="control-label">{{__('Quotation Number')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
            <input type="text" name="quotation_number" class="form-control" disabled value="####" id="quotation_number"
                   placeholder="{{__('Quotation Number')}}">
        </div>
        {{input_error($errors,'quotation_number')}}
    </div>
</div>

<div class="col-md-4">
    <div class="form-group has-feedback">
        <div class="form-group">
            <label for="type_en" class="control-label">{{__('Time')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                <input type="time" name="time" class="form-control" value="{{now()->format('h:i')}}"
                       id="time" placeholder="{{__('Time')}}">
            </div>
            {{input_error($errors,'time')}}
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group has-feedback">
        <label for="date" class="control-label">{{__('Date')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
            <input type="date" name="date" class="form-control" value="{{now()->format('Y-m-d')}}"
                   id="date" placeholder="{{__('Date')}}">
        </div>
        {{input_error($errors,'date')}}
    </div>
</div>
