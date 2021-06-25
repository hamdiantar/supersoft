<div class="row">

    <div class="col-xs-12">

        @if(authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group has-feedback">

                    <label for="inputSymbolAR"
                           class="control-label">{{__('Select Branch')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-file"></span>
                        <select name="branch_id" class="form-control  js-example-basic-single">
                            @foreach(\App\Models\Branch::all() as $branch)

                                <option value="{{$branch->id}}"

                                    {{isset($sparePart) && $sparePart->branch_id == $branch->id ? 'selected':'' }}>

                                    {{$branch->name}}
                                </option>
                            @endforeach
                        </select>
                        {{input_error($errors,'branch_id')}}
                    </div>
                </div>
            </div>
    </div>
    @endif

    <div class="col-md-12">

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAR" class="control-label">{{__('Sub Type In Arabic')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                    <input type="text" name="type_ar" class="form-control" id="inputNameAR"
                           placeholder="{{__('Type In Arabic')}}" value="{{isset($sparePart) ? $sparePart->type_ar :''}}">
                </div>
                {{input_error($errors,'type_ar')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="inputNameEN" class="control-label">{{__('Sub Type In English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                    <input type="text" name="type_en" class="form-control" id="inputNameEN"
                           placeholder="{{__('Type In English')}}"  value="{{isset($sparePart) ? $sparePart->type_en :''}}">
                </div>
                {{input_error($errors,'type_en')}}
            </div>
        </div>

    </div>

    <div class="col-md-12">

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="logo" class="control-label">{{__('Image')}}</label>

                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-photo"></li></span>

                    <input type="file" name="image" class="form-control" id="image"
                           placeholder="{{__('Image')}}" onchange="preview_image(event)">
                </div>

                <img src="{{isset($sparePart) ?  asset('storage/images/spare-parts/' . $sparePart->image) : '' }}"
                     style="max-width:300px; max-height: 130px" id="output_image"/>

                {{input_error($errors,'image')}}
            </div>

        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAR" class="control-label">{{__('Main Type')}}</label>

                <div class="input-group">

                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>

                    <select name="spare_part_id" class="form-control js-example-basic-single">
                        <option value="">{{__('Select Main Type')}}</option>
                        @foreach($parts_types as $k=>$v)
                            <option value="{{$k}}" {{isset($sparePart) && $sparePart->spare_part_id == $k ? "selected":'' }}>{{$v}}</option>
                        @endforeach
                    </select>

                </div>

                {{input_error($errors,'spare_part_id')}}
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="col-md-6">
            <label for="inputPhone" class="control-label">{{__('Status')}}</label>
            <div class="switch primary" style="margin-top: 15px">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                <label for="switch-1">{{__('Active')}}</label>
            </div>
        </div>
    </div>

    <div class="form-group col-sm-12">
        @include('admin.buttons._save_buttons')
    </div>
</div>
