// function calculateServiceValues(id) {
//
//     let qty = $('#service-qty-'+id);
//     let price = $('#service-price-'+id);
//     let total = $("#service-total-"+id);
//     let discount_type = $("#service-discount-type-"+id).val();
//     let discount = $("#service-discount-"+id).val();
//     let total_after_discount = $('#service-total_after_discount');
//
//     var item_total = (qty.val() * price.val()).toFixed(2);
//
//     total.val(item_total);
//
//     implementServiceDiscount(discount, discount_type,total.val(),id);
// }
//
// function implementServiceDiscount(discount, discount_type, total, id) {
//
//     if(discount == ""){
//         discount = 0;
//     }
//
//     if(discount_type == 'amount'){
//
//         var price_after_discount = parseFloat(total) - parseFloat(discount);
//
//     }else{
//
//         var discount_percentage_value = parseFloat(total) * parseFloat(discount)/parseFloat(100);
//
//         var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
//     }
//
//     if(price_after_discount <= 0)
//         price_after_discount = 0;
//
//     $("#service-total-after-discount-"+id).val(price_after_discount.toFixed(2));
// }

// Search in services types
function searchInServicesTypes() {

    var value = $("#searchInServicesType").val().toLowerCase();
    $(".searchResultServicesTypes li a").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}

// Search in services
function searchInServices() {
    var value = $("#searchInServicesData").val().toLowerCase();
    $("#services_data li a").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}


