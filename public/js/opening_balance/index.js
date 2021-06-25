

function removeItem(index) {

    swal({

        title: "Delete Item",
        text: "Are you sure want to delete this item ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,

    }).then((willDelete) => {

        if (willDelete) {

            $('#tr_part_' + index).remove();
            calculateTotal();
            reorderItems();
        }
    });

}

function calculateItem(index) {

    let quantity = $('#quantity_' + index).val();

    let price = $('#price_' + index).val();

    let total = quantity * price;

    $("#total_" + index).val(total.toFixed(2));

    calculateTotal();
}

function calculateTotal() {

    let items_count = $("#items_count").val();

    let total_price = 0;
    let total_quantity = 0;

    for (let i = 0; i <= items_count; i++) {

        if ($("#total_" + i).val()) {

            total_price = parseFloat($('#total_' + i).val()) + parseFloat(total_price);
            total_quantity = parseFloat($('#quantity_' + i).val()) + parseFloat(total_quantity);
        }
    }

    $("#total_price").val(total_price.toFixed(2));
    $("#total_quantity").val(total_quantity.toFixed(2));
}

function getPurchasePrice(index) {

    let price_segment = $('#price_segments_part_' + index).find(":selected").val();

    let purchasePrice = $('#price_segments_part_' + index).find(":selected").data('purchase-price');


    console.log(purchasePrice);

    if (price_segment.length == 0) {

        $("#price_" + index).val($('#prices_part_' + index).find(":selected").data('purchase-price'));
        return true;
    }

    $("#price_" + index).val(purchasePrice);
}

// function partQuantityInStore(index) {
//
//     let partQuantityInStore = $('#store_part_' + index).find(":selected").data('quantity');
//     $("#max_quantity_part_" + index).val(partQuantityInStore);
// }
//
// function checkPartQuantity(index) {
//
//     let settlementTypeType = '';
//
//     if ($("#positive").is(':checked')) {
//
//         settlementTypeType = 'positive';
//     } else {
//
//         settlementTypeType = 'negative';
//     }
//
//     if (settlementTypeType == 'positive') {
//
//         return false;
//     }
//
//     let max_quantity = $("#max_quantity_part_" + index).val();
//
//     let unit_quantity = $('#unit_quantity_' + index).val();
//
//     let quantity = parseFloat($('#quantity_' + index).val()) * parseFloat(unit_quantity);
//
//     if (quantity > max_quantity) {
//
//         swal({
//
//             title: "{{__('Max Quantity')}}",
//             text: "{{__('Quantity is more than available')}}",
//             icon: "warning",
//             buttons: true,
//             dangerMode: true,
//
//         }).then((willDelete) => {
//
//             $('#quantity_' + index).val(0);
//             calculateItem(index);
//         });
//
//     }
// }

function reorderItems() {

    let items_count = $('#items_count').val();

    let index = 1;

    for (let i = 1; i <= items_count; i++) {

        if ($('#price_' + i).length) {
            $('#item_number_' + i).text(index);

        }else {
            continue;
        }

        index++;
    }
}

function checkBranchValidation() {

    let branch_id = $('#branch_id').find(":selected").val();

    let isSuperAdmin = '{{authIsSuperAdmin()}}';

    if (!isSuperAdmin) {
        return true;
    }

    if (branch_id) {
        return true;
    }

    return false;
}
