<script type="application/javascript">
    function alert(message ,callback) {
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
        .then(() => {
            if (callback) callback()
        })
    }

    const current_lang = '{{ app()->getLocale() }}'

    $(document).on('change' ,'select[name="branch_id"]' ,function () {
        const custom_branch = $(this).find('option:selected').val()
        loadMainTypes(custom_branch)
        loadSubTypes(custom_branch)
        loadParts(custom_branch)
        loadAllParts(custom_branch)

    })

    $(document).on('change' ,'#main-type-selector' ,function () {
        const branch = $('[name="branch_id"]').val() ,type_id = $(this).find('option:selected').val()
        loadSubTypes(branch ,type_id)
        loadParts(branch ,type_id)

    })

    $(document).on('change' ,'#sub-type-selector' ,function () {
        const branch = $('[name="branch_id"]').val() ,type_id = $(this).find('option:selected').val()
        loadParts(branch ,type_id)
    })

    $(document).on('change' ,'#part-selector' ,function () {
        const selected_id = $(this).find('option:selected').val()
        if (selected_id) {
            loadPartRow(selected_id)
        }
    })

    $(document).on('change' ,'[data-row-input="qnty"]' ,function () {
        let buy_price = $(this).parent().siblings('td').find('input[data-row-input="buy-price"]').val(),
            qnt = $(this).val()
        if (buy_price == '') buy_price = 0
        if (qnt == '') qnt = 0
        $(this).parent().siblings('td').find('input[data-row-input="total"]').val(parseFloat(buy_price) * parseFloat(qnt))
        calculate_total_money()
    })

    $(document).on('change' ,'[data-row-input="buy-price"]' ,function () {
        let qnt = $(this).parent().siblings('td').find('input[data-row-input="qnty"]').val(),
            buy_price = $(this).val()
        if (buy_price == '') buy_price = 0
        if (qnt == '') qnt = 0
        $(this).parent().siblings('td').find('input[data-row-input="total"]').val(parseFloat(buy_price) * parseFloat(qnt))
        calculate_total_money()
    })

    $(document).on('change' ,'[data-row-input="unit"]' ,function () {
        const default_qnt = $(this).find('option:selected').data('quantity'),
            purchase_price = $(this).find('option:selected').data('purchase-price'),
            price_segments = $(this).find('option:selected').data('price-segment-json')
        let segment_options = '<option value="" data-purchase-price="0"> {{ __('Select') }} </option>'

        $(this).parent().siblings('td').find('input[data-row-input="defualt-qnty"]').val(default_qnt)
        $(this).parent().siblings('td').find('input[data-row-input="buy-price"]').val(purchase_price)
        $(this).parent().siblings('td').find('input[data-row-input="qnty"]').change()

        price_segments.forEach(segment => {
            segment_options += `
                <option value="${segment.id}" data-purchase-price="${segment.purchase_price}">
                    ${segment.name}
                </option>`
        })

        $(this).parent().siblings('td').find('select[data-row-input="price-segment"]').html(segment_options)
        $(this).parent().siblings('td').find('select[data-row-input="price-segment"]').select2()
        calculate_total_money()
    })

    $(document).on('change' ,'select[data-row-input="price-segment"]' ,function () {
        const purchase_price = $(this).find('option:selected').data('purchase-price')
        const price_segment = $(this).find('option:selected').val();

        $(this).parent().siblings('td').find('input[data-row-input="buy-price"]').val(purchase_price)
        $(this).parent().siblings('td').find('input[data-row-input="qnty"]').change()
    })

    function calculate_total_money() {
        let total_money = 0
        $('tr').each(function () {
            const price = $(this).find('input[data-row-input="buy-price"]').val(),
                qnty = $(this).find('input[data-row-input="qnty"]').val()
            if (price && qnty) total_money += parseFloat(qnty) * parseFloat(price)
        })
        $("#total-money").val(total_money)
    }

    function loadPartRow(part_id) {
        const branch = $('[name="branch_id"]').val()
        $("#loader-modal").modal('toggle')
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: `{{ route('opening-balance.build-row') }}?part_id=${part_id}&branch=${branch}`,
            success: function (response) {
                if(response.status && response.status == 'error') alert(response.message)
                else $("#opening-balance-items").append(response.row_code)
                $('.select2-updated').select2()
                $("#loader-modal").modal('hide')
            },
            error: function (err) {
                alert('{{ __('words.back-support') }}')
            }
        })
    }

    function remove_my_row_parent(event ,item_id) {
        swal({
            title: '{{ __('opening-balance.warning') }}',
            text: '{{ __('opening-balance.confirm-row') }}',
            icon: "warning",
            reverseButtons: false,
            buttons:{
                cancel: {
                    text: "{{ __('words.no') }}",
                    className: "btn btn-primary",
                    value: null,
                    visible: true
                },
                confirm: {
                    text: "{{ __('words.yes_delete') }}",
                    className: "btn btn-default",
                    value: true,
                    visible: true
                }
            }
        }).then(approved => {
            if (approved) {
                $(event.target).parent().parent().remove()
                calculate_total_money()
                if (item_id != undefined) {
                    $("#opening-balance-form").prepend(`<input type="hidden" name="deleted_ids[]" value="${item_id}"/>`)
                }
            }
        })
    }

    function loadMainTypes(branch_id) {
        branch_id = branch_id === undefined ? branch : branch_id
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: `{{ route('admin:part-types.main-part-types') }}?branch_id=${branch_id}`,
            success: function (response) {
                $('#main-type-selector').html(response.options)
                $('#main-type-selector').select2()
            },
            error: function (err) {
                alert('{{ __('words.back-support') }}')
            }
        })
    }

    function loadSubTypes(branch_id ,main_type_id) {
        branch_id = branch_id === undefined ? branch : branch_id
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: `{{ route('admin:part-types.sub-part-types') }}?branch_id=${branch_id}${main_type_id ? '&type_id='+main_type_id : '' }`,
            success: function (response) {
                $('#sub-type-selector').html(response.options)
                $('#sub-type-selector').select2()
            },
            error: function (err) {
                alert('{{ __('words.back-support') }}')
            }
        })
    }

    function loadParts(branch_id ,type_id) {
        branch_id = branch_id === undefined ? branch : branch_id
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: `{{ route('opening-balance.get-parts') }}?branch=${branch_id}${type_id ? '&type_id='+type_id : '' }`,
            success: function (response) {
                $('#part-selector').html(response.parts)
                $('#part-selector').select2()
            },
            error: function (err) {
                alert('{{ __('words.back-support') }}')
            }
        })
    }

    function loadAllParts(branch_id) {
        branch_id = branch_id === undefined ? branch : branch_id
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: `{{ route('admin:part-types.getAllParts') }}?branch_id=${branch_id}`,
            success: function (response) {
                $('#loadAllParts').html(response.options)
                $('#loadAllParts').select2()
            },
            error: function (err) {
                alert('{{ __('words.back-support') }}')
            }
        })
    }
</script>
