<div class="row">

    @if(authIsSuperAdmin())
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                    <div class="input-group">

                        <span class="input-group-addon fa fa-file"></span>

                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id">
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{$branch->id}}" {{isset($supplyTerm) && $supplyTerm->branch_id == $branch->id? 'selected':''}}>
                                    {{$branch->name}}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                </div>

            </div>
        </div>
    @endif

    <div class="col-md-12">


        <div class="col-md-12">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Term Ar')}}</label>
                <div class="input-group">
                <textarea name="term_ar" class="form-control" rows="4" cols="150"
                >{{old('term_ar', isset($supplyTerm)? $supplyTerm->term_ar :'')}}</textarea>
                </div>
                {{input_error($errors,'term_ar')}}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Term En')}}</label>
                <div class="input-group">
                <textarea name="term_en" class="form-control" rows="4" cols="150"
                >{{old('term_en', isset($supplyTerm)? $supplyTerm->term_en :'')}}</textarea>
                </div>
                {{input_error($errors,'term_en')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Type')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-file-text-o"></span>

                    <select class="form-control js-example-basic-single" name="type">
                        <option value="">{{__('Select')}}</option>
                        <option value="supply" {{isset($supplyTerm) && $supplyTerm->type == 'supply'? 'selected':''}}>
                            {{__('Supply')}}
                        </option>

                        <option value="payment" {{isset($supplyTerm) && $supplyTerm->type == 'payment'? 'selected':''}}>
                            {{__('Payment')}}
                        </option>

                    </select>
                </div>

                {{input_error($errors,'type')}}

            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Status')}}</label>

                <div class="switch primary" style="margin-top: 15px">
                    <input type="checkbox" id="switch-2" name="status" {{!isset($supplyTerm) ? 'checked':'' }}
                        {{isset($supplyTerm) && $supplyTerm->status ? 'checked':''}}>
                    <label for="switch-2">{{__('Active')}}</label>
                </div>

            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Purchase Quotation')}}</label>
                <div class="switch primary" style="margin-top: 15px">
                    <input type="checkbox" id="switch-3" name="for_purchase_quotation"
                        {{isset($supplyTerm) && $supplyTerm->for_purchase_quotation ? 'checked':''}}>
                    <label for="switch-3">{{__('Active')}}</label>
                </div>
            </div>
        </div>

    </div>
</div>
