                  <div class="row">


<div class="col-xs-12">
<div class="col-md-12">
                        @if(authIsSuperAdmin())
                            <div class="form-group has-feedback">
                                <label for="inputPhone" class="control-label">{{__('Branches')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file"></span>
                                    <select class="form-control js-example-basic-single" name="branch_id">
                                        <option value="">{{__('Select Branches')}}</option>
                                        @foreach($branches as $k => $v)
                                            <option value="{{$k}}" {{isset($services_type) && $services_type->branch_id == $k? 'selected':''}}>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{input_error($errors,'branch_id')}}
                            </div>
                        @endif
                        </div>
</div>

                    <div class="col-md-12">



	
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputNameAr" class="control-label">{{__('Name Ar')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                                    placeholder="{{__('NameAr')}}"
                                    value="{{old('name_ar', isset($services_type)? $services_type->name_ar:'')}}">
                            </div>
                            {{input_error($errors,'name_ar')}}
                        </div>
                        </div>      

                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputName" class="control-label">{{__('Name En')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                <input type="text" name="name_en" class="form-control" id="inputNameEn"
                                    placeholder="{{__('NameEn')}}"
                                    value="{{old('name_en', isset($services_type)? $services_type->name_en:'')}}">
                            </div>
                            {{input_error($errors,'name_en')}}
                        </div>
                        </div>
                                          

                    </div>                  

                  </div>  



                  <div class="col-md-6">
                    <div class="form-group ">
                        <label for="inputDescription" class="control-label">{{__('Description')}}</label>
                        <div class="input-group">
                            <textarea name="description" class="form-control" rows="4" cols="150"
                            >{{old('description', isset($services_type)? $services_type->description :'')}}</textarea>
                        </div>
                        {{input_error($errors,'description')}}
                    </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                        <div class="switch primary">
                            <input type="checkbox" id="switch-1" name="status"{{!isset($services_type)?'checked':''}}
                                    {{isset($services_type) && $services_type->status? 'checked':''}}
                            >
                            <label for="switch-1">{{__('Active')}}</label>
                        </div>
                    </div>
                    </div>  


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
