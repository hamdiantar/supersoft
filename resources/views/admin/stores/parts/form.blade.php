<div class="col-md-12">
    <hr>
    <span style="color: white;font-size: 14px;background:#2980B9;padding:5px 10px;border-radius:3px"> {{__('Stores officials')}} </span>
    <hr>
</div>

<div class="container">
    <div class="form_new_contact">
        @if(isset($store) && $store->storeEmployeeHistories )
            @foreach($store->storeEmployeeHistories as $index=>$employeeHistory)
                <div class="row contact-{{$index + 1}}" id="employeeHistory_{{$employeeHistory->id}}">

                   <div class="row">
                   <div class="col-md-12">
                       <div class="col-md-4">
                           <div class="form-group has-feedback">
                               <label for="inputSymbolAR" class="control-label">{{__('Select Store Creator')}}</label>
                               <div class="input-group">
                                   <input type="hidden" value="employeesUpdate[{{$index}}][id]">
                                   <span class="input-group-addon fa fa-file"></span>
                                   <select name="employeesUpdate[{{$index}}][employee_id]" class=" form-control js-example-basic-single"
                                           onchange="getemployeeById('{{$employeeHistory->employee_id}}')" id="employees_ids{{$employeeHistory->employee_id}}">
                                       <option>{{__('Select Store Creator')}}</option>
                                       @foreach($employees as $employeeData)
                                           <option value="{{$employeeData->id}}"
                                               {{ $employeeHistory->employee_id === $employeeData->id ? 'selected':''}}>{{optional($employeeHistory->employee)->name}}</option>
                                       @endforeach
                                   </select>
                               </div>
                               {{input_error($errors,'employees_ids')}}
                           </div>
                       </div>

                       <div class="col-md-3">
                           <div class="form-group">

                               <label for="exampleInputEmail1">{{__('phone 1')}}</label>
                               <input type="text" readonly value="{{optional($employeeHistory->employee)->phone1}}" id="phone1{{optional($employeeHistory->employee)->id}}" class="form-control">

                           </div>
                       </div>

                       <div class="col-md-3">
                           <div class="form-group">
                               <label for="exampleInputEmail1">{{__('phone 2')}}</label>
                               <input type="text" readonly
                                      value="{{optional($employeeHistory->employee)->phone2}}"  id="phone2{{optional($employeeHistory->employee)->id}}" class="form-control">
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
                                <input type="date" name="employeesUpdate[{{$index}}][startDate]" class="form-control" value="{{$employeeHistory->start}}">
                            </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">

                                <label for="exampleInputEmail1">{{__('End Date')}}</label>
                                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>

                                <input type="date" name="employeesUpdate[{{$index}}][endDate]" class="form-control" value="{{$employeeHistory->end}}">
                            </div>
                        </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <button class="btn btn-sm btn-danger" type="button"
                                        onclick="destroyEmployeeHistory('{{$employeeHistory->id}}')"
                                        id="delete-div-" style="margin-top: 31px;">
                                    <li class="fa fa-trash"></li>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            @endforeach
        @endif
        <input type="hidden" value="{{isset($index) ? $index + 1 : 1}}" id="contacts_count">
    </div>
</div>


<div class="col-md-12">
    <button type="button" title="new price" onclick="newEmployee()"
            class="btn btn-sm btn-info">
        <li class="fa fa-plus"></li>  {{__('New employee')}}
    </button>
    <hr>
</div>


@section('accounting-scripts')
    <script type="text/javascript">
        function newEmployee() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let contacts_count = $("#contacts_count").val();
            let branchId = $("#branchId").val();
            @if(authIsSuperAdmin())
            if (!is_numeric(branchId)) {
                swal({text: '{{__('please select the branch first')}}', icon: "warning"})
                return false;
            }
            @endif
            $.ajax({
                type: 'post',
                url: '{{route('admin:stores.new.employee')}}',
                data: {
                    _token: CSRF_TOKEN,
                    contacts_count: contacts_count,
                    branchId: branchId,
                },
                success: function (data) {
                    $("#contacts_count").val(data.index);
                    $("#employees_ids" + data.index).select2()
                    $(".form_new_contact").append(data.view);
                    $("#employees_ids" + data.index).select2()
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteContact(index) {
            swal({
                title: "{{__('Delete')}}",
                text: "{{__('Are you sure want to Delete?')}}",
                type: "success",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $(".contact-" + index).remove();
                }
            });
        }

        function getemployeeById(index) {
            let id = $('#employees_ids' + index).val();
            let ids = [];
            $('.employees_ids').each(function () {
                ids.push( $(this).val());
            });
            const values = $.map(ids, function (value, key) {
                return value;
            });
            const hasDups = !values.every(function (v, i) {
                return values.indexOf(v) === i;
            });
            if (hasDups) {
                swal({text: '{{__('you have already added this employee before')}}', icon: "warning"})
                $('.contact-'+index).remove();
                return false;
            }
            $.ajax({
                type: 'get',
                url: "{{ route('admin:stores.getBYId.employee') }}?emp_id=" + id,
                success: function (data) {
                    $("#phone1" + index).val(data.phone1)
                    $("#phone2" + index).val(data.phone2)
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function destroyEmployeeHistory(storeEmployeeHistoryId)
        {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: "{{__('Delete Employee History')}}",
                text: "{{__('Are you sure want to delete this Employee History?')}}",
                icon: "warning",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                },
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'delete',
                        url: '{{route('admin:store_employee_history.destroyEmployeeHistory')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            storeEmployeeHistoryId: storeEmployeeHistoryId
                        },
                        success: function (data) {
                            $("#employeeHistory_" + data.id).fadeOut('slow');
                            $("#employeeHistory_" + data.id).remove();
                            swal({text: '{{__('Employee History Deleted Successfully')}}', icon: "success"})
                        },
                        error: function (jqXhr, json, errorThrown) {
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
                }
            });
        }

        $("#startDate").flatpickr();

        $('#branchId').change(function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branchId = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:stores.getEmployeeByBranchId')}}",
                data : {
                    branchId : branchId,
                    _token : CSRF_TOKEN,
                },
                success: function (data) {
                    $('.employees_ids').each(function () {
                        $(this).html(data.data)
                    });
                    $('.employeeData').each(function () {
                        $(this).val('')
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        })
    </script>
@endsection
