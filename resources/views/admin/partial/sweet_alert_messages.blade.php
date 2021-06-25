<script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

<script type="application/javascript">
    $(document).on('click', '#reset', function () {
        swal({
            title: "{{__('Reset Form')}}",
            text: "{{__('Are you sure want to reset form ?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                $(".form-control").val("");
                swal("{{__('successfully')}}", "{{__('form has been reset')}}", "success");
            }
        });
    });

    $(document).on('click', '#back', function () {
        swal({
            title: "{{__('Return Back')}}",
            text: "{{__('Are you sure want to return back ?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.history.back();
            }
        });
    });
</script>
