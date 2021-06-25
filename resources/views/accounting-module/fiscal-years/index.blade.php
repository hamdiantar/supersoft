@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.fiscal-years-index') }} </title>
@endsection

@section('content')
<nav>
    <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
    <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
        <li class="breadcrumb-item active"> {{ __('accounting-module.fiscal-years-index') }} </li>
    </ol>
</nav>

<div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.fiscal-years-index') }} 
                    </h4>


					<div class="card-content">
            </div>

    <div class="col-md-12">
        <div class="clearfix"></div>
        <!-- <a class="btn btn-primary style-btn-wg-new"
            @if(user_can_access_accounting_module(NULL ,'fiscal-years' ,'add'))
                href="{{ route('fiscal-years.create') }}"
            @else
                disabled
            @endif
                >
            <i class="fa fa-plus"></i> {{ __('accounting-module.fiscal-years-create') }}
        </a> -->
<div class=" style-btn-wg-new">
        <a style="
    margin-bottom: 12px; border-radius: 5px" type="button" class=" btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left"
    @if(user_can_access_accounting_module(NULL ,'fiscal-years' ,'add'))
                href="{{ route('fiscal-years.create') }}"
            @else
                disabled
            @endif>
    {{ __('accounting-module.fiscal-years-create') }}
    <i class="ico fa fa-plus"></i>

</a></div>
        <div class="clearfix"></div>
        <table class="table table-responsive table-striped table-bordered" style="margin-top: 15px">
            <thead>
                <tr>
                    <th class="text-center"> {{ __('accounting-module.name-en') }} </th>
                    <th class="text-center"> {{ __('accounting-module.name-ar') }} </th>
                    <th class="text-center"> {{ __('accounting-module.change-status') }} </th>
                    <th class="text-center"> {{ __('accounting-module.start-date') }} </th>
                    <th class="text-center"> {{ __('accounting-module.end-date') }} </th>
                    <th class="text-center"> {{ __('accounting-module.actions') }} </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $collection
                    ->chunk(100 ,function ($__collection) use ($view_path) {
                        foreach($__collection as $row) {
                            $editable = $row->daily_restrictions->first();
                            $code = view($view_path .'.table-row' ,['row' => $row ,'editable' => !$editable])->render();
                            echo $code;
                        }
                    });
                @endphp
            </tbody>
        </table>
    </div>
    </div>
    </div>
@endsection

@section('accounting-scripts')
    <script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

    <script type="application/javascript">
        function alert(message) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:message,
                icon:"warning",
                reverseButtons: false,
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
        function confirmDelete(url) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:'{{ __('accounting-module.are-u-sure-to-delete-fiscal-years') }}',
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
                    alert('{{ __('accounting-module.fiscal-years-not-deleted') }}')
                }
            })
        }

        function change_fiscal_year_status(event ,current_status ,url) {
            if (current_status == 0) {
                swal({
                    title:"{{ __('accounting-module.warning') }}",
                    text:'{{ __('accounting-module.are-u-sure-to-change-fiscal-years-status') }}',
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
                        $.ajax({
                            dataType: 'json',
                            type: 'GET',
                            url: url,
                            success: function (response) {
                                if (response.status == 200) location.reload()
                            },
                            error: function (err) {
                                alert("server error")
                            }
                        })
                    } else {
                        $('input[name="fiscal_year_status"]').prop('checked' ,false)
                        $('input.selected-year').prop('checked' ,true)
                    }
                })
            } else {
                $.ajax({
                    dataType: 'json',
                    type: 'GET',
                    url: url,
                    success: function (response) {
                        if (response.status == 200) location.reload()
                    },
                    error: function (err) {
                        alert("server error")
                    }
                })
            }
        }
    </script>
@endsection