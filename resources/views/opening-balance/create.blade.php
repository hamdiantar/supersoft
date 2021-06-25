@extends('admin.layouts.app')

@section('title')
<title>{{__('opening-balance.create')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('opening-balance.index')}}"> {{__('opening-balance.index-title')}}</a></li>
                <li class="breadcrumb-item">  {{__('opening-balance.create')}} </li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gears"></i>  {{__('opening-balance.create')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                        <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
				    </span>
                </h4>
                <div class="box-content">
                    <form method="post" action="{{ route('opening-balance.store') }}" class="form"
                        id="opening-balance-form" onsubmit="return submit_form(event)">
                        @csrf
                        @method('post')
                        <input type="hidden" name="serial_number" value="{{ $last_serial_number }}"/>
                        @if(authIsSuperAdmin())
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Select Branch')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon fa fa-file"></span>
                                            <select name="branch_id" class="form-control js-example-basic-single">
                                                <option value=""> {{ __('opening-balance.select-one') }} </option>
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                            {{input_error($errors,'branch_id')}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
                        @endif
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label> {{ __('opening-balance.serial-number') }} </label>
                                <div class="input-group">
                               <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                                <input class="form-control" readonly name="serial_number" value="{{ $last_serial_number }}"/>
                            </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label> {{ __('opening-balance.operation-date') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input class="form-control" type="date" name="operation_date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}"/>
                            </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label> {{ __('opening-balance.operation-time') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                <input class="form-control" type="time" name="operation_time" value="{{ \Carbon\Carbon::now()->format('H:i')}}"/>
                            </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date" class="control-label">{{__('User')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-dashcube"></li></span>
                                        <input type="text" name="user" class="form-control" disabled
                                               value="{{auth()->user()->name}}">
                                    </div>
                                    {{input_error($errors,'user')}}
                                </div>
                            </div> -->
                            </div>


                        @include('opening-balance.common-selects')
                        @include('opening-balance.items-table')
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label> {{ __('opening-balance.total-money') }} </label>
                                <div class="input-group">
                                <span class="input-group-addon fa fa-money"></span>
                                <input class="form-control" readonly id="total-money" value="0"/>
                            </div>
                            </div>

                            <div class="form-group col-md-8">
                                <label> {{ __('opening-balance.notes') }} </label>
                                <textarea class="form-control" name="notes" rows="4"></textarea>
                            </div>

                        </div>



                        <div class="row">
                            <div class="form-group col-sm-12">
                                @include('admin.buttons._save_buttons')
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('accounting-module-modal-area')

    <div id="loader-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" id="form-modal" style="height: 150px">
                <h3 class="text-center" style="margin-top: 70px"> {{ __('opening-balance.data-loading') }} </h3>
            </div>
        </div>
    </div>

@endsection

@section('js-validation')
    @include('admin.partial.sweet_alert_messages')
    @include('opening-balance.common-script')

    <script>
        function submit_form(event) {
            $("#btnsave").attr('disabled' ,'disabled')
            event.preventDefault()
            $('.custom-error-html').remove()
            const form = $(event.target) ,data = form.serialize() ,url = form.attr('action') ,type = form.attr('method')
            $.ajax({
                datatype: 'json',
                type: type,
                data: data,
                url: url,
                success: function (response) {
                    window.location = '{{ route('opening-balance.created') }}'
                },
                error: function (err) {
                    $("#btnsave").removeAttr('disabled')
                    if ((err.status == 400 || err.status == 301) && err.responseJSON.message) {
                        const callback = err.responseJSON.location ? function () {
                            window.location = err.responseJSON.location
                        } : undefined
                        alert(err.responseJSON.message ,callback)
                    } else {
                        alert('{{ __('opening-balance.fill-form-first') }}')
                        Object.entries(err.responseJSON.errors).forEach(error => {
                            const input = error[0] ,errors = error[1] , name_in_keys = input.split(".")
                            let new_name = name_in_keys[0]
                            for(let i = 1; i < name_in_keys.length; i++) {
                                new_name += '[' + name_in_keys[i] + ']'
                            }
                            const error_html = `<span class="help-block custom-error-html error-help-block">${errors[0]}</span>`
                            $('[name="'+new_name+'"]').parent().append(error_html)
                        })
                    }
                }
            })
        }
    </script>
@endsection
