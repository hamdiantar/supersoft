<script type="application/javascript">
    $(document).on('click' ,'input[name="related_as"]' ,function () {
        let nature = $(this).val()
        if (nature == 'store') {
            $("#related_id").show()
        } else {
            $("#related_id").hide()
        }
    })
</script>