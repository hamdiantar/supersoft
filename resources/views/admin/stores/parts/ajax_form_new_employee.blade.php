<div class="row contact-{{$index}}">

    <div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputSymbolAR" class="control-label">{{__('Select Store Creator')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select name="employees[{{$index}}][employee_id]" class="employees_ids form-control js-example-basic-single"
                            onchange="getemployeeById('{{$index}}')" id="employees_ids{{$index}}">
                        <option>{{__('Select Store Creator')}}</option>
                        @foreach($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors, 'employees['.$index.'][employee_id]')}}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">

                <label for="exampleInputEmail1">{{__('phone 1')}}</label>
                <input type="text" readonly id="phone1{{$index}}" class="form-control employeeData">

            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">{{__('phone 2')}}</label>
                <input type="text" readonly min="0" id="phone2{{$index}}" class="form-control employeeData">
            </div>
        </div>
    </div>
    </div>

    <div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label for="exampleInputEmail1">{{__('Start Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                <input type="date" name="employees[{{$index}}][startDate]" class="form-control employeeData" value="{{now()}}">
            </div>
        </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">{{__('End Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                <input type="date" name="employees[{{$index}}][endDate]" class="form-control employeeData">
            </div>
        </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <button class="btn btn-sm btn-danger" type="button"
                        onclick="deleteContact('{{$index}}')"
                        id="delete-div-" style="margin-top: 31px;">
                    <li class="fa fa-trash"></li>
                </button>
            </div>
            </div>
        </div>
    </div>
    <hr>
</div>
