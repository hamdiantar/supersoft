@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Spare parts types') }} </title>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{__('Spare parts types')}}</li>
        </ol>
    </nav>
    <div class="col-md-12">
        <input type="hidden" id="selected-part-type-id"/>
        <br>
        <div class="col-md-12" style="margin-bottom: 20px">
            @include('admin.part-types.buttons')
        </div>

        <div class="col-xs-12 ui-sortable-handle">
            <div class="box-content card bordered-all primary">
                <h4 class="box-title bg-primary"><i class="ico fa fa-check-circle"></i>
                    {{ __('Spare parts types') }}
                </h4>
                <div class="card-content">
                    {!! $tree !!}
 
  
    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('accounting-module-modal-area')
    <div class="remodal" data-remodal-id="part-type-modal" role="dialog" aria-labelledby="modal1Title"
        aria-describedby="modal1Desc">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div id="form-modal"></div>
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

        function select_part_type(id, ele) {
            openTree(ele)
            $('.span-selected').removeClass('span-selected')
            $(event.target).addClass('span-selected')
            $("#selected-part-type-id").val(id)
        }

        function clearSelectedType() {
            $('.span-selected').removeClass('span-selected')
            $("#selected-part-type-id").val('')
        }

        function open_modal(action_for) {
            if ($('.span-selected').length <= 0 && (action_for == 'edit' || action_for == 'delete')) {
                alert('{{ __('words.choose-part-type-to-edit') }}')
            } else {
                switch(action_for) {
                    case "create":
                        return createPartType($("#selected-part-type-id").val())
                    case "edit":
                        return editPartType($("#selected-part-type-id").val())
                    case "delete":
                        return deletePartType($("#selected-part-type-id").val())
                }
            }
        }

        function createPartType(id) {
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: `{{ route("admin:part-types.form" ,['action_for' => 'create' ]) }}${id ? '?parent_id='+id : ''}`,
                success: function (response) {
                    $("#form-modal").html(response.html_code)
                    $('[data-remodal-id=part-type-modal]').remodal().open()
                    // $('select[name="branch_id"]').select2()
                },
                error: function (err) {
                    const msg = err.responseJSON.message
                    alert(msg ? msg : "server error")
                }
            })
        }

        function editPartType(id) {
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: `{{ route("admin:part-types.form" ,['action_for' => 'edit' ]) }}${id ? '?id='+id : ''}`,
                success: function (response) {
                    $("#form-modal").html(response.html_code)
                    $('[data-remodal-id=part-type-modal]').remodal().open()
                    // $('select[name="branch_id"]').select2()
                },
                error: function (err) {
                    var msg = "server error"
                    if (err.responseJSON.message) msg = err.responseJSON.message
                    alert(msg)
                }
            })
        }

        function deletePartType(id) {
            swal({
                title:"{{ __('words.warning') }}",
                text:"{{ __('words.are-u-sure-to-delete-part-type') }}",
                icon:"warning",
                reverseButtons: false,
                buttons: {
                    confirm: {text: "{{ __('words.yes_delete') }}", className: "btn btn-danger", value: true, visible: true},
                    cancel: {text: "{{ __('words.no') }}", className: "btn btn-default", value: null, visible: true}
                }
            }).then(function(confirm_delete){
                if (confirm_delete) {
                    window.location = "{{ route('admin:part-types.delete') }}?id="+id
                } else {
                    alert("{{ __('words.part-type-not-deleted') }}")
                }
            })
        }

        function openTree(element) {
            let targetElement = $('.' + element)
            let ul_tree_id = targetElement.data('current-ul')
            let ul_tree = $("#" + ul_tree_id)
            if (ul_tree.css('display') === 'none') {
                targetElement.removeClass('fa-plus')
                targetElement.addClass('fa-minus')
                targetElement.siblings('span.folder-span').addClass('fa-folder-open')
                targetElement.siblings('span.folder-span').removeClass('fa-folder')
                ul_tree.show()
            } else {
                targetElement.removeClass('fa-minus')
                targetElement.addClass('fa-plus')
                targetElement.siblings('span.folder-span').removeClass('fa-folder-open')
                targetElement.siblings('span.folder-span').addClass('fa-folder')
                ul_tree.hide()
            }
        }

        $(document).on('change' ,'#cost-centers-form .form-control' ,function () {
            $(this).parent().removeClass('has-error');
            $(this).parent().find('span.help-block').remove()
        });
    </script>
@endsection
