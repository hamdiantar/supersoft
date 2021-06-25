function setServiceValues(id) {

    let purchase_qty = $('#purchased-qy-' + id);

    let purchase_price = $('#purchased-price-' + id);
    let total = $("#subtotal-" + id);
    let discount_amount = $("#radio-10-discount-amount-" + id);
    let discount_percent = $("#radio-12-discount-percent-" + id);
    let discount = $("#discount-" + id).val();

    var item_total = (purchase_qty.val() * purchase_price.val()).toFixed(2);

    total.val(item_total);

    if (discount_amount.prop('checked')) {
        implementDiscount(discount, discount_amount.val(), total.val(), id);
    } else {
        implementDiscount(discount, discount_percent.val(), total.val(), id);
    }

    calculateTaxOnPart(id);

    calculateInvoiceTotal();

    calculateTax();
}


function implementDiscount(discount, discount_type, total, id) {
    if (discount == "") {
        discount = 0;
    }

    if (discount_type == 'amount') {
        var price_after_discount = parseFloat(total) - parseFloat(discount);

    } else {
        var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);

        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    $("#total-after-discount-" + id).val(price_after_discount);

    calculateTaxOnPart(id);
}

function resetItem(id) {
    $('#purchased-qy-' + id).val(0);
    $('#discount-' + id).val(0);
    $('#total-' + id).val(0);
    $('#total-after-discount-' + id).val(0);
}

function calculateInvoiceTotal() {

    var items_count = $("#invoice_items_count").val();

    var total = 0;

    for (var i = 1; i <= items_count; i++) {

        var part_id = $("#part-" + i).val();

        if (part_id) {
            // total += parseFloat($("#total-after-discount-" + part_id).val());
            total += parseFloat($("#total-part-" + part_id).val());
        }
    }

    $("#subtotal").val(total);
    $("#total").val(total);

    implementInvoiceDiscount();

    calculateTax();
}

function implementInvoiceDiscount() {

    var discount = $("#discount").val();

    if (!discount) {
        discount = 0;
    }

    var invoice_discount_amount = $("#invoice_discount_amount");
    var invoice_discount_percent = $("#invoice_discount_percent");
    var total = $("#subtotal").val();
    var price_after_discount = 0;

    var parts_items_count = $("#invoice_items_count").val();
    let supplier_discount = 0;

    if ($('#supplier_discount_check').is(':checked')) {

        supplier_discount = supplierDiscount();

    } else {

        $("#supplier_discount_check").prop('checked', false);
    }


    if (parts_items_count == 0) {
        swal({
            text: "{{__('you can not add discount now!')}}",
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                $("#discount").val(0);
                $("#total_after_discount").val(total);
                $("#supplier_discount_check").prop('checked', false);
            }
        });
    }

    if (invoice_discount_amount.val() == "" || invoice_discount_percent.val() == "") {
        discount = 0;
    }

    if (invoice_discount_amount.prop('checked')) {

        price_after_discount = parseFloat(total) - parseFloat(discount) - parseFloat(supplier_discount);

    } else {

        var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);

        price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value) - parseFloat(supplier_discount);
    }

    if (price_after_discount <= 0) {
        price_after_discount = 0;
    }

    $("#total_after_discount").val(price_after_discount);

    calculateTax();
}

