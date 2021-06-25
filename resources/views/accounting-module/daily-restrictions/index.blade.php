@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.daily-restrictions-index') }} </title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
<nav>
    <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
    <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
        <li class="breadcrumb-item active"> {{ __('accounting-module.daily-restrictions-index') }} </li>
    </ol>
</nav>
    <div class="col-md-12">

        @include($view_path . '.index-form')
      
        <div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.daily-restrictions-index') }}
                    </h4>


					<div class="card-content">
            </div>
        <div class="col-md-12">
            <div class="style-btn-wg-new">
            <a class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left "
                @if (user_can_access_accounting_module(NULL ,'daily-restrictions' ,'add'))
                    href="{{ route('daily-restrictions.create') }}"
                @else
                    disabled
                @endif
                >
                <i class="ico fa fa-plus"></i> {{ __('accounting-module.daily-restrictions-create') }}
            </a>
            </div>
        </div>

        <div class="col-md-12">
            @include($view_path . '.options-datatable.option-row')
            <div class="clearfix"></div>
            <table class="table table-responsive table-striped table-bordered" style="margin-top: 10px">
                @include($view_path . '.options-datatable.table-thead')
                <tbody>
                    @foreach($collection as $c)
                        <tr>
                            <td class="text-center column-restriction-number">
                                {{ $c->restriction_number }}
                            </td>
                            <!-- <td class="text-center column-operation-number">
                                {{ $c->operation_number }}
                            </td> -->
                            <td class="text-center column-operation-date">
                                {{ $c->operation_date }}
                            </td>
                            <td class="text-center column-debit-amount">
                                {{ $c->debit_amount }}
                            </td>
                            <td class="text-center column-credit-amount">
                                {{ $c->credit_amount }}
                            </td>
                            <td class="text-center column-records-number">
                                {{ $c->records_number }}
                            </td>
                            <td class="text-center column-actions">
                                @if($c->am_editable())
                                    <a  class="btn btn-wg-edit hvr-radial-out" style="padding:5px 12px"
                                        href="{{ route('daily-restrictions.edit' ,['daily_restriction' => $c->id ]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                                @if($c->am_printable())
                                    <button type="button" class="btn btn-wg-show hvr-radial-out" style="padding:5px 12px"
                                        onclick="printMe('{{ route('daily-restrictions.print' ,['id' => $c->id ]) }}')">
                                        <i class="fa fa-print"></i>
                                    </button>
                                @endif
                                @if($c->am_deleteable())
                                    <button  class="btn btn-wg-delete hvr-radial-out" style="padding:5px 12px"
                                        onclick="delete_confirm('{{ route('daily-restrictions.delete' ,['id' => $c->id ]) }}')">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $collection->links() }}
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 col-sm-6 col-xs-6">

            <div class="bordered-div">
                <h3>
                {{ __('accounting-module.debit-amount') }}
                </h3>
                <input disabled="" value="{{ $total->total_debit }}" class="form-control">
            </div>               
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">

            <div class="bordered-div">
                <h3>
                {{ __('accounting-module.credit-amount') }}
                </h3>
                <input disabled="" value="{{ $total->total_credit }}" class="form-control">
            </div>               
        </div>
        <div class="clearfix"></div>
    </div>
    </div>
    </div>
@endsection

@section('accounting-module-modal-area')
    @include($view_path . '.options-datatable.column-visible')
    <div id="printer-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:70%">
            <div class="modal-content" id="printer-modal-content" style="padding:20px">
            </div>
        </div>
    </div>
@endsection

@section('accounting-scripts')
    <script type="application/javascript" src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

    <script type="application/javascript">
        function alert(message) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:message,
                icon:"warning",
                buttons:{
                    cancel: {
                        text: "{{ __('words.ok') }}",
                        className: "btn btn-primary",
                        value: null,
                        visible: true
                    }
                }
            })
        }
    </script>
    <script type="application/javascript">

        function delete_confirm(url) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:"{{ __('accounting-module.are-u-sure-to-delete-daily-restriction') }}",
                icon:"warning",
                reverseButtons: false,
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
            }).then(function(confirm_delete){
                if (confirm_delete) {
                    window.location = url
                } else {
                    alert("{{ __('accounting-module.daily-restriction-not-deleted') }}")
                }
            })
        }

        function printMe(url) {
            $.ajax({
                dataType:'json',
                type:'GET',
                url:url,
                success: function (response) {
                    $("#printer-modal-content").html(response.code)
                    $("#printer-modal").modal('toggle')
                },
                error: function (err) {
                    alert("server error")
                }
            })
        }

        function modal_printer(container_id ,header) {
            var options = {
                printable: container_id,
                type: 'html',
                css: []
            }
            if (header) options.header = header
            options.css.push("{{ asset('assets-ar/Design/css/bootstrap.min.css') }}")
            options.css.push("https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css")

            //Added by Ahmed Hesham to solve font family in css
            options.css.push("https://fonts.googleapis.com/css2?family=Droid Arabic Kufi&display=swap")
            
            //here we can push more than 1 file to handle css files
            @php
                $lang = 'ar';
            @endphp
            options.style = `
                html{direction:{{ $lang == 'ar' ? 'rtl' : 'ltr' }}}
                html ,body{ height: 99%; width: 99%; margin: 0 auto; border: none }
                table{ direction:{{ $lang == 'ar' ? 'rtl' : 'ltr' }};float:{{ $lang == 'ar' ? 'right' : 'left' }} }
                .col-xs-12, .col-xs-4, .col-xs-6, .col-md-12, .col-md-4 ,.col-md-6, .col-sm-12, .col-sm-4, .col-sm-6 {
                    float:{{ $lang == 'ar' ? 'right' : 'left' }}
                }
            `
            printJS(options)
            return;
        }

    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection