@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.locker-receives-edit') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">
                    <a href="{{route('admin:locker-receives.index')}}"> {{__('words.locker-receives')}}</a>
                </li>
                <li class="breadcrumb-item active"> {{__('words.locker-receives-edit')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial">
                    <i class="fa fa-money"></i> {{ __('words.locker-receives-edit') }}
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
                    <form method="post" action="{{route('admin:locker-receives.update' ,['id' => $model->id])}}" class="form">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label class="control-label">
                                            {{ __('words.exchange-permission') }}
                                        </label>
                                        <input type="text" readonly class="form-control"
                                            @if($model->source_type == 'locker')
                                                value="{{ optional($model->exchange_permission)->permission_number }}"
                                            @else
                                                value="{{ optional($model->bank_exchange_permission)->permission_number }}"
                                            @endif
                                            >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.from') }}</label>
                                        <input type="text" readonly class="form-control"
                                            @if($model->source_type == 'locker')
                                                value="{{ optional($model->exchange_permission->fromLocker)->name }}"
                                            @else
                                                value="{{ optional($model->bank_exchange_permission->fromBank)->name }}"
                                            @endif
                                            >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.to') }}</label>
                                        <input type="text" readonly class="form-control"
                                            @if($model->source_type == 'locker')
                                                value="{{ optional($model->exchange_permission->toLocker)->name }}"
                                            @else
                                                value="{{ optional($model->bank_exchange_permission->toLocker)->name }}"
                                            @endif
                                            >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.source-type') }}</label>
                                        <input type="text" id="type" readonly class="form-control" value="{{ __('words.'.$model->source_type) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.permission-number') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                            <input type="text" name="permission_number" value="{{ $model->permission_number }}"
                                                readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.operation-date') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                            <input name="operation_date" class="form-control" value="{{ $model->operation_date }}"
                                                   type="date" placeholder="{{ __('words.operation-date') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('the Amount') }}</label>
                                        <input type="text" readonly class="form-control"
                                            placeholder="{{ __('the Amount') }}" value="{{ $model->amount }}">
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
                                                <option {{ $model->employee_id == $employee->id ? 'selected' : '' }}
                                                    value="{{ $employee->id }}"> {{ $employee->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @php
                                    $branch_id = optional($model->exchange_permission)->branch_id;
                                    $selected_id = $model->cost_center_id;
                                @endphp
                                @include('admin.money-permissions.cost-centers' ,['branch_id' => $branch_id ,'selected_id' => $selected_id])
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <div class="form-group has-feedback">
                                        <label class="control-label">{{__('words.permission-note')}}</label>
                                        <textarea rows="4" name="note" class="form-control">{{ $model->note }}</textarea>
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
    {!! JsValidator::formRequest('App\Http\Requests\MoneyPermissions\LockerReceiveEditRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $(document).on('change' ,'#exchange-permissions' ,function () {
            const exchange = $(this).find('option:selected').data('json-object')
            if (!exchange.amount) {
                $('[name="amount"]').val('')
                $('#locker-from').val('')
                $('#locker-to').val('')
            } else {
                $('[name="amount"]').val(exchange.amount)
                $('[name="operation_date"]').val(exchange.operation_date)
                $('#locker-from').val(exchange.from_locker.trans_name)
                $('#locker-to').val(exchange.to_locker.trans_name)
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
