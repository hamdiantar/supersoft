<script type="application/javascript">
    $(document).on('click' ,'input[name="account_nature"]' ,function () {
        let nature = $(this).val()
        if (nature == 'locker') {
            $("#locker-id").show()
            $("#bank-acc-id").hide()
        } else {
            $("#locker-id").hide()
            $("#bank-acc-id").show()
        }
    })
</script>