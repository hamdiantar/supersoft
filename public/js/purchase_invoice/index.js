function changeType() {

    let quotation_type = $('#quotation_type').find(":selected").val();

    if (quotation_type == 'from_supply_order') {

        $(".purchase_request_type").show();
        $(".out_purchase_request_type").hide();

    } else {

        $(".purchase_request_type").hide();
        $(".out_purchase_request_type").show();
    }

    $(".remove_on_change_branch").remove();
    $("#items_count").val(0);

    calculateTotal();
}

function getPurchasePrice(index) {

    let purchase_price = $('#prices_part_' + index).find(":selected").data('purchase-price');
    $('#price_' + index).val(purchase_price);
}

function calculateItem(index) {

    let quantity = $("#quantity_" + index).val();
    let price = $("#price_" + index).val();
    let discount = $("#discount_" + index).val();
    let total = parseFloat(quantity) * parseFloat(price);

    $('#total_before_discount_' + index).val(total.toFixed(2));

    calculateItemDiscount(discount, total, index);

    calculateItemTaxes(index);

    calculateTotal();
}

function calculateItemDiscount(discount, total, id) {

    if (discount == "") {
        discount = 0;
    }

    if ($("#discount_type_amount_" + id).is(':checked')) {

        let big_amount_discount = $('#prices_part_' + id).find(":selected").data('big-amount-discount');

        if (parseFloat(discount) > parseFloat(big_amount_discount)) {

            swal({
                text: " sorry big amount discount is " + big_amount_discount,
                icon: "warning",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $("#discount_" + id).val(0);
                }
            });
            discount = 0;
        }

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    } else {

        let big_percent_discount = $('#prices_part_' + id).find(":selected").data('big-percent-discount');

        if (parseFloat(discount) > parseFloat(big_percent_discount)) {

            swal({
                text: " sorry big percent discount is " + big_percent_discount,
                icon: "warning",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $("#discount_" + id).val(0);
                }
            });
            discount = 0;
        }

        var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);

        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    if (price_after_discount <= 0)
        price_after_discount = 0;

    $("#total_after_discount_" + id).val(price_after_discount.toFixed(2));
}

function calculateItemTaxes(index) {

    let taxes_count = $('#tax_count_' + index).val();

    let total_taxes = 0;
    let total_after_discount = $('#total_after_discount_' + index).val();

    for (let i = 1; i <= taxes_count; i++) {

        if ($('#checkbox_tax_' + i + '_' + index).is(':checked')) {

            let tax_type = $('#checkbox_tax_' + i + '_' + index).data('tax-type');
            let tax_value = $('#checkbox_tax_' + i + '_' + index).data('tax-value');
            let tax_execution_time = $('#checkbox_tax_' + i + '_' + index).data('tax-execution-time');

            if (tax_execution_time == 'after_discount') {

                var totalUsedToCalculate = $('#total_after_discount_' + index).val();

            } else {

                var totalUsedToCalculate = $('#total_before_discount_' + index).val();
            }

            if (tax_type == 'amount') {

                total_taxes += parseFloat(tax_value);
                $("#calculated_tax_value_" + i + '_' + index).text(tax_value);

            } else {

                let tax_percent_value = parseFloat(totalUsedToCalculate) * parseFloat(tax_value) / 100;
                total_taxes += parseFloat(tax_percent_value);

                $("#calculated_tax_value_" + i + '_' + index).text(tax_percent_value);
            }
        }
    }

    var total = parseFloat(total_after_discount) + parseFloat(total_taxes);

    $("#total_" + index).val(total.toFixed(2));
    $("#tax_" + index).val(total_taxes.toFixed(2));
}

function calculateTotal() {

    let items_count = $('#items_count').val();

    let total = 0;

    for (let i = 1; i <= items_count; i++) {

        if ($('#price_' + i).length) {
            total += parseFloat($('#total_' + i).val());
        }
    }

    $('#sub_total').val(total.toFixed(2));

    calculateInvoiceDiscount();
}

function calculateInvoiceDiscount() {

    let discount = $("#discount").val();
    let total = $("#sub_total").val();
    let supplier_discount = 0;
    let supplier_id = $('#supplier_id').find(":selected").val();

    if (discount == "") {
        discount = 0;
    }

    if ($('#discount_type_amount').is(':checked')) {

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    } else {

        let discount_percent_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);
        var price_after_discount = parseFloat(total) - parseFloat(discount_percent_value);
    }

    if ($('#supplier_discount_check').is(':checked') && supplier_id != '') {
        supplier_discount = supplierDiscount();
    }

    price_after_discount -= parseFloat(supplier_discount);

    if (price_after_discount <= 0)
        price_after_discount = 0;

    $("#total_after_discount").val(price_after_discount.toFixed(2));

    calculateTax();
}

