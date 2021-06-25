<div class="row">


<div class="col-xs-12">
<div class="col-md-12">

@if(authIsSuperAdmin())
    <div class="form-group has-feedback">
        <label for="inputPhone" class="control-label">{{__('Branches')}}</label>
        <div class="input-group">
            <span class="input-group-addon fa fa-file"></span>
            <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                    onchange="getTypesByBranch()">
                <option value="">{{__('Select Branches')}}</option>
                @foreach($branches as $k => $v)
                    <option value="{{$k}}" {{isset($service) && $service->branch_id == $k? 'selected':''}}>{{$v}}</option>
                @endforeach
            </select>
        </div>
        {{input_error($errors,'branch_id')}}
    </div>
@endif
</div>
</div>


    <div class="col-md-12">


        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Service Name Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                           placeholder="{{__('NameAr')}}"
                           value="{{old('name_ar', isset($service)? $service->name_ar:'')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>
        </div>

        <div class="col-md-4">

            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Service Name En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEn"
                           placeholder="{{__('NameEn')}}"
                           value="{{old('name_en', isset($service)? $service->name_en:'')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Service type')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="type_id" id="services_types_options">
                        <option value="" class="removeToNewData">{{__('Select Service Type')}}</option>
                        @foreach($types as $k => $v)
                            <option value="{{$k}}" {{isset($service) && $service->type_id == $k? 'selected':''}}
                            class="removeToNewData">
                                {{$v}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'type_id')}}
            </div>
        </div>

    </div>

    <div class="col-md-12">



        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Hours')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="number" name="hours" class="form-control" id="inputNameEn"
                           placeholder="{{__('hours')}}" value="{{old('hours', isset($service)? $service->hours:0)}}">
                </div>
                {{input_error($errors,'hours')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Minutes')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="number" name="minutes" class="form-control" id="inputNameEn"
                           placeholder="{{__('minutes')}}"
                           value="{{old('minutes', isset($service)? $service->minutes:0)}}">
                </div>
                {{input_error($errors,'minutes')}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Price')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                    <input type="text" name="price" class="form-control" id="inputNameEn"
                           placeholder="{{__('Price')}}" value="{{old('price', isset($service)? $service->price:0)}}">
                </div>
                {{input_error($errors,'price')}}
            </div>
        </div>

    </div>



    <div class="col-md-12">


        <div class="col-md-4">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Service Description Ar')}}</label>
                <div class="input-group">
        <textarea name="description_ar" class="form-control" rows="4" cols="150"
        >{{old('description_ar', isset($service)? $service->description_ar :'')}}</textarea>
                </div>
                {{input_error($errors,'description_ar')}}
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Service Description En')}}</label>
                <div class="input-group">
        <textarea name="description_en" class="form-control" rows="4" cols="150"
        >{{old('description_en', isset($service)? $service->description_en :'')}}</textarea>
                </div>
                {{input_error($errors,'description_en')}}
            </div>

        </div>



<div class="col-md-4">
    <div class="form-group">
        <label for="inputPhone" class="control-label">{{__('Status')}}</label>
        <div class="switch primary">
            <input type="checkbox" id="switch-1" name="status"{{!isset($service)?'checked':''}}
                    {{isset($service) && $service->status? 'checked':''}}
            >
            <label for="switch-1">{{__('Active')}}</label>
        </div>
    </div>
</div>
</div>
</div>


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
