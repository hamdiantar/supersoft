@extends('web.layout.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Services Reservations') }} </title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('web:dashboard')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{__('Services Reservations')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title with-control">
                    <span> <i class="fa fa-calendar"></i>  {{__('Services Reservations')}}</span>
                </h4>
                <div class="card-content js__card_content" style="">
                    <form action="{{ route('web:reservations.store') }}" method="POST">
                        @csrf
                        <div class="col-md-4">
                            <label> {{ __('Date') }} </label>
                            <input name="event_date" class="form-control" type="date" value="{{ old('event_date') }}"/>
                        </div>
                        <div class="col-md-4">
                            <label> {{ __('Time') }} </label>
                            <input name="event_time" class="form-control" type="time" value="{{ old('event_time') }}"/>
                        </div>
                        <div class="col-md-4">
                            <label> {{ __('reservations.event-title') }} </label>
                            <input name="event_title" class="form-control" type="text" value="{{ old('event_title') }}"/>
                        </div>
                        <div class="col-md-12" style="margin-bottom: 5px; margin-top: 5px;">
                            <label> {{ __('reservations.event-comment') }} </label>
                            <textarea name="customer_comment" rows="4" class="form-control" placeholder="{{ __('reservations.event-comment') }}">{{ old('customer_comment') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary"> {{ __('reservations.event-save') }} </button>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div>
                <div class="card-content js__card_content calender-reservation" style="">
                    @if ($calendar)
                        {!! $calendar->calendar() !!}
                    @else
                        <h3 class="text-center"> {{ __('reservations.no-reservations') }} </h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
<div class="modal fade" id="reservation-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="form-content" style="min-height: 100px"></div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script src="{{ asset('fullcalendar' . ($lang == 'ar' ? '/ar.js' : '/en-ca.js')) }}"></script>
    @if ($calendar)
        {!! $calendar->script() !!}
    @endif
    {!! JsValidator::formRequest('App\Http\Requests\Web\ReservationReq') !!}
    <script type="application/javascript">
        $(document).on('click' ,'td.fc-event-container > a' ,function(e) {
            e.preventDefault()
            var url = $(this).prop('href')
            $("#form-content").html('{{ __('reservations.loading') }}')
            $("#reservation-modal").modal('toggle')
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: url,
                success: function (response) {
                    $("#form-content").html(response.code)
                },
                error: function (err) {
                    swal('{{ __('reservations.error') }}' ,err.responseJSON.message ,'error')
                }
            })
        })

        $(document).ready(function () {
            @if($lang == 'ar')
                $('[aria-label="prev"]').addClass('pull-right')
            @endif
        })

        function editable_form_submit(event) {
            event.preventDefault()
            var form = $(event.target) ,data = form.serialize() ,method = 'POST' ,url = form.attr('action')
            $.ajax({
                dataType: 'json',
                type: method,
                data: data,
                url: url,
                success: function (response) {
                    window.location = "{{ route('web:reservations.index') }}?refresh_message="+response.message
                },
                error: function (error) {
                    if (error.responseJSON.status == 400) {
                        swal('{{ __('reservations.error') }}' ,error.responseJSON.message ,'error')
                    } else {
                        $.each(error.responseJSON.errors, function (key, value) {
                            console.log(key ,value[0])
                            $('input[name="' + key + '"]')
                                .closest('.form-group')
                                .addClass('has-error')
                                .append('<span class="help-block">' + value[0] + '</span>');
                        })
                    }
                }
            })
            return false;
        }
    </script>
@endsection
