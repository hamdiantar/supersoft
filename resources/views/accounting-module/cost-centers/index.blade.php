@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('accounting-module.cost-centers-index') }} </title>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
        <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('accounting-module.cost-centers-index') }} </li>
        </ol>
    </nav>
    <div class="col-md-12">
        <input type="hidden" id="selected-cost-center-id"/>
<br>
        <div class="col-md-12" style="margin-bottom: 20px">
            <button style="
    margin-bottom: 12px; border-radius: 5px" class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left" onclick="open_modal('create')"
                {{ user_can_access_accounting_module(NULL ,'cost-centers' ,'add') ? '' : 'disabled' }}>
                <i class="ico fa fa-plus"></i> {{ __('accounting-module.create-cost-center') }}
            </button>

            <button style="
    margin-bottom: 12px; border-radius: 5px" class="btn btn-icon btn-icon-left  waves-effect waves-light hvr-bounce-to-left resetAdd-wg-btn" onclick="open_modal('edit')"
                {{ user_can_access_accounting_module(NULL ,'cost-centers' ,'edit') ? '' : 'disabled' }}>
                <i class="ico fa fa-edit"></i> {{ __('accounting-module.edit-cost-center') }}
            </button>

            <button style="
    margin-bottom: 12px; border-radius: 5px" class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left" onclick="open_modal('delete')"
                {{ user_can_access_accounting_module(NULL ,'cost-centers' ,'delete') ? '' : 'disabled' }}>
                <i class="ico fa fa-trash-o"></i> {{ __('accounting-module.delete-cost-center') }}
            </button>
        </div>

        <div class="col-xs-12 ui-sortable-handle">
				<div class="box-content card bordered-all primary">
					<h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{ __('accounting-module.cost-centers-index') }}
                    </h4>


					<div class="card-content">
        
        {!! $account_tree_ul !!}
    </div>
    </div>
    </div>
    </div>
@endsection

@section('accounting-module-modal-area')
    <div id="cost-center-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" id="form-modal">
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

        function select_cost_center(id) {
            $('.span-selected').removeClass('span-selected')
            $(event.target).addClass('span-selected')
            $("#selected-cost-center-id").val(id)
        }

        function open_modal(action_for) {
            if ($('.span-selected').length <= 0 && (action_for == 'edit' || action_for == 'delete')) {
                alert('{{ __('accounting-module.select-cost-center') }}')
            } else {
                switch(action_for) {
                    case "create":
                        return create_account_tree($("#selected-cost-center-id").val())
                    case "edit":
                        return edit_account_tree($("#selected-cost-center-id").val())
                    case "delete":
                        return delete_account_tree($("#selected-cost-center-id").val())
                }
            }
        }

        function create_account_tree(id) {
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: '{{ route("cost-centers.form" ,['action_for' => 'create' ]) }}?id='+(id ? id : 0),
                success: function (response) {
                    $("#form-modal").html(response.html_code)
                    $("#cost-center-modal").modal('toggle')
                },
                error: function (err) {
                    alert("server error")
                }
            })
        }

        function edit_account_tree(id) {
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: '{{ route("cost-centers.form" ,['action_for' => 'edit' ]) }}?id='+id,
                success: function (response) {
                    $("#form-modal").html(response.html_code)
                    $("#cost-center-modal").modal('toggle')
                },
                error: function (err) {
                    var msg = "server error"
                    if (err.responseJSON.message) msg = err.responseJSON.message
                    alert(msg)
                }
            })
        }

        function delete_account_tree(id) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:"{{ __('accounting-module.are-u-sure-to-delete-cost-center') }}",
                icon:"warning",
                reverseButtons: false,
                buttons: {
                    confirm: {
                        text: "{{ __('words.yes_delete') }}",
                        className: "btn btn-danger",
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
                    var check_url = '{{ route("cost-centers.delete-ability") }}?id='+id
                    $.ajax({
                        dataType:'json',
                        type:'GET',
                        url:check_url,
                        success: function(response) {
                            if (response.status == 200) {
                                window.location = "{{ route('cost-centers.delete') }}?id="+id
                            } else {
                                swal({
                                    title:"{{ __('accounting-module.warning') }}",
                                    text:'{{ __('accounting-module.delete-with-transaction') .' '. __('accounting-module.cost-center') }}',
                                    icon:"warning",
                                    reverseButtons: false,
                                    buttons:{
                                        confirm: {
                                            text: "{{ __('words.yes_delete') }}",
                                            className: "btn btn-danger",
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
                                }).then(function(confirm_delete_2){
                                    if (confirm_delete_2) {
                                        window.location = "{{ route('cost-centers.delete') }}?id="+id
                                    } else {
                                        alert('{{ __('accounting-module.data-will-not-deleted') }}')
                                    }
                                })
                            }
                        },
                        error: function(err) {
                            var msg = "server error"
                            if (err.responseJSON.message) msg = err.responseJSON.message
                            alert(msg)
                        }
                    })
                } else {
                    alert("{{ __('accounting-module.cost-center-not-deleted') }}")
                }
            })
        }

        $(document).on('click' ,'.clickable' ,function () {
            var ul_tree_id = $(this).data('current-ul')
            var ul_tree = $("#"+ ul_tree_id)
            if (ul_tree.css('display') == 'none') {
                $(this).removeClass('fa-plus')
                $(this).addClass('fa-minus')
                $(this).siblings('span.folder-span').addClass('fa-folder-open')
                $(this).siblings('span.folder-span').removeClass('fa-folder')
                ul_tree.show()
            } else {
                $(this).removeClass('fa-minus')
                $(this).addClass('fa-plus')
                $(this).siblings('span.folder-span').removeClass('fa-folder-open')
                $(this).siblings('span.folder-span').addClass('fa-folder')
                ul_tree.hide()
            }
        })

        function cost_centers_form_submit(event) {
            event.preventDefault()
            var form = $(event.target) ,data = form.serialize() ,method = 'POST' ,url = form.attr('action')
            $.ajax({
                dataType: 'json',
                type: method,
                data: data,
                url: url,
                success: function (response) {
                    window.location = "{{ route('show-message') }}?message="+response.message
                },
                error: function (error) {
                    if (error.responseJSON.status == 400) {
                        alert(error.responseJSON.message)
                    } else {
                        $.each(error.responseJSON.errors, function (key, value) {
                            $('input[name="' + key + '"]')
                                .closest('.form-group')
                                .addClass('has-error')
                                .append('<span class="help-block">' + value + '</span>');
                        })
                    }
                }
            })
            return false;
        }

        $(document).on('change' ,'#cost-centers-form .form-control' ,function () {
            $(this).parent().removeClass('has-error');
            $(this).parent().find('span.help-block').remove()
        });
    </script>
@endsection