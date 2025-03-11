// handler pro pridani noveho produktu
let response;
response.errors.nazev = undefined;
response.errors.popis = undefined;
response.errors.cena = undefined;
response.errors.mnozstvi = undefined;
response.errors.fileToUpload = undefined;

$(document).on('click', '#pridatProdukt', function(event) {
    console.log("Starting product addition...");
    event.preventDefault();

    // vymazani chybovych zprav
    $('.text-danger').remove();

    //aktualni stranka
    const page = window.location.href;

    // data y formu
    const formData = new FormData();
    formData.append('action', 'add-product');
    formData.append('nazev', $('#productName').val());
    formData.append('popis', $('#productDescription').val());
    formData.append('cena', $('#productPrice').val());
    formData.append('mnozstvi', $('#productStock').val());
    formData.append('fileToUpload', $('#fileToUpload')[0].files[0]);

    // ajax pozadavek k pridani noveho produktu
    $.ajax({
        url: page,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response);
            //povedlo se pridani
            if (response.success) {
                //ulozime alert
                sessionStorage.setItem("showAlert", "true");
                sessionStorage.setItem("alertType", "success"); // Type of alert (success, warning, danger)
                sessionStorage.setItem("alertMessage", "Produkt byl úspěšně přidán!"); // Alert message
                window.location.reload(); // Reload after success
            } else {
                // zobrazime chybove zpravys
                if (response.errors && response.errors.nazev) {
                    $('#productName').after('<div class="text-danger">' + response.errors.nazev + '</div>');
                }
                if (response.errors && response.errors.popis) {
                    $('#productDescription').after('<div class="text-danger">' + response.errors.popis + '</div>');
                }
                if (response.errors && response.errors.cena) {
                    $('#productPrice').after('<div class="text-danger">' + response.errors.cena + '</div>');
                }
                if (response.errors && response.errors.mnozstvi) {
                    $('#productStock').after('<div class="text-danger">' + response.errors.mnozstvi + '</div>');
                }
                if (response.errors && response.errors.fileToUpload) {
                    $('#fileToUpload').after('<div class="text-danger">' + response.errors.fileToUpload + '</div>');
                }
            }
        },
        error: function(xhr, status, error) {
            //chyba
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Response Text:", xhr.responseText);
        }
    });
});

// handler pro smazani produktu
$(document).on('click', '.delete-product-btn', function(event) {
    console.log("Starting product deletion...");
    event.preventDefault();

    // potvrzeni
    if (!confirm("Opravdu chcete smazat tento produkt?")) {
        return;
    }


    // data
    const produktId = $(this).data('product-id'); // ID produktu
    console.log("Product ID to delete:", produktId);

    // odstanit html kod
    let produkt = document.getElementById('sprava_produktu_tab_tr_' + produktId.toString());
    if (produkt) {
        produkt.remove();
    }

    const page = window.location.href


    // AJAX požadavek pro odstraneni produktu
    $.ajax({
        url: page,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            action: 'delete-product',
            id: produktId
        }), // data k odeslani
        success: function(response) {
            console.log(response);
            //povedlo se produkt odstranit
            if (response.success) {
                //alert
                showAlert("success", "Produkt byl úspěšně odstraněn z košíku.");
            } else {
                showAlert("error", response.error || "Nastala chyba při odstraňování produktu.");
            }
        },
        error: function(xhr, status, error) {
            //chyba
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Response Text:", xhr.responseText);
            showAlert("danger", "Chyba při komunikaci se serverem.");
        }
    });
});

// handler pro editaci produktu
$(document).on('click', '#edit-product-btn', function(event) {
    event.preventDefault();
    console.log("ahoj")

    //data
    const productId = $(this).data('product-id');
    const page = window.location.href

    //ajax pozadavek k ziskani produktu
    $.ajax({
        url: page,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            action: 'get-product',
            id_produkt: productId
        }), //data k odeslani
        success: function(response) {
            console.log(response);
            if (response.success) {
                // nacteni dat
                $('#editproductName').val(response.data.nazev);
                $('#editproductDescription').val(response.data.popis);
                $('#editproductPrice').val(response.data.cena);
                $('#editproductStock').val(response.data.mnozstvi);
                $('#fileToUpload').val(''); // Vyprázdnění file inputu

                $('#saveProductChanges').attr('data-product-id', productId);
                // zobrazeni modalniho okna
                $('#editProductModal').modal('show');
            } else {
                alert(response.message || 'Nepodařilo se načíst informace o produktu.');
            }
        },
        error: function(xhr, status, error) {
            //chyba
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Response Text:", xhr.responseText);
        }
    });
});

// handler pro ulozeni zmen produktu
$(document).on('click', '#saveProductChanges', function(event) {
    console.log("Starting product addition...");
    event.preventDefault();

    // vymazani chybovych zprav
    $('.text-danger').remove();


    const page = window.location.href;

    // formdata k odeslani
    const formData = new FormData();
    formData.append('action', 'update-product');
    formData.append('nazev', $('#editproductName').val());
    formData.append('popis', $('#editproductDescription').val());
    formData.append('cena', $('#editproductPrice').val());
    formData.append('mnozstvi', $('#editproductStock').val());
    formData.append('fileToUpload', $('#editfileToUpload')[0].files[0]);
    formData.append('product_id', $(this).data('product-id'));

    // ajax pozadavek k uprave produktu
    $.ajax({
        url: page,
        type: 'POST',
        data: formData, // data k odeslani
        processData: false,
        contentType: false,
        success: function(response) {
            console.log("Response received:", response);
            //povedlo se ulozit zmeny
            if (response.success) {
                //ulozime alert
                sessionStorage.setItem("showAlert", "true");
                sessionStorage.setItem("alertType", "success"); // Type of alert (success, warning, danger)
                sessionStorage.setItem("alertMessage", "Produkt byl úspěšně upraven!"); // Alert message
                window.location.reload(); // Reload after success
            } else {
                // zobrazime chybove zpravy
                if (response.errors && response.errors.nazev) {
                    $('#editproductName').after('<div class="text-danger">' + response.errors.nazev + '</div>');
                }
                if (response.errors && response.errors.popis) {
                    $('#editproductDescription').after('<div class="text-danger">' + response.errors.popis + '</div>');
                }
                if (response.errors && response.errors.cena) {
                    $('#editproductPrice').after('<div class="text-danger">' + response.errors.cena + '</div>');
                }
                if (response.errors && response.errors.mnozstvi) {
                    $('#editproductStock').after('<div class="text-danger">' + response.errors.mnozstvi + '</div>');
                }
                if (response.errors && response.errors.fileToUpload) {
                    $('#editfileToUpload').after('<div class="text-danger">' + response.errors.fileToUpload + '</div>');
                }
            }
        },
        error: function(xhr, status, error) {
            //chyba
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Response Text:", xhr.responseText);
        }
    });
});