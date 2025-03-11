//handler pro zmenu role
$(document).on('change', '.update-role', function () {
    // ziskani hodnot
    const newRoleId = $(this).val();
    const userId = $(this).data('user-id');

    console.log("meni se uzivatel:", userId, "na roli ID:", newRoleId);

    // AJAX pozadavek pro zmenu role
    $.ajax({
        url: window.location.href, // Endpoint pro změnu role
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            action: "update-role",
            id_uzivatel: userId,
            id_prava: newRoleId
        }), // data k odeslani
        success: function (response) {
            console.log(response);
            //povedlo se zmenit roli
            if (response.success) {
                // alert
                showAlert("primary", "Role uživatele byla úspěšně změněna!");
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