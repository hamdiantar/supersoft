@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.locker-receives-create') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">
                    <a href="{{route('admin:locker-receives.index')}}"> {{__('words.locker-receives')}}</a>
                </li>
                <li class="breadcrumb-item active"> {{__('words.locker-receives-create')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial">
                    <i class="fa fa-money"></i> {{ __('words.locker-receives-create') }}
                    <span class="controls hidden-sm hidden-xs pull-left">
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{ __('Save') }}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                src="{{ asset('assets/images/f1.png') }}">
                        </button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{ __('Reset') }}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                src="{{asset('assets/images/f2.png')}}">
                        </button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{ __('Back') }}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                src="{{asset('assets/images/f3.png')}}">
                        </button>
                    </span>
                </h4>
                <div class="box-content">
                    <form method="post" action="{{route('admin:locker-receives.store')}}" class="form">
                        @csrf
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label class="control-label">
                                            {{ __('words.select-exchange-permission') }}
                                        </label>
                                        <select name="locker_exchange_permission_id" id="exchange-permissions"
                                            class="form-control js-example-basic-single">
                                            <option data-json-object="{}" value=''> {{ __('Select One') }} </option>
                                            @foreach($exchange_permissions as $exchange)
                                                <option value="{{ $exchange->id }}"
                                                    data-type="{{ $exchange instanceof \App\ModelsMoneyPermissions\BankExchangePermission ? __('words.bank') : __('words.locker') }}"
                                                    data-json-object="{{ $exchange->toJson() }}">
                                                    {{ ($exchange instanceof \App\ModelsMoneyPermissions\BankExchangePermission ? __('words.bank') : __('words.locker')) . ' | '.$exchange->permission_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.from') }}</label>
                                        <input type="text" id="locker-from" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.to') }}</label>
                                        <input type="text" id="locker-to" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.source-type') }}</label>
                                        <input type="text" id="type" readonly class="form-control">
                                        <input type="hidden" name="source_type"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.permission-number') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                            <input type="text" name="permission_number" value="{{ $permission_number }}"
                                                readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.operation-date') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                            <input name="operation_date" class="form-control"
                                                   type="date" placeholder="{{ __('words.operation-date') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('the Amount') }}</label>
                                        <input type="text" readonly name="amount" class="form-control"
                                            placeholder="{{ __('the Amount') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label class="control-label">{{ __('words.money-receiver') }}</label>
                                        <select name="employee_id" class="form-control">
                                            <option value=""> {{ __('Select Employee') }} </option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}"> {{ $employee->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @php
                                    $branch_id = authIsSuperAdmin() ? NULL : auth()->user()->branch_id;
                                    $selected_id = NULL;
                                @endphp
                                @include('admin.money-permissions.cost-centers' ,['branch_id' => $branch_id ,'selected_id' => $selected_id])
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <div class="form-group has-feedback">
                                        <label class="control-label">{{__('words.permission-note')}}</label>
                                        <textarea rows="4" name="note" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group col-sm-12">
                            <button type="submit"class="btn hvr-rectangle-in saveAdd-wg-btn">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>

                            <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn">
                                <i class="ico ico-left fa fa-trash"></i>
                                {{__('Reset')}}
                            </button>

                            <button id="back" type="button" class="btn hvr-rectangle-in closeAdd-wg-btn">
                                <i class="ico ico-left fa fa-close"></i>
                                {{__('Back')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\MoneyPermissions\LockerReceiveRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $(document).on('change' ,'#exchange-permissions' ,function () {
            const exchange = $(this).find('option:selected').data('json-object'),
                type = $(this).find('option:selected').data('type')
            if (!exchange.amount) {
                $('[name="amount"]').val('')
                $('#locker-from').val('')
                $('#locker-to').val('')
                $('#type').val('')
                $('input[name="source_type"]').val('')
            } else {
                $('[name="amount"]').val(exchange.amount)
                $('[name="operation_date"]').val(exchange.operation_date)
                $('#locker-from').val(exchange.from_locker ? exchange.from_locker.trans_name : exchange.from_bank.trans_name)
                $('#locker-to').val(exchange.to_locker.trans_name)
                $('#type').val(type)
                $('input[name="source_type"]').val(type == '{{ __('words.bank') }}' ? 'bank' : 'locker')
                $.ajax({
                    url: "{{ route('branch-cost-center') }}?branch_id=" + exchange.branch_id,
                    method: 'GET',
                    success: function (data) {
                        $('select[name="cost_center_id"]').html(data.options);
                        $('select[name="cost_center_id"]').select2()
                    }
                })
                $.ajax({
                    url: "{{ route('admin:get-employees-select') }}?branch_id=" + exchange.branch_id,
                    method: 'GET',
                    success: function (data) {
                        $('select[name="employee_id"]').html(data.options);
                        $('select[name="employee_id"]').select2()
                    }
                })
            }
        })
    </script>
@endsection
