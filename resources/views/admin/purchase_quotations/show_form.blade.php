<div class="row">

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <input type="text" class="form-control" disabled
                           value="{{optional($purchaseRequest->branch)->name}}">
                </div>

            </div>
        </div>
    </div>


    <div class="col-md-12">

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-pagelines"></li></span>
                    <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}" disabled
                           value="{{ $purchaseRequest->special_number }}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                    <input type="date" name="date" class="form-control" id="date" disabled value="{{ $purchaseRequest->date}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                    <input type="time" name="time" class="form-control" id="time" disabled
                           value="{{$purchaseRequest->time}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('User')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                    <input type="text" name="user" class="form-control" disabled
                           value="{{$purchaseRequest->user->name}}">
                </div>

            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Status')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-pagelines"></li></span>
                    <input type="text" class="form-control" placeholder="{{__('requesting_party')}}"
                           value="{{__($purchaseRequest->status)}}" disabled>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date From')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                    <input type="date" name="date_from" class="form-control" id="date_from"
                           value="{{$purchaseRequest->date_from}}" disabled>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date To')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                    <input type="date" name="date_to" class="form-control" value="{{$purchaseRequest->date_to}}" disabled>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Requesting Party')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-pagelines"></li></span>
                    <input type="text" name="requesting_party" class="form-control" placeholder="{{__('requesting_party')}}"
                           value="{{$purchaseRequest->requesting_party}}" disabled>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Requesting For')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-pagelines"></li></span>
                    <input type="text" name="request_for" class="form-control" placeholder="{{__('request for')}}"
                           value="{{$purchaseRequest->request_for}}" disabled>
                </div>
            </div>
        </div>


{{--        <div class="radio primary col-md-1" style="margin-top: 37px;">--}}
{{--            <input type="radio" name="type" value="positive" id="positive" disabled--}}
{{--                {{ !isset($settlement) ? 'checked':'' }}--}}
{{--                {{isset($settlement) && $settlement->type == 'positive' ? 'checked':''}} >--}}
{{--            <label for="positive">{{__('Positive')}}</label>--}}
{{--        </div>--}}

{{--        <div class="radio primary col-md-2" style="margin-top: 37px;">--}}
{{--            <input type="radio" name="type" id="negative" value="negative" disabled--}}
{{--                {{isset($settlement) && $settlement->type == 'negative' ? 'checked':''}} >--}}
{{--            <label for="negative">{{__('Negative')}}</label>--}}
{{--        </div>--}}

    </div>

    <div class="col-md-12">
        <table class="table table-responsive table-bordered table-striped">
            <thead>
            <tr>
                <th width="16%"> {{ __('Name') }} </th>
                <th width="12%"> {{ __('Unit') }} </th>
                <th width="5%"> {{ __('Quantity') }} </th>
                <th width="5%"> {{ __('Approval Quantity') }} </th>
            </tr>
            </thead>
            <tbody id="parts_data">
            @if(isset($purchaseRequest))

                @foreach ($purchaseRequest->items as $index => $item)
                    @php
                        $index +=1;
                        $part = $item->part;
                    @endphp
                    <tr id="tr_part_{{$index}}">
                        <td>
                            <input type="text" disabled value="{{$part->name}}" class="form-control" style="text-align: center;">
                        </td>

                        <td>
                            <div class="input-group">
                                <input type="text" disabled value="{{optional($item->partPrice->unit)->unit}}" class="form-control" style="text-align: center;">
                            </div>
                        </td>

                        <td>
                            <input type="number" class="form-control" disabled
                                   value="{{isset($item) ? $item->quantity : 0}}" min="0" name="items[{{$index}}][quantity]">
                        </td>

                        <td>
                            <input type="text" disabled class="form-control" value="{{isset($item) ? $item->approval_quantity : 0}}" >
                        </td>

                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="16%"> {{ __('Name') }} </th>
                <th width="12%"> {{ __('Unit') }} </th>
                <th width="5%"> {{ __('Quantity') }} </th>
                <th width="5%"> {{ __('Approval Quantity') }} </th>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="inputDescription" class="control-label">{{__('Description')}}</label>
            <div class="input-group">
                <textarea name="description" class="form-control" rows="4" cols="150" disabled
                >{{$purchaseRequest->description}}</textarea>
            </div>
        </div>
    </div>


</div>
