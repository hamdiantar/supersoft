<div class="row">

    <div class="col-md-12">
        @if(authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputSymbolAR" class="control-label">{{__('Branch')}}</label>
                    <select name="branch_id" class="form-control  js-example-basic-single"
                            id="branch_id">
                        <option value="">{{__('Select Branch')}}</option>
                        @foreach(\App\Models\Branch::all() as $branch)
                            <option value="{{$branch->id}}"
                                {{(isset($expensesItem) && $expensesItem->branch_id === $branch->id)
                                    || old('branch_id') === $branch->id ? 'selected' : ''}}>
                                {{$branch->name}}
                            </option>
                        @endforeach
                    </select>
                    {{input_error($errors,'branch_id')}}
                </div>
            </div>
        @endif


        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputSymbolAR" class="control-label">{{__('Select Expense Type')}}</label>
                <select name="assets_type_expenses_id" class="form-control  js-example-basic-single" id="expenseTypes">
                    <option value="">{{__('Select Expense Type')}}</option>
                    @foreach($expensesTypes as $type)
                        <option value="{{$type->id}}"
                            {{(isset($expensesItem) && $expensesItem->assets_type_expenses_id === $type->id)
                                || old('assets_type_expenses_id') === $type->id ? 'selected' : ''}}>
                            {{$type->name}}
                        </option>
                    @endforeach
                </select>
                {{input_error($errors,'assets_type_expenses_id')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAR" class="control-label">{{__('Item in Arabic')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                    <input type="text" name="item_ar" class="form-control" id="inputNameAR"
                           value="{{isset($expensesItem) ? $expensesItem->item_ar : old('item_ar')}}" placeholder="{{__('Item in Arabic')}}">
                </div>
                {{input_error($errors,'item_ar')}}
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="type_en" class="control-label">{{__('Item in English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                    <input type="text" name="item_en" class="form-control" id="item_en"
                           value="{{isset($expensesItem) ? $expensesItem->item_en : old('item_en')}}" placeholder="{{__('Item in English')}}">
                </div>
                {{input_error($errors,'item_en')}}
            </div>
        </div>
    </div>
</div>

