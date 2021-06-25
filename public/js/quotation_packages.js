// function calculatePackageValues(id) {
//
//     let price = $('#package-price-'+id);
//     let discount_type = $("#package-discount-type-"+id).val();
//     let discount = $("#package-discount-"+id).val();
//     let total_after_discount = $('#package-total_after_discount');
//
//     implementPackageDiscount(discount, discount_type, price.val(), id);
// }
//
// function implementPackageDiscount(discount, discount_type, total, id) {
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
//     $("#package-total-after-discount-"+id).val(price_after_discount.toFixed(2));
// }

// Search in parts Types


function searchInPackages(){

    var value = $("#searchInPackagesData").val().toLowerCase();
    $("#packages_data li a").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}

