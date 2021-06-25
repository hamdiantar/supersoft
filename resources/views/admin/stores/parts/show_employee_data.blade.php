<a class="btn btn-wg-show hvr-radial-out hvr-shrink" data-remodal-target="show-{{$id}}">
    <i class="fa fa-eye"></i>
    @if(isset($showInStore))
        {{$employeeData->name}}
    @else
        {{__('Show')}}
    @endif
</a>


<div class="remodal" data-remodal-id="show-{{$id}}" role="dialog" aria-labelledby="modal1Title"
     aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="remodal-content">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('Employee Name In Arabic')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" name="name_ar" value="{{$employeeData->name_ar}}"
                                   placeholder="{{__('Employee Name In Arabic')}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{__('Employee Name In English')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                            <input readonly class="form-control" name="name_en" value="{{$employeeData->name_en}}"
                                   placeholder="{{__('Employee Name In English')}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('Phone1')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input readonly class="form-control" name="phone1" value="{{$employeeData->phone1}}"
                                   placeholder="{{__('Phone1')}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{__('Phone2')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                            <input readonly class="form-control" name="phone2" value="{{$employeeData->phone2}}"
                                   placeholder="{{__('Phone2')}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button data-remodal-action="cancel" class="remodal-cancel">{{__('Close')}}</button>
</div>

