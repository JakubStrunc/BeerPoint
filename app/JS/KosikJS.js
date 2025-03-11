// handler smazani produktu
$(document).on('click', '#smazat', function(event) {
    console.log("ahoj");
    event.preventDefault();

    //id z eventu
    const produktId = $(this).data('product-id');
    console.log(produktId)

    //vymazani zobrazeni produktu
    let produkt = document.getElementById('product' + produktId.toString());
    produkt.remove();

    // prazdny kosik
    if ($('.card.rounded-3').length === 0) {
        console.log("Košík je prázdný");

        // skryti
        $(".flex-grow-1").hide();

        // zobrazeni hlasky
        const emptyMessageHtml = `
            <div id="error_kosik" class="d-flex justify-content-center align-items-center flex-column" style="height: 100%;">
                <h3>HMM...</h3>
                <p>Vypadá to, že košík je prázdný.</p>
            </div>
        `;
        $(".card.shadow-sm").html(emptyMessageHtml);
    }



    // ajax pozadavek pro odebrani produktu
    $.ajax({
        url: window.location.href,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({action: 'delete-product', id: produktId}), // data k odeslani
        success: function (response) {
            console.log(response);
            // povedlo se odebrani
            if (response.success) {
                //alert
                showAlert("success", "Produkt byl úspěšně odstraněn z košíku.");
            }
        },
        error: function (xhr, status, error) {
            //chyba
            console.log("Status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
        }
    });
});

// handler pro zmenu mnozstvi produktu
$(document).on('click', '.btn-minus, .btn-plus', function(event) {
    event.preventDefault();
    console.log("work");

    // nejblizi element pro kontrolu mnozstvi
    const control = $(this).closest('.quantity-control');
    const productId = control.find('input[type="hidden"]').val(); // id produktu
    // ziskame mnozstvi
    const quantityInput = control.find('input[type="number"]');
    let currentQuantity = parseInt(quantityInput.val());
    console.log(productId);

    // zjistime zda uzivatel klikl na plus nebo minus
    if ($(this).hasClass('btn-minus') && currentQuantity > 0) {
        currentQuantity--; // snizime mnozstvi
    } else if ($(this).hasClass('btn-plus')) {
        currentQuantity++; // zvysime mnozstvi
    } else {
        return; // neplatne
    }

    // aktualizuj inputove pole
    quantityInput.val(currentQuantity);

    // ajax pozadavek o zmene mnozstvi
    $.ajax({
        url: window.location.href,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            action: 'update-quantity',
            id_produkt: productId,
            quantity: currentQuantity
        }), // data k odeslani
        success: function(response) {
            console.log(response);
            if (response.success) {
                // aktualizuj cenu produktu
                $(`#productTotal${productId} h5`).text(`${response.productTotal} Kč`);

                // aktualizuj total cenu
                $('#orderTotal').text(`${response.orderTotal} Kč`);
            } else {
                console.error("Server error: ", response.error);
                alert("Nepodařilo se aktualizovat množství.");
            }
        },
        error: function(xhr, status, error) {
            //chyba
            console.log("Status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
        }
    });
});
// hadler pro zmenu mnozstvi pres textove pole
$(document).on('change', 'input[type="number"]', function() {
    console.log("ahoj")
    // nejblizi element pro kontrolu mnozstvi
    const control = $(this).closest('.quantity-control');
    const productId = control.find('input[type="hidden"]').val();
    const currentQuantity = parseInt($(this).val());

    // zkontrolovat input
    if (currentQuantity < 0) {
        alert("Množství nemůže být záporné.");
        $(this).val(0);
        return;
    }

    // ajax pozadavek na zmenu hesla
    $.ajax({
        url: window.location.href,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            action: 'update-quantity',
            id_produkt: productId,
            quantity: currentQuantity
        }), // data k odeslani
        success: function(response) {
            response.productTotal = undefined;
            response.orderTotal = undefined;
            console.log(response);
            //povedlo se
            if (response.success) {
                // aktualizuj mnozstvi
                $(`#productTotal${productId} h5`).text(`${response.productTotal} Kč`);

                // aktualizuj total cenu
                $('#orderTotal').text(`${response.orderTotal} Kč`);
            } else {
                console.error("Server error: ", response.error);
                alert("Nepodařilo se aktualizovat množství.");
            }
        },
        error: function(xhr, status, error) {
            //chyba
            console.log("Status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
        }
    });
});


$(document).on('click', '#odeslat', function(event) {
    console.log("ahoj");
    event.preventDefault();
    const kosik = $(this).data('kosik-id');



    $.ajax({
        url: window.location.href,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({action: 'send-order',
            kosik_id: kosik,
            stav: "Zpracovává se",
            jmeno: $('#firstName').val(),
            primeni: $('#lastName').val(),
            ulice: $('#ulice').val(),
            cislo_popisne: $('#cislopopisne').val(),
            orientacni_cislo: $('#orijentacnicislo').val(),
            psc: $('#psc').val(),
            mesto: $('#mesto').val(),
            telefonni_cislo: $('#phone').val()}),
        success: function (response) {
            console.log(response);
            if (response.success) {
                sessionStorage.setItem("showAlert", "true");
                sessionStorage.setItem("alertType", "success"); // Typ alertu (success, warning, danger)
                sessionStorage.setItem("alertMessage", "Objednávka byla úspěšně zpracována!"); // Zpráva alertu
                window.location.href = "http://localhost/index.php?page=home";
            }
        },
        error: function (xhr, status, error) {

            console.log("Status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
        }
    });
});