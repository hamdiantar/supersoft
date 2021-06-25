<script type="application/javascript">
    var account_type_credit_options = '<option value=""> {{ __('accounting-module.select-one') }} </option>'
    var account_type_debit_options = '<option value=""> {{ __('accounting-module.select-one') }} </option>'
    var credit_revenues_items = [] ,debit_expenses_items = []

    @foreach($debit_expenses as $debit_e_type)
        account_type_debit_options += '<option value="{{ $debit_e_type->id }}">'
        account_type_debit_options += '{{ $debit_e_type->name }}'
        account_type_debit_options += '</option>'
    @endforeach
    @foreach($credit_revenues as $credit_r_type)
        account_type_credit_options += '<option value="{{ $credit_r_type->id }}">'
        account_type_credit_options += '{{ $credit_r_type->name }}'
        account_type_credit_options += '</option>'
    @endforeach

    @foreach($credit_revenues_items as $credit_r_item)
        credit_revenues_items.push({
            id: {{ $credit_r_item->id }},
            type_id: {{ $credit_r_item->revenue_id }},
            name: '{{ $credit_r_item->name }}'
        })
    @endforeach
    @foreach($debit_expenses_items as $debit_e_item)
        debit_expenses_items.push({
            id: {{ $debit_e_item->id }},
            type_id: {{ $debit_e_item->expense_id }},
            name: '{{ $debit_e_item->name }}'
        })
    @endforeach

    $(document).on('click' ,'input[name="account_nature"]' ,function () {
        let nature = $(this).val()
        if (nature == 'credit') {
            $("select[name='account_type_id']").html(account_type_credit_options)
        } else if (nature == 'locker') {
            $("#locker-id").show()
            $("#bank-acc-id").hide()
        } else if (nature == 'bank_acc') {
            $("#locker-id").hide()
            $("#bank-acc-id").show()
        } else {
            $("select[name='account_type_id']").html(account_type_debit_options)
        }
        $("select[name='account_type_id']").select2()
    })

    $(document).on('change' ,"select[name='account_type_id']" ,function () {
        var type_id = $(this).find('option:selected').val(),
            item_options_array = [] ,
            item_options = '<option value=""> {{ __('accounting-module.select-one') }} </option>'

        if ($('input[name="account_nature"]:checked').val() == 'credit') {
            item_options_array = credit_revenues_items
        } else {
            item_options_array = debit_expenses_items
        }

        item_options_array.map(item => {
            if (item.type_id == type_id) {
                item_options += '<option value="'+item.id+'">' + item.name + '</option>'
            }
        })

        $('select[name="account_item_id"]').html(item_options)
        $('select[name="account_item_id"]').select2()
    })

    $(document).on('change' ,"select[name='account_item_id']" ,function () {
        $('select[name="accounts_tree_root_id"]').find('option:first').prop('selected' ,'selected')
        $('select[name="accounts_tree_root_id"]').select2()
    })
</script>