<div class="form-group has-feedback col-sm-4">
    <label for="inputPhone" class="control-label">{{__('Lockers From')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-lock"></span>
        <select class="form-control js-example-basic-single"
                onchange="hideLockerFromOfLockerTo(); getBalance('from')"
                id="locker_from_id" name="locker_from_id" >
            <option value="">{{__('Select Locker')}}</option>
            @foreach($lockers as $k => $v)
                <option value="{{$k}}" id="locker_from_{{$k}}"
                        {{isset($lockers_transfer) && $lockers_transfer->locker_from_id == $k? 'selected':''}}>
                    {{$v}}
                </option>
            @endforeach
        </select>
    </div>
    {{input_error($errors,'locker_from_id')}}
</div>

<div class="form-group has-feedback col-sm-4">
    <label for="inputPhone" class="control-label">{{__('Lockers To')}}</label>
    <div class="input-group">
        <span class="input-group-addon fa fa-lock"></span>
        <select class="form-control js-example-basic-single" onchange="getBalance('to')"
                id="locker_to_id"  name="locker_to_id" >
            <option value="">{{__('Select Locker')}}</option>
            @foreach($lockers as $k => $v)
                <option value="{{$k}}" id="locker_to_{{$k}}" class="locker_to_options"
                        {{isset($lockers_transfer) && $lockers_transfer->locker_to_id == $k? 'selected':''}}>
                    {{$v}}
                </option>
            @endforeach
        </select>
    </div>
    {{input_error($errors,'locker_to_id')}}
</div>