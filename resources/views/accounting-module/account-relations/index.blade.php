@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.account-relations-index') }} </title>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('accounting-module.account-relations-index') }} </li>
        </ol>
    </nav>

    <div class="col-md-12">

    <div class="col-md-12" style="margin-bottom: 20px">
    <br>
        <a style="
    margin-bottom: 12px; border-radius: 5px" class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left" style="padding:5px 20px"
            @if (user_can_access_accounting_module(NULL ,'account-relations' ,'add'))
                href="{{ route('account-relations.create') }}"
            @else
                disabled
            @endif
            >
            <i class="ico fa fa-plus"></i> {{ __('accounting-module.account-relation-create') }}
        </a>
</div>

<div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.account-relations-index') }}
                    </h4>


					<div class="card-content">
    

        <table class="table table-responsive table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center"> {{ __('accounting-module.account-type') }} </th>
                    <th class="text-center"> {{ __('accounting-module.account-item') }} </th>
                    <th class="text-center"> {{ __('accounting-module.account-name') }} </th>
                    <th class="text-center"> {{ __('accounting-module.account-nature') }} </th>
                    <th class="text-center"> {{ __('accounting-module.actions') }} </th>
                </tr>
            </thead>
            <tbody>
                @foreach($collection as $c)
                    <tr>
                        <td> {{ $c->custom_account_type }} </td>
                        <td> {{ $c->custom_account_item }} </td>
                        <td> {{ $lang == 'ar' ? optional($c->account_tree_parent)->name_ar : optional($c->account_tree_parent)->name_en }} </td>
                        <td> {{ $c->custom_nature }} </td>
                        <td>
                            <a class="btn btn-wg-edit hvr-radial-out" style="padding:5px 12px"
                                @if (user_can_access_accounting_module(NULL ,'account-relations' ,'edit'))
                                    href="{{ route('account-relations.edit' ,['account_relation' => $c->id ]) }}"
                                @else
                                    disabled
                                @endif
                                >
                                <i class="fa fa-edit"></i>
                            </a>
                            <button class="btn btn-wg-delete hvr-radial-out" style="padding:5px 12px"
                                @if (user_can_access_accounting_module(NULL ,'account-relations' ,'delete'))
                                    onclick="delete_confirm('{{ route('account-relations.delete' ,['id' => $c->id ]) }}')"
                                @else
                                    disabled
                                @endif
                                >
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $collection->links() }}
    </div>
    </div>
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
        function delete_confirm(url) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:"{{ __('accounting-module.are-u-sure-to-delete-account-relation') }}",
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
                    alert("{{ __('accounting-module.account-relation-not-deleted') }}")
                }
            })
        }
    </script>
@endsection