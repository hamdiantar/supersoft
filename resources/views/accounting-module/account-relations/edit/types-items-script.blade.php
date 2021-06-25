<script type="application/javascript">
    var account_type_credit_options = '<option value=""> {{ __('accounting-module.select-one') }} </option>'
    var account_type_debit_options = '<option value=""> {{ __('accounting-module.select-one') }} </option>'
    var credit_revenues_items = [] ,debit_expenses_items = []

    @foreach($debit_expenses as $debit_e_type)
        @php
            $is_selected =
                $model &&
                $model->related_expense_revenue->related_as == 'debit' &&
                $model->related_expense_revenue->type_id == $debit_e_type->id ?
                'selected' : ''
        @endphp
        account_type_debit_options += '<option {{ $is_selected }} value="{{ $debit_e_type->id }}">'
        account_type_debit_options += '{{ $debit_e_type->name }}'
        account_type_debit_options += '</option>'
    @endforeach
    @foreach($credit_revenues as $credit_r_type)
        @php
            $is_selected =
                $model &&
                $model->related_expense_revenue->related_as == 'credit' &&
                $model->related_expense_revenue->type_id == $credit_r_type->id ?
                'selected' : ''
        @endphp
        account_type_credit_options += '<option {{ $is_selected }} value="{{ $credit_r_type->id }}">'
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
        // $("#account-type-id").show()
        // $("#account-item-id").hide()
        // $("#accounts-tree-root-id").hide()
        // $("#accounts-tree-id").hide()
        // $("#save-btn").hide()
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
                var selected_item = '{{ $model->related_expense_revenue->item_id }}'
                var is_selected = item.id == selected_item ? 'selected' : ''
                item_options += '<option '+is_selected+' value="'+item.id+'">' + item.name + '</option>'
            }
        })

        $('select[name="account_item_id"]').html(item_options)
        $('select[name="account_item_id"]').select2()

        // $("#account-item-id").show()
        // $("#accounts-tree-root-id").hide()
        // $("#accounts-tree-id").hide()
        // $("#save-btn").hide()
    })

    $(document).on('change' ,"select[name='account_item_id']" ,function () {
        changeAccountItemId(true)
    })

    function changeAccountItemId(select_first) {
        if (select_first)
            $('select[name="accounts_tree_root_id"]').find('option:first').prop('selected' ,'selected')
        $('select[name="accounts_tree_root_id"]').select2()
        // $("#accounts-tree-root-id").show()
        // $("#accounts-tree-id").hide()
        // $("#save-btn").hide()
    }

    $(document).on('change' ,"select[name='accounts_tree_root_id']" ,function () {
        var root_id = $(this).find('option:selected').val(),
            account_id = {{ $model->accounts_tree_id }}
        changeRootAcc(root_id ,account_id)
    })

    $(document).on('change' ,"select[name='accounts_tree_id']" ,function () {
        if($(this).find('option:selected').val() == '') {
            // $("#save-btn").hide()
        } else {
            // $("#save-btn").show()
        }
    })

    $(document).on('click' ,'#save-btn-clickable' ,function () {
        var clicked_btn = $(this).prop('id')
        var form = $("#account-relations-edit-form")
        var data = form.find('[name!=_method]').serialize(),
            url = '{{ route('check-account-relation-unique') }}'
        $.ajax({
            dataType: 'json',
            type: 'POST',
            data: data,
            url: url,
            success: function (response) {
                form.submit()
            },
            error: function (err) {
                alert(err.responseJSON.message)
            }
        })
    })

    $(document).ready(function () {
        $('input[name="account_nature"]:checked').click()
        $("select[name='account_type_id']").change()
        changeAccountItemId(false)
        changeRootAcc({{ $model->accounts_tree_root_id }} ,{{ $model->accounts_tree_id }})
    })

    function changeRootAcc(root_id ,account_id) {
        $.ajax({
            dataType:'json',
            type:'GET',
            async: false,
            url:'{{ route('fetch-accounts-tree-branches') }}?root_id=' + root_id + '&account_id=' + account_id,
            success: function (response) {
                $("select[name='accounts_tree_id']").html(response.html_options)
                $("select[name='accounts_tree_id']").select2()
                // $("#accounts-tree-id").show()
                var selected_option = $("select[name='accounts_tree_id']").find('option:selected').val()
                // if (selected_option == account_id)
                    // $("#save-btn").show()
                // else
                    // $("#save-btn").hide()
            },
            error: function (err) {
                alert('server error')
            }
        })
    }
</script>