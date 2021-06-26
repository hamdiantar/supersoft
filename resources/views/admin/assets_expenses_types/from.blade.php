<div class="row">

    <div class="col-md-12">
        @if(authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputSymbolAR"
                           class="control-label">{{__('Select Branch')}}</label>
                    <select name="branch_id" class="form-control  js-example-basic-single">
                        @foreach(\App\Models\Branch::all() as $branch)
                            <option value="{{$branch->id}}"
                            {{(isset($assetsTypeExpense) && $assetsTypeExpense->branch_id === $branch->id)
                                || old('branch_id') === $branch->id ? 'selected' : ''}}>
                                {{$branch->name}}
                            </option>
                        @endforeach
                    </select>
                    {{input_error($errors,'branch_id')}}
                </div>
            </div>
        @endif

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAR" class="control-label">{{__('Type in Arabic')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                           value="{{isset($assetsTypeExpense) ? $assetsTypeExpense->name_ar : old('name_ar')}}" placeholder="{{__('Type in Arabic')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="type_en" class="control-label">{{__('Type in English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                    <input type="text" name="name_en" class="form-control" id="type_en"
                           value="{{isset($assetsTypeExpense) ? $assetsTypeExpense->name_en : old('name_en')}}" placeholder="{{__('Type in English')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>
    </div>
</div>
