@if(isset($store))
    <div class="modal-header">

    <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ __('Show store data') }}</h4>

    </div>
    <div class="modal-body">

    <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
        <div class="row">
            <div class="col-md-12">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('Name in Arabic')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" value="{{$store->name_ar}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('Name in English')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" value="{{$store->name_en}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">

                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="creator_phone" class="control-label">{{__('Store Phone')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                            <input type="text" readonly name="store_phone" class="form-control" value="{{$store->store_phone}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="creator_phone" class="control-label">{{__('Store Address')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                            <input type="text" readonly name="store_address" class="form-control" value="{{$store->store_address}}">
                        </div>
                    </div>
                </div>
                </div>

            <div class="col-md-12">
                <div class="col-md-12">

                    <div class="form-group has-feedback">
                        <label for="note" class="control-label">{{__('Notes')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                            <textarea readonly name="note" class="form-control" id="store_address" rows="3">{{$store->note}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </div>
            </div>
        

            <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


            <div class="col-md-12 text-center">
               
                <span style="color: black;font-size: 14px;padding:5px 5px 8px !important;border-radius:0px">
                    {{__('Stores officials')}} </span>
                    <hr style="width: 100px;border-color:#5685CC;margin-top:0;;margin-bottom:0;">
                <br>
                <br>
            </div>
            @if(count($store->storeEmployeeHistories) > 0)
                @foreach($store->storeEmployeeHistories as $employeeHistory)
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Employee Name In Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <input readonly class="form-control" name="name_ar" value="{{optional($employeeHistory->employee)->name_ar}}"
                                       placeholder="{{__('Employee Name In Arabic')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{__('Employee Name In English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <input readonly class="form-control" name="name_en" value="{{optional($employeeHistory->employee)->name_en}}"
                                       placeholder="{{__('Employee Name In English')}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Phone1')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                <input readonly class="form-control" name="phone1" value="{{optional($employeeHistory->employee)->phone1}}"
                                       placeholder="{{__('Phone1')}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Phone2')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                <input readonly class="form-control" name="phone2" value="{{optional($employeeHistory->employee)->phone2}}"
                                       placeholder="{{__('Phone2')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Start Date')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input readonly class="form-control" name="phone2" value="{{$employeeHistory->start}}"
                                       placeholder="{{__('Phone2')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('End Date')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input readonly class="form-control" name="phone2" value="{{$employeeHistory->end}}"
                                       placeholder="{{__('Phone2')}}">
                            </div>
                        </div>
                    </div>

                    <hr style="border-top: 1px solid #2980b9;">
                </div>
                @endforeach
            @else
                <div class="col-md-12">
                  <h3 class="text-center">{{__('No Stores officials')}}</h3>
                </div>
            @endif
        </div>
    </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Close')}}</button>
    </div>
@else
    <div class="modal-header">
        <h4 class="modal-title text-center">{{__('Please Try again')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body bg-danger">
        <h1 class="text-center white">{{__('Some thing went wrong')}}</h1>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
    </div>
@endif