function calculateTax() {

    var total_after_discount = $("#total_after_discount").val();
    var tax_count = $("#invoice_tax_count").val();
    var total_tax = 0;
    var sub_total = $("#sub_total").val();

    if (sub_total == 0) {
        $("#tax").val(0);
        $("#total_after_discount").val(0);
        $("#total").val(0);
        return false;
    }

    for (var i = 1; i <= tax_count; i++) {

        if ($('#checkbox_tax_' + i).is(':checked')) {

            var type = $('#checkbox_tax_' + i).data('tax-type');
            var value = $('#checkbox_tax_' + i).data('tax-value');

            let tax_execution_time = $('#checkbox_tax_' + i).data('tax-execution-time');

            if (tax_execution_time == 'after_discount') {

                var totalUsedToCalculate = $("#total_after_discount").val();

            } else {

                var totalUsedToCalculate = $("#sub_total").val();
            }

            if (type == 'amount') {

                total_tax += parseFloat(value);
                $("#calculated_tax_value_" + i).text(value);
            } else {

                var tax_value = parseFloat(totalUsedToCalculate) * parseFloat(value) / 100;
                total_tax += parseFloat(tax_value);

                $("#calculated_tax_value_" + i).text(tax_value);
            }
        }
    }

    var total = parseFloat(total_after_discount) + parseFloat(total_tax);

    var additional_payments = calculateAdditionalPayments();

    total += parseFloat(additional_payments);

    $("#total").val(total.toFixed(2));

    $("#tax").val(total_tax.toFixed(2));
}

function calculateAdditionalPayments() {

    var total_after_discount = $("#total_after_discount").val();
    var additional_count = $("#invoice_additional_count").val();
    var total_additional = 0;
    var sub_total = $("#sub_total").val();

    if (sub_total == 0) {
        $("#additional_payments").val(0);
        $("#total_after_discount").val(0);
        $("#total").val(0);
        return false;
    }

    for (var i = 1; i <= additional_count; i++) {

        if ($('#checkbox_additional_' + i).is(':checked')) {

            var type = $('#checkbox_additional_' + i).data('additional-type');
            var value = $('#checkbox_additional_' + i).data('additional-value');

            let additional_execution_time = $('#checkbox_additional_' + i).data('additional-execution-time');

            if (additional_execution_time == 'after_discount') {

                var totalUsedToCalculate = $("#total_after_discount").val();

            } else {

                var totalUsedToCalculate = $("#sub_total").val();
            }


            if (type == 'amount') {

                total_additional += parseFloat(value);
                $("#calculated_additional_value_" + i).text(value);

            } else {

                var tax_value = parseFloat(totalUsedToCalculate) * parseFloat(value) / 100;
                total_additional += parseFloat(tax_value);

                $("#calculated_additional_value_" + i).text(tax_value);
            }
        }
    }

    $("#additional_payments").val(total_additional.toFixed(2));

    let additional_total = total_additional.toFixed(2);

    return additional_total;
}

function getPurchasePriceFromSegments(index) {

    let price_segment = $('#price_segments_part_' + index).find(":selected").val();

    if (price_segment.length == 0) {

        getPurchasePrice(index);
        return true;
    }

    let purchase_price = $('#price_segments_part_' + index).find(":selected").data('purchase-price');
    $('#price_' + index).val(purchase_price);
}

function executeAllItems() {

    let items_count = $('#items_count').val();

    for (let i = 1; i <= items_count; i++) {

        if ($('#price_' + i).length) {
            calculateItem(i);
        }
    }

    calculateTotal();
}

function selectPurchaseQuotation(index) {

    if ($('.purchase_quotation_box_' + index).is(':checked')) {

        $('.real_purchase_quotation_box_' + index).prop('checked', true);

    } else {

        $('.real_purchase_quotation_box_' + index).prop('checked', false);
    }
}

function selectSupplier () {

    let supplier_id = $('#supplier_id').find(":selected").val();
    let discount = $('#supplier_id').find(":selected").data('discount');
    let discount_type = $('#supplier_id').find(":selected").data('discount-type');

    if (discount_type == 'amount') {

        $('.supplier_discount_type').val('$');

    }else {
        $('.supplier_discount_type').val('%');
    }

    $('.supplier_discount_type_value').val(discount_type);
    $('.supplier_discount').val(discount);

    calculateTotal();
}

function supplierDiscount () {

    let total = $('#sub_total').val();

    let discount = $('#supplier_id').find(":selected").data('discount');
    let discount_type = $('#supplier_id').find(":selected").data('discount-type');

    if (discount == "") {
        discount = 0;
    }

    if (total == 0) {
        return 0;
    }

    if (discount_type == 'amount') {

        return discount

    }else {

        return  parseFloat(total) * parseFloat(discount) / parseFloat(100);
    }
}


