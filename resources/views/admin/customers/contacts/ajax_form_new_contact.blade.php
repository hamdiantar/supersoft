<div class="row contact-{{$index}}">

    <div class="col-md-2">
        <div class="form-group">

            <label for="exampleInputEmail1">{{__('Name')}}</label>

            <span class="asterisk" style="color: #ff1d47"> * </span>

            <input type="text" name="contacts[{{$index}}][name]" required class="form-control">

        </div>
        {{input_error($errors,'contacts.'.$index.'.name')}}
    </div>

    <div class="col-md-2">
        <div class="form-group">

            <label for="exampleInputEmail1">{{__('phone 1')}}</label>

            <span class="asterisk" style="color: #ff1d47"> * </span>

            <input type="text" name="contacts[{{$index}}][phone_1]" required class="form-control">

        </div>
        {{input_error($errors,'contacts.'.$index.'.phone_1')}}
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="exampleInputEmail1">{{__('phone 2')}} :</label>
            <input type="text" min="0" name="contacts[{{$index}}][phone_2]" class="form-control">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">

            <label for="exampleInputPassword1"> {{__('Address')}} : </label>

            <textarea name="contacts[{{$index}}][address]" class="form-control" style=" height: 45px;"
            ></textarea>

        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <button class="btn btn-sm btn-danger" type="button"
                    onclick="deleteContact('{{$index}}')"
                    id="delete-div-" style="margin-top: 31px;">
                <li class="fa fa-trash"></li>
            </button>
        </div>
    </div>
</div>
