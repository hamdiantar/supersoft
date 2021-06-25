<script type="application/javascript">
    $(document).on('click' ,'input[name="related_as"]' ,function () {
        let nature = $(this).val()
        if (nature == 'actor_group') {
            $("#select-actor_group").show()
            $("#select-actor_id").hide()
        } else if (nature == 'actor_id') {
            $("#select-actor_group").hide()
            $("#select-actor_id").show()
        } else {
            $("#select-actor_group").hide()
            $("#select-actor_id").hide()
        }
    })
</script>