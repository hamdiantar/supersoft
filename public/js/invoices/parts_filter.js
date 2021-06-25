function getSubPartsTypes(part_type_id, type) {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    var base_url = $('meta[name="base_url"]').attr('content');

    let url = base_url + '/admin/invoices/sub-parts-types';

    let branch_id = $("#branch_id").val();

    $.ajax({

        type: 'post',
        url: url,
        data: {_token: CSRF_TOKEN, part_type_id: part_type_id, type: type, branch_id: branch_id},

        // beforeSend: function () {
        //     $("#loader_store_auto_message").show();
        // },

        success: function (data) {

            if (data.type != 'sub_type') {

                $("#sub_parts_types_area").html(data.subTypes);
            }

            $("#parts_area").html(data.parts);
        },

        error: function (jqXhr, json, errorThrown) {
            var errors = jqXhr.responseJSON;
            swal({text: errors, icon: "error"})
        }
    });
}

function searchInSubPartsType() {

    let value = $("#searchInSubSparePartsType").val().toLowerCase();

    $(".searchResultSubSparePartsType td").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}

function searchInMainPartsType() {

    let value = $("#searchInSparePartsType").val().toLowerCase();

    $(".searchResultSparePartsType td").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}

function partsSearch() {

    let value = $("#searchInParts").val().toLowerCase();

    $(".searchResultSpareParts td").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}

function partsFilterFooter(type) {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    var base_url = $('meta[name="base_url"]').attr('content');

    let url = base_url + '/admin/invoices/parts-filter-footer';

    let branch_id = $("#branch_id").val();

    let part_type_id = $("#footer_main_part_type option:selected").val();

    if (type == 'sub_type') {

        part_type_id = $("#footer_sub_part_type option:selected").val();
    }

    $.ajax({

        url: url,

        method: 'POST',

        data: {branch_id: branch_id, _token: CSRF_TOKEN, part_type_id: part_type_id, type:type},

        success: function (data) {

            $('.js-example-basic-single').select2();

            $(".removeToNewData").remove();

            let   subTypesLabel = 'Select Sub Part Type';
            let   partsLabel = 'Select Part';
            let   partsBarcodeLabel = 'Search By Barcode';

            if (data.lang == 'ar') {

                subTypesLabel = 'اختر نوع قطعه الغيار الفرعيه';
                partsLabel = 'اختر  قطعه الغيار';
                partsBarcodeLabel = 'بحث بالباركود';
            }

            if (type != 'sub_type') {

                $(".removeToNewSubTypes").remove();

                // SUB TYPE
                var option = new Option(subTypesLabel, '');
                $("#footer_sub_part_type").append(option);

                $.each(data.subTypes, function (key, modelName) {

                    var option = new Option(modelName, modelName);
                    option.text = modelName['type_' + data.lang];
                    option.value = modelName['id'];

                    $("#footer_sub_part_type").append(option);
                });

                $('#footer_sub_part_type option').addClass(function () {
                    return 'removeToNewSubTypes';
                });
            }

            // PARTS
            var partDefaultOption = new Option(partsLabel, '');
            $("#footer_filter_parts").append(partDefaultOption);

            $.each(data.parts, function (key, modelName) {

                var option = new Option(modelName, modelName);
                option.text = modelName['name_' + data.lang];
                option.value = modelName['id'];

                $("#footer_filter_parts").append(option);
            });

            $('#footer_filter_parts option').addClass(function () {
                return 'removeToNewData';
            });


            // PARTS barcode

            var partBarcodeDefaultOption = new Option(partsBarcodeLabel, '');
            $("#partOFBarcode").append(partBarcodeDefaultOption);

            $.each(data.parts, function (key, modelName) {

                if (modelName['barcode'] != null) {

                    var option = new Option(modelName, modelName);
                    option.text = modelName['barcode'];
                    option.value = modelName['id'];

                    $("#partOFBarcode").append(option);
                }
            });

            $('#partOFBarcode option').addClass(function () {
                return 'removeToNewData';
            });
        }
    });
}
