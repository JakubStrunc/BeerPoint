// hadler pro zmenu stavu
$(document).on('change', '.update-status', function () {
    // ziskani hodnot
    const newStatus = $(this).val();
    const orderId = $(this).data('order-id');

    console.log(orderId, "na", newStatus);

    // ajax poyadavek pro zmenu stavu
    $.ajax({
        url: window.location.href, // Endpoint pro změnu stavu
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            action: "update-status",
            id_objednavka: orderId,
            stav: newStatus
        }), // odeslana data
        success: function (response) {
            console.log("working");

            //povedlo se zmenit
            if (response.success) {
                // alert
                showAlert("primary", "Stav objednávky byl úspěšně změněn!");
            }
        },
        error: function (xhr, status, error) {
            //chyba
            console.error("Chyba:", error);
            console.error("Stav:", status);
            console.error("Odpověď:", xhr.responseText);
        }
    });
});