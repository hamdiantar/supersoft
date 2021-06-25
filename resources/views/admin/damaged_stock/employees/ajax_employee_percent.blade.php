
<div class="col-md-12" id="employee_{{$employees_items_count}}">
    <div class="col-md-2">
        <div class="form-group">
            <label for="inputDescription" class="control-label">{{__('Employees')}}</label>
            <div class="input-group">
                <select class="form-control js-example-basic-single"
                        name="employees[{{$employees_items_count}}][employee_id]">
                    <option value="">{{__('Select Employee')}}</option>
                    @foreach($employees as $employee)
                        <option
                            value="{{$employee->id}}" {{isset($damaged_employee) && $damaged_employee->id == $employee->id ? 'selected':''}}>
                            {{$employee->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="inputDescription" class="control-label">{{__('Percent')}}</label>
            <div class="input-group">
                <input type="text" id="employee_percent_{{$employees_items_count}}"
                       name="employees[{{$employees_items_count}}][percent]"
                       onkeyup="calculateEmployeePercent('{{$employees_items_count}}')"
                       value="{{isset($damaged_employee) ? $damaged_employee->pivot->percent : 0}}"
                       class="form-control">
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="inputDescription" class="control-label">{{__('Total Amount')}}</label>
            <div class="input-group">
                <input type="text" readonly id="employee_amount_{{$employees_items_count}}"
                       name="employees[{{$employees_items_count}}][amount]"
                       value="{{isset($damaged_employee) ? $damaged_employee->pivot->amount : 0}}" class="form-control">
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">

            @if(isset($damaged_employee))

                <button type="button" class="btn btn-danger fa fa-trash" style="margin-top: 30px;"
                        onclick="deleteEmployee('{{$damaged_employee->pivot->id}}' , '{{$employees_items_count}}')">
                </button>
            @else
                <button type="button" class="btn btn-danger fa fa-trash" style="margin-top: 30px;"
                        onclick="removeEmployeesPercent('{{$employees_items_count}}')">
                </button>
            @endif
        </div>
    </div>

</div>
