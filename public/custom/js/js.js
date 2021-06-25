function preview_image(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('output_image');
        $("#output_image").show();
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

//for delete selected
$(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
            this.checked = checked;
        });
    })
});

function change_theme(url) {
    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: url,
        success: function() {
            location.reload()
        }
    })
}