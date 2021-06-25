<div class="remodal-content for-labels">
    <form  method="post" action="{{ $formRoute }}" class="form" id="part-type-form-content"
        enctype="multipart/form-data">
        @csrf
        @method('post')
        <input type="hidden" name="_id" value="{{ $partType->id }}"/>

        @if(authIsSuperAdmin()) 
            <div class="">
            <h4 class="box-title with-control" style="text-align: initial;">
            {{__('Select Branch')}}
            </h4>
                <div class="form-group has-feedback">
                    <!-- <label for="inputSymbolAR" class="control-label"></label> -->
                    <div class="col-md-12">
                    <div class="input-group" style="margin-bottom:10px">
                        <span class="input-group-addon fa fa-file"></span>
                        <select name="branch_id" class="form-control  js-example-basic-single">
                            <option value=""> {{ __('words.select-one') }} </option>
                            @foreach(\App\Models\Branch::all() as $branch)
                                <option {{ $partType->branch_id == $branch->id ? 'selected' : '' }}
                                    value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-5">
            <div class="form-group">
                <label for="inputNameAR" class="control-label">{{__('Type In Arabic')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                    <input type="text" name="type_ar" class="form-control" value="{{ $partType->type_ar }}"
                        id="inputNameAR" placeholder="{{__('Type In Arabic')}}">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group has-feedback">
                <label for="inputNameEN" class="control-label">{{__('Type In English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                    <input type="text" name="type_en" class="form-control" value="{{ $partType->type_en }}"
                        id="inputNameEN" placeholder="{{__('Type In English')}}">
                </div>
            </div>
        </div>
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group has-feedback">--}}
{{--                <label for="logo" class="control-label">{{__('Image')}}</label>--}}
{{--                <div class="input-group">--}}
{{--                    <span class="input-group-addon"><li class="fa fa-photo"></li></span>--}}
{{--                    <input type="file" name="image" class="form-control" id="image"--}}
{{--                        placeholder="{{__('Image')}}">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-2">
            <label for="inputPhone" class="control-label">{{__('Status')}}</label>
            <div class="switch primary" style="margin-top: 15px">
                <input type="hidden"  name="status" value="0">
                <input type="checkbox" id="switch-1" name="status" value="1" {{ $partType->status == 1 ? 'CHECKED' : '' }}>
                <label for="switch-1">{{__('Active')}}</label>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="form-group col-sm-12">
            <button  class="btn btn-primary waves-effect waves-light" type="submit">
                <i class='fa fa-print'></i>
                {{ __('Save') }}
            </button>

            <button data-remodal-action="cancel" type="button" class="btn btn-danger waves-effect waves-light" onclick="clearSelectedType()">
                <i class='fa fa-close'></i>
                {{ __('Close') }}
            </button>
        </div>
    </form>
</div>

{!! JsValidator::formRequest($validationClass, '#part-type-form-content'); !!}
