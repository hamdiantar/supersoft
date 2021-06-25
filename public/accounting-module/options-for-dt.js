var visible_columns = []

function change_rows(event) {
    var form = $("#filtration-form") ,select = $(event.target) ,
        option = select.find('option:selected')
    form.find('input[name="rows"]').val(option.val())
    form.find('input[name="invoker"]').val('search')
    form.submit()
}

function free_search(input_selector ,input_name) {
    var input_value = $(input_selector).val() ,form = $("#filtration-form")
    form.find('input[name="'+input_name+'"]').val(input_value)
    form.find('input[name="invoker"]').val('search')
    form.submit()
}

function appling_sort(event ,sort_by) {
    var form = $("#filtration-form") ,
        sort_method = $(event.target).data('sort-method') == 'desc' ? 'asc' : 'desc'
    form.find('input[name="sort_by"]').val(sort_by)
    form.find('input[name="invoker"]').val('search')
    form.find('input[name="sort_method"]').val(sort_method)
    form.submit()
}

function invoke(method_to_run ,event) {
    var form = $("#filtration-form")
    $("input[name='invoker']").val(method_to_run)
    if (method_to_run == 'search') return
    form.attr('target' ,'_blank')
    form.append('<input name="visible_columns" value="'+visible_columns.join(',')+'" type="hidden"/>')
    form.submit()
    $("input[name='invoker']").val('')
    form.removeAttr('target')
}

function change_columns(event) {
    $("#colum-visible-modal").modal('toggle')
}

function hide_column(event ,column_class_name) {
    if($(event.target).hasClass('btn-info')) {
        $('.column-' + column_class_name).show()
        $(event.target).removeClass('btn-info')
        $(event.target).addClass('btn-default')
        var __index = visible_columns.indexOf(column_class_name)
        if (__index > -1)
            visible_columns.splice(__index ,1)
    } else {
        visible_columns.push(column_class_name)
        $('.column-' + column_class_name).hide()
        $(event.target).addClass('btn-info')
        $(event.target).removeClass('btn-default')
    }
    return
}

function change_tree_options(event ,base_url ,selected) {
    var tree_id = $(event.target).find('option:selected').val()
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: base_url + '?tree_id=' + tree_id + '&selected=' + selected,
        success: function(response) {
            $('select[name="acc_tree_id"]').html(response.tree_options)
            $('select[name="acc_tree_id"]').select2()
        },
        error: function(err) {
            alert('server error')
        }
    })
}
function change_cost_options(event ,base_url ,selected) {
    var root_id = $(event.target).find('option:selected').val()
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: base_url + '?root_id=' + root_id + '&selected=' + selected,
        success: function(response) {
            $('select[name="cost_id"]').html(response.centers_options)
            $('select[name="cost_id"]').select2()
        },
        error: function(err) {
            alert('server error')
        }
    })
}
