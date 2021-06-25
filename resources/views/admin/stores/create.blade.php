@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Create store') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:stores.index')}}"> {{__('Stores')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create store')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-home"></i>
                    {{__('Create store')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                  <button class="control text-white"
                          style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid"
                                                                                                style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                                                                                src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>


                <div class="box-content">
                    <form method="post" action="{{route('admin:stores.store')}}" class="form">
                        @csrf
                        @method('post')

                        <div class="row">
<div class="col-xs-12">

<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                                @if(authIsSuperAdmin())
                                    <div class="col-xs-12">
                                        <div class="form-group has-feedback">
                                            <label for="inputSymbolAR"
                                                   class="control-label">{{__('Select Branch')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon fa fa-file"></span>
                                                <select name="branch_id" class="form-control js-example-basic-single" id="branchId">
                                                    <option value="">{{__('Select')}}</option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'branch_id')}}
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            <div class="col-md-12">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                                                   placeholder="{{__('Name in Arabic')}}">
                                        </div>
                                        {{input_error($errors,'name_ar')}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN" class="control-label">{{__('Name in English')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                                   placeholder="{{__('Name in English')}}">
                                        </div>
                                        {{input_error($errors,'name_en')}}
                                    </div>
                                </div>

                                </div>

                            <div class="col-md-12">

                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="creator_phone" class="control-label">{{__('Store Phone')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="store_phone" class="form-control" id="store_phone"
                                                   placeholder="{{__('Store Phone')}}">
                                        </div>
                                        {{input_error($errors,'store_phone')}}
                                    </div>
                                </div>


                                <div class="col-md-6">


                                    <div class="form-group has-feedback">
                                        <label for="store_address" class="control-label">{{__('Store Address')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="store_address" class="form-control"
                                                   id="store_address"
                                                   placeholder="{{__('Store Address')}}">
                                        </div>
                                        {{input_error($errors,'Store')}}
                                    </div>
                                </div>

                                </div>

                                <div class="col-md-12">


                                    <div class="form-group has-feedback">
                                        <label for="note" class="control-label">{{__('Notes')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <textarea name="note" class="form-control" id="store_address" rows="3">
</textarea>
                                        </div>
                                        {{input_error($errors,'note')}}
                                    </div>

                                </div>

                                </div>
                                </div>
                                </div>


                                <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                            <div class="col-xs-12">
                                <div class="col-md-12">
                                    <hr>
                                    <span
                                        style="color: white;font-size: 14px;background:#2980B9;padding:5px 10px;border-radius:3px"> {{__('Stores officials')}} </span>
                                    <hr>
                                </div>

                                <div class="container">
                                    <div class="form_new_contact">
                                        <input type="hidden" value="0" id="contacts_count">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" title="new price" onclick="newEmployee()"
                                            class="btn btn-sm btn-info">
                                        <li class="fa fa-plus"></li> {{__('New employee')}}
                                    </button>
                                    <hr>
                                </div>
                            </div>

                            </div>

                            <div class="form-group col-sm-12">
                                @include('admin.buttons._save_buttons')
                            </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\store\StoreRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')

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
