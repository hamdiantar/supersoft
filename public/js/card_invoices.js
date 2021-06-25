function setServiceValues(id, maintenance_id, maintenance_type_id) {

    let sold_qty = $('#sold-qy-'+id+'-'+maintenance_id);
    let selling_price = $('#selling-price-'+id+'-'+maintenance_id);
    let total = $("#total-"+id+'-'+maintenance_id);
    let discount_type = $("#discount-type-"+id+'-'+maintenance_id).val();
    let discount = $("#discount-"+id+'-'+maintenance_id).val();
    let total_after_discount = $('#total_after_discount');

    var item_total = (sold_qty.val() * selling_price.val()).toFixed(2);

    total.val(item_total);

    checkAmount(id, maintenance_id);

    implementDiscount(discount, discount_type,total.val(),id, maintenance_id);

    calculateTotalQuotation(maintenance_id, maintenance_type_id);

    // calculateTax();
}

function implementDiscount(discount, discount_type, total, id, maintenance_id) {

    if(discount == ""){
        discount = 0;
    }

    if($("#discount-type-amount-"+id+'-'+maintenance_id).is(':checked')){

        var big_amount_discount = $("#amount-discount-" + id + '_' + maintenance_id).val();

        if (parseFloat(discount) > parseFloat(big_amount_discount)) {

            swal({
                text: " sorry big amount discount is " + big_amount_discount,
                icon: "warning",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $("#discount-" + id + '-' + maintenance_id).val(0);
                }
            });
            discount = 0;
        }

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    }else{

        let big_percent_discount = $("#percent-discount-" + id + '_' + maintenance_id).val();

        if (parseFloat(discount) > parseFloat(big_percent_discount)) {

            swal({
                text: " sorry big percent discount is " + big_percent_discount,
                icon: "warning",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $("#discount-" + id + '-' + maintenance_id).val(0);
                }
            });
            discount = 0;
        }

        var discount_percentage_value = parseFloat(total) * parseFloat(discount)/parseFloat(100);

        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    if(price_after_discount <= 0)
        price_after_discount = 0;

    $("#total-after-discount-"+id+'-'+maintenance_id).val(price_after_discount.toFixed(2));
}

function checkAmount(id, maintenance_id) {

    let maximum_sale_amount = $("#maximum-sale-amount-" + id + '_' +maintenance_id).val();

    let sold_qty = $('#sold-qy-'+id+'-'+maintenance_id).val();

    if (parseInt(sold_qty) > parseInt(maximum_sale_amount)) {

        swal({
            text: "sorry maximum sale amount is " + maximum_sale_amount,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                // $("#discount-"+id).val(0);
            }
        });

        $('#sold-qy-'+id+'-'+maintenance_id).val(0);
    }
}

// Start packages calculations
function calculatePackageValues(id, maintenance_id, maintenance_type_id) {

    let price = $('#package-price-'+id+'-'+maintenance_id);
    let qty = $('#package-qty-'+id+'-'+maintenance_id);
    let discount_type = $("#package-discount-type-"+id+'-'+maintenance_id).val();
    let discount = $("#package-discount-"+id+'-'+maintenance_id).val();
    // let total_after_discount = $('#package-total_after_discount');
    let total = $('#package-total-before-discount-'+id+'-'+maintenance_id);

    var item_total = (qty.val() * price.val()).toFixed(2);

    total.val(item_total);

    implementPackageDiscount(discount, discount_type, item_total, id, maintenance_id);

    calculateTotalQuotation(maintenance_id, maintenance_type_id);
}