function calculateTax() {

    var total_after_discount = $("#total_after_discount").val();
    var tax_count = $("#tax_count").val();
    var total_tax = 0;
    var subtotal = $("#subtotal").val();

    if (subtotal == 0) {

        $("#invoice_tax").val(0);
        $("#total_afetr_discount").val(0);
        $("#final_total").val(0);
        return false;

    }

    for (var i = 1; i <= tax_count; i++) {

        var type = $("#tax_type_" + i).val();
        var value = $("#tax_value_" + i).val();


        if (type == 'amount') {

            if ($("#tax_check_" + i).is(':checked')) {
                total_tax += parseFloat(value);
            }

            $("#tax_value_after_discount_" + i).val(value);

        } else {

            var tax_value = parseFloat(total_after_discount) * parseFloat(value) / 100;

            if ($("#tax_check_" + i).is(':checked')) {

                total_tax += parseFloat(tax_value);
            }

            $("#tax_value_after_discount_" + i).val(tax_value);
        }
    }

    var total = parseFloat(total_after_discount) + parseFloat(total_tax);

    if (total <= 0)
        total = 0;

    // if (total_tax <= 0)
    //     total_tax = 0;

    $("#total").val(total.toFixed(2));

    $("#invoice_tax").val(total_tax.toFixed(2));
}

function unitPrices(part_id) {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    var base_url = $('meta[name="base_url"]').attr('content');

    let url = base_url + '/admin/purchase-invoices/part-unit-prices';

    let price_id = $("#part_prices_" + part_id + " option:selected").val();

    let purchase_price = $("#part_prices_" + part_id + " option:selected").data('purchase-price')

    $("#purchased-price-" + part_id).val(purchase_price);

    setServiceValues(part_id);

    $.ajax({

        url: url,

        method: 'get',

        data: {_token: CSRF_TOKEN, price_id: price_id, part_id: part_id},

        success: function (data) {

            $("#td_unit_prices_" + part_id).html(data.view);

            $('.js-example-basic-single').select2();
        },

        error: function (jqXhr, json, errorThrown) {
            var errors = jqXhr.responseJSON;
            toastr.error(errors);
        },

    });
}

function segmentPrices(part_id) {

    let purchase_price = $("#unit_prices_" + part_id + " option:selected").data('purchase-price');

    $("#purchased-price-" + part_id).val(purchase_price);

    setServiceValues(part_id);
}

function selectTaxes(index) {

    let tax_value_after_discount = $('#tax_value_after_discount_' + index).val();
    let invoice_tax = $('#invoice_tax').val();
    let total = $('#total').val();

    if ($("#tax_check_" + index).is(':checked')) {

        let val = parseFloat(invoice_tax) + parseFloat(tax_value_after_discount);

        $('#invoice_tax').val(val.toFixed(2));

        let total_val = parseFloat(total) + parseFloat(tax_value_after_discount);

        $('#total').val(total_val.toFixed(2));

        $("#tax_status_check_" + index).prop('disabled', false);

    } else {

        let val = parseFloat(invoice_tax) - parseFloat(tax_value_after_discount);

        $('#invoice_tax').val(val.toFixed(2));

        let total_val = parseFloat(total) - parseFloat(tax_value_after_discount);

        $('#total').val(total_val.toFixed(2));

        $("#tax_status_check_" + index).prop('disabled', true);
    }
}

function calculateTaxOnPart (part_id) {

    var total_after_discount = $("#total-after-discount-" + part_id).val();
    var tax_count = $("#part_taxes_count_" + part_id).val();
    var total_part_tax = 0;

    if (total_after_discount == 0) {
        return false;
    }

    if (tax_count != 0) {

        for (var i = 0; i <= tax_count; i++) {

            var type = $("#part_tax_check_" + part_id + '_' + i).data('tax-type');
            var value = $("#part_tax_check_" + part_id + '_' + i).data('tax-value');

            if (type == 'amount') {

                if ($("#part_tax_check_" + part_id + '_' + i).is(':checked')) {
                    total_part_tax += parseFloat(value);
                }

            } else {

                var tax_value = parseFloat(total_after_discount) * parseFloat(value) / 100;

                if ($("#part_tax_check_" + part_id + '_' + i).is(':checked')) {

                    total_part_tax += parseFloat(tax_value);
                }
            }
        }
    }

    console.log(total_part_tax);

    var total = parseFloat(total_after_discount) + parseFloat(total_part_tax);

    if (total <= 0)
        total = 0;

    $("#total-part-" + part_id).val(total.toFixed(2));
}
