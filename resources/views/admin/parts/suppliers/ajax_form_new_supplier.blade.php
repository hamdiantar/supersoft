<div class="row supplier-{{$index}}">

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputSymbolAR" class="control-label">{{__('Select Supplier')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-file"></span>
                <select name="suppliers_ids[]" class="form-control js-example-basic-single"
                        onchange="getSupplierById('{{$index}}')" id="suppliers_ids{{$index}}">
                    <option>{{__('Select Supplier')}}</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                    @endforeach
                </select>
            </div>
            {{input_error($errors,'suppliers_ids')}}
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">

            <label for="exampleInputEmail1">{{__('phone')}}</label>
            <input type="text" readonly name="contacts[{{$index}}][phone_1]" id="phone{{$index}}" class="form-control">

        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="exampleInputEmail1">{{__('Address')}}</label>
            <input type="text" readonly min="0" name="contacts[{{$index}}][phone_2]" id="address{{$index}}" class="form-control">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <button class="btn btn-sm btn-danger" type="button"
                    onclick="deleteSupplier('{{$index}}')"
                    id="delete-div-" style="margin-top: 31px;">
                <li class="fa fa-trash"></li>
            </button>
        </div>
    </div>
</div>
