<script type="application/javascript">
    $(document).on('change' ,"select[name='accounts_tree_root_id']" ,function () {
        var root_id = $(this).find('option:selected').val()
        $.ajax({
            dataType:'json',
            type:'GET',
            url:'{{ route('fetch-accounts-tree-branches') }}?root_id=' + root_id,
            success: function (response) {
                $("select[name='accounts_tree_id']").html(response.html_options)
                $("select[name='accounts_tree_id']").select2()
            },
            error: function (err) {
                alert('server error')
            }
        })
    })

    $(document).on('click' ,'#form-save-btn' ,function () {
        $('#form-save-btn').prop('disabled', true)
        var form = $('{{ $form_id }}') ,data = form.serialize(),
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
                $('#form-save-btn').prop('disabled', false)
            }
        })
    })
</script>