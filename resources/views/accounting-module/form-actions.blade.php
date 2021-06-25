<script type="application/javascript">
    $(document).ready(function() {
        $('select').select2()
    })
    
    function accountingModuleClearFrom(event) {
        swal({
            title:"{{ __('accounting-module.are you sure') }}",
            text:"{{ __('accounting-module.clear this form') }}",
            icon:"warning",
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
                $('form input').val('')
                $('form textarea').val('')
                $('form checkbox').val('')
                $('form .select2').val(null).trigger('change');
            } else {
                swal({
                    title:'{{ __('words.warning') }}',
                    text:'{{ __('words.clear_canceled') }}',
                    icon:"warning",
                    buttons:{
                        cancel: {
                            text: "{{ __('words.yes_delete') }}",
                            className: "btn btn-default",
                            value: null,
                            visible: true
                        }
                    }
                })
            }
        })
    }

    function accountingModuleCancelForm(url) {
        swal({
            title:"{{ __('accounting-module.are you sure') }}",
            text:"{{ __('accounting-module.cancel this form') }}",
            icon:"warning",
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
                swal({
                    title:'{{ __('words.warning') }}',
                    text:'{{ __('words.cancel_canceled') }}',
                    icon:"warning",
                    buttons:{
                        cancel: {
                            text: "{{ __('words.yes_delete') }}",
                            className: "btn btn-default",
                            value: null,
                            visible: true
                        }
                    }
                })
            }
        })
    }
</script>