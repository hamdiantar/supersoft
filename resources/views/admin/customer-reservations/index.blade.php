@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Services Reservations') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Services Reservations')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card top-search">
                <h4 class="box-title with-control">
                    <i class="fa fa-search"></i>{{__('Search filters')}}
                    <span class="controls">
                        <button type="button" class="control fa fa-minus js__card_minus"></button>
                        <button type="button" class="control fa fa-times js__card_remove"></button>
                    </span>
                </h4>
                <div class="card-content js__card_content">
                    <form>
                        <div class="list-inline margin-bottom-0 row">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> {{ __('Date From') }} </label>
                                    <input type="date" class="form-control" placeholder="YYYY-MM-DD" name="date_from"
                                        value="{{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }}"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> {{ __('Date To') }} </label>
                                    <input type="date" class="form-control" placeholder="YYYY-MM-DD" name="date_to"
                                        value="{{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }}"/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out">
                                    <i class=" fa fa-search "></i> {{__('Search')}}
                                </button>
                                <a href="{{route('admin:reservations.index')}}"
                                class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-car"></i>  {{__('Services Reservations')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <div class="clearfix"></div>
                        <table id="customers-reservations" class="table table-bordered" style="width:100%;margin-top: 15px">
                            <thead>
                                <tr>
                                    <th scope="col">{!! __('Customer Name') !!}</th>
                                    <th scope="col">{!! __('Customer Phone') !!}</th>
                                    <th scope="col">{!! __('Date') !!}</th>
                                    <th scope="col">{!! __('Time') !!}</th>
                                    <th scope="col">{!! __('reservations.reservation-status') !!}</th>
                                    <th scope="col">{!! __('reservations.event-title') !!}</th>
                                    <th scope="col">{!! __('Options') !!}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th scope="col">{!! __('Customer Name') !!}</th>
                                    <th scope="col">{!! __('Customer Phone') !!}</th>
                                    <th scope="col">{!! __('Date') !!}</th>
                                    <th scope="col">{!! __('Time') !!}</th>
                                    <th scope="col">{!! __('reservations.reservation-status') !!}</th>
                                    <th scope="col">{!! __('reservations.event-title') !!}</th>
                                    <th scope="col">{!! __('Options') !!}</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @php
                                    $collection->chunk(100 ,function ($reservations) use ($row_view_path) {
                                        foreach($reservations as $reservation) {
                                            $code = view($row_view_path ,['reservation' => $reservation])->render();
                                            echo $code;
                                        }
                                    })
                                @endphp
                            </tbody>
                        </table>
                    </div>
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
            <div id="form-content" style="min-height: 100px">
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script type="application/javascript">
        invoke_datatable($('#customers-reservations'))

        function load_reservation(url) {
            $('#form-content').html(`<h3> {{ __('reservations.loading') }} </h3><div class="clearfix"></div>`)
            $('#reservation-modal').modal('toggle')
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: url,
                success: function (response) {
                    $('#form-content').html(response.code)
                },
                error: function (error) {
                    $('#reservation-modal').modal('toggle')
                    swal({
                        title: '{{ __('reservations.warning') }}',
                        text: error.responseJSON.message,
                        icon: 'warning'
                    })
                }
            })
        }

        function submit_update(event) {
            event.preventDefault()
            swal({
                title: '{{ __('reservations.warning') }}',
                text: '{{ __('reservations.change-status?') }}',
                icon: 'warning',
                buttons:{
                    confirm: {
                        text: "{{ __('words.yes_delete') }}",
                        className: "btn btn-default",
                        value: true,
                        visible: true
                    },
                    cancel: {
                        text: "{{ __('words.no') }}",
                        className: "btn btn-default",
                        value: null,
                        visible: true
                    }
                }
            })
            .then(accepted => {
                if (accepted) {
                    $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        data: $(event.target).serialize(),
                        url: $(event.target).attr('action'),
                        success: function (response) {
                            if (response.status == 200) {
                                swal('{{ __('reservations.success') }}' ,response.message ,'success').then(accepted => location.reload())
                            } else {
                                swal('{{ __('reservations.error') }}' ,response.message ,'error')
                            }
                        }
                    })
                } else {
                    swal('{{ __('reservations.warning') }}' ,'{{ __('reservations.status-not-updated') }}' ,'warning')
                }
            })
        }
    </script>
@endsection