function implementPackageDiscount(discount, discount_type, total, id, maintenance_id) {

    if(discount == ""){
        discount = 0;
    }

    if($("#package-discount-type-amount-"+id+'-'+maintenance_id).is(':checked')){

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    }else{

        var discount_percentage_value = parseFloat(total) * parseFloat(discount)/parseFloat(100);

        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    if(price_after_discount <= 0)
        price_after_discount = 0;

    $("#package-total-after-discount-"+id+"-"+maintenance_id).val(price_after_discount.toFixed(2));
}
// End packages calculations

// Start Services Calculations
function calculateServiceValues(id, maintenance_id, maintenance_type_id) {

    let qty = $('#service-qty-'+id+'-'+maintenance_id);
    let price = $('#service-price-'+id+'-'+maintenance_id);
    let total = $("#service-total-"+id+'-'+maintenance_id);
    let discount_type = $("#service-discount-type-"+id+'-'+maintenance_id).val();
    let discount = $("#service-discount-"+id+'-'+maintenance_id).val();
    let total_after_discount = $('#service-total_after_discount');

    var item_total = (qty.val() * price.val()).toFixed(2);

    total.val(item_total);

    implementServiceDiscount(discount, discount_type,total.val(),id, maintenance_id);

    calculateTotalQuotation(maintenance_id, maintenance_type_id);
}

function implementServiceDiscount(discount, discount_type, total, id, maintenance_id) {

    if(discount == ""){
        discount = 0;
    }

    if($("#service-discount-type-amount-"+id+'-'+maintenance_id).is(':checked')){

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    }else{

        var discount_percentage_value = parseFloat(total) * parseFloat(discount)/parseFloat(100);

        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    if(price_after_discount <= 0)
        price_after_discount = 0;

    $("#service-total-after-discount-"+id+'-'+maintenance_id).val(price_after_discount.toFixed(2));
}
// End Services Calculations


// not work now
function resetItem(id) {
    $('#purchased-qy-'+id).val(0);
    $('#discount-'+id).val(0);
    $('#total-'+id).val(0);
    $('#total-after-discount-'+id).val(0);
}


//////////////////////  GET MAINTENANCE TYPE TOTAL ////////////////////////////
function implementInvoiceDiscount(maintenance_type_id) {

    var discount = $("#discount_"+maintenance_type_id+'_type').val();
    // var discount_type = $("#invoice_discount_type").val();
    var total = $("#total_before_discount_"+maintenance_type_id+'_type').val();


    if(discount == ""){
        discount = 0;
    }

    if ($('#discount_type_amount_'+maintenance_type_id+'_type').is(':checked')){
        // if(discount_type == 'amount'){

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    }else{

        var discount_percentage_value = parseFloat(total) * parseFloat(discount)/parseFloat(100);
        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    if(price_after_discount <= 0)
        price_after_discount = 0;

    $("#total_after_discount_"+maintenance_type_id+'_type').val(price_after_discount.toFixed(2));

    invoiceTotal();

    //will work after that
    // calculateTax(maintenance_type_id);
}

function calculateTotalQuotation(maintenance_id,maintenance_type_id) {

    var total = 0;

    total += calculatePartsTotal(maintenance_id);

    total += calculateServicesTotal(maintenance_id);

    total += calculatePackagesTotal(maintenance_id);

    $("#maintenance_"+maintenance_id+'_total_cost').val(total.toFixed(2));


    maintenanceTypeTotal(maintenance_type_id);

    implementInvoiceDiscount(maintenance_type_id);

    invoiceTotal();
}

function maintenanceTypeTotal(maintenance_type_id) {

    let maintenance_count = $("#type_"+maintenance_type_id+"_maintenance_count").val();

    let total = 0;

    for(let i=0; i< maintenance_count; i++){

        total += parseFloat($(".type_"+maintenance_type_id+'_maintenance_'+i).val());
    }

    $("#total_before_discount_"+maintenance_type_id+'_type').val(total.toFixed(2));
}

//////////////////////////////////////////////////////////////////////////////

//////////////////////////  GET TOTAL OF SERVICES,PACKAGES AND PARTS  //////////////////////////////
function calculatePartsTotal(maintenance_id) {

    var items_count = $("#parts_items_count_"+maintenance_id).val();

    var total = 0;

    for(var i=1; i<= items_count; i++){

        var part_id = $("#part-"+i+'-'+maintenance_id).val();

        var total_after_discount = $("#total-after-discount-"+part_id+'-'+maintenance_id).val();

        if(part_id && total_after_discount){

            total+= parseFloat($("#total-after-discount-"+part_id+'-'+maintenance_id).val());

        }else{
            $("#part-"+i+'-'+maintenance_id).remove();
        }
    }

    return total;

    // $("#total_before_discount").val(total.toFixed(2));

    // implementInvoiceDiscount();
}

function calculateServicesTotal(maintenance_id) {

    var items_count = $("#services_items_count_"+maintenance_id).val();

    var total = 0;

    for(var i=1; i<= items_count; i++){

        var service_id = $("#service-"+i+'-'+maintenance_id).val();

        var total_after_discount = $("#service-total-after-discount-"+service_id+'-'+maintenance_id).val();

        if(service_id && total_after_discount){

            total+= parseFloat($("#service-total-after-discount-"+service_id+'-'+maintenance_id).val());

        }else{
            $("#service-"+i+'-'+maintenance_id).remove();
        }
    }

    return total;
    // $("#total_before_discount").val(total.toFixed(2));


    // implementInvoiceDiscount();
}

function calculatePackagesTotal(maintenance_id) {

    var items_count = $("#packages_items_count_"+maintenance_id).val();

    var total = 0;

    for(var i=1; i<= items_count; i++){

        var package_id = $("#package-"+i+'-'+maintenance_id).val();

        var total_after_discount = $("#package-total-after-discount-"+package_id+'-'+maintenance_id).val();

        if(package_id && total_after_discount){

            total+= parseFloat($("#package-total-after-discount-"+package_id+'-'+maintenance_id).val());

        }else{
            $("#package-"+i+'-'+maintenance_id).remove();
        }
    }

    return total;
    // $("#total_before_discount").val(total.toFixed(2));

    // implementInvoiceDiscount();
}

function calculateWinchTotal () {

    return parseFloat($("#winch-total-after-discount").val());
}
//////////////////////////////////////////////////////////////////////////////////////


function partsItemsCount() {
    var parts_count = $("#parts_count").val();
    $("#number_of_items").val(parts_count);
}

/////////////////// TOTAL INVOICE ///////////////////////
function invoiceTotal() {

   let maintenance_types_count = $("#maintenance_types_count").val();

    let total = 0;

    for(let i=0; i< maintenance_types_count; i++){

        total += parseFloat($(".maintenance_type_number_"+i).val());
    }

    if($("#active_winch").is(':checked')) {
        total += calculateWinchTotal();
    }

    if(total <= 0){
        total = 0;
    }

    $("#total_before_discount").val(total);

    invoiceDiscount();
}

function invoiceDiscount() {

    var discount = $("#discount").val();
    // var discount_type = $("#invoice_discount_type").val();
    var total = $("#total_before_discount").val();
    let customer_discount = 0;


    if(discount == ""){
        discount = 0;
    }

    if ($('#discount_type_amount').is(':checked')){

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    }else{

        var discount_percentage_value = parseFloat(total) * parseFloat(discount)/parseFloat(100);
        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    if($("#checkbox_customer_discount").is(':checked')){
         customer_discount = customerDiscount();
    }

    price_after_discount -= parseFloat(customer_discount);

    var points_discount = $("#points_discount").val();

    price_after_discount -= parseFloat(points_discount);

    if(price_after_discount <= 0)
        price_after_discount = 0;

    $("#total_after_discount").val(price_after_discount.toFixed(2));

    calculateTax();
}

function customerDiscount() {

    var discount = $("#customer_discount").val();
    var customer_discount_type = $("#customer_discount_type").val();
    var total = $("#total_before_discount").val();

    if(discount == ""){
        discount = 0;
    }

    if (customer_discount_type == 'amount'){

        return discount;

    }else{

        var discount = parseFloat(total) * parseFloat(discount)/parseFloat(100);
        return discount;
    }

}

function calculateTax(){

    var total_after_discount = $("#total_after_discount").val();
    var tax_count = $("#tax_count").val();
    var total_tax = 0;
    var total_before_discount = $("#total_before_discount").val();

    if(total_before_discount == 0){
        $("#invoice_tax").val(0);
        $("#total_after_discount").val(0);
        $("#final_total").val(0);
        return false;
    }

    for(var i=1; i<=tax_count; i++){

        var type = $("#tax_type_"+i).val();
        var value = $("#tax_value_"+i).val();

        if(type == 'amount'){

            total_tax += parseFloat(value);
            $("#tax_value_after_discount_"+i).val(value);
        }else{

            var tax_value = parseFloat(total_after_discount) * parseFloat(value)/100;
            total_tax += parseFloat(tax_value);
            $("#tax_value_after_discount_"+i).val(tax_value);
        }
    }

    var total = parseFloat(total_after_discount) + parseFloat(total_tax);

    $("#final_total").val(total.toFixed(2));

    $("#invoice_tax").val(total_tax.toFixed(2));
}


/////////////////// END TOTAL INVOICE ///////////////////////

function selectPointsRule () {

    let rule_amount = $("#point_rule_id").find(':selected').data("amount");

    $("#points_discount").val(rule_amount);

    let rule_id = $("#point_rule_id").find(':selected').val();

    $(".rule_id").val(rule_id);

    invoiceDiscount();
}



