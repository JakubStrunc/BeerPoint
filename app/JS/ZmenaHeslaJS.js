//hadler pro zmenu hesla
$(document).on('click', '#btn_ulozit', function(event) {
    console.log("zmena");
    //console.log(window.location.href);
    event.preventDefault();
    // vymazani chybovych zprav
    $('.text-danger').remove();
    const page = window.location.href

    // ajax pozadavek k zmene hesla
    $.ajax({
        url: page,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            action: 'change-password',
            oldPassword: $('#formPassword').val(),
            confirmOldPassword: $('#formConfirmPassword').val(),
            newPassword: $('#formNewPassword').val(),
            confirmNewPassword: $('#formConfirmNewPassword').val()
        }), //data k odeslani
        success: function(response) {
            console.log("Response received:", response);
            //pokud zmena probehla uspesne
            if (response.success) {
                //alert na dalsi strance
                sessionStorage.setItem("showAlert", "true");
                sessionStorage.setItem("alertType", "success");
                sessionStorage.setItem("alertMessage", "Heslo bylo úspěšně změněno!");
                window.location.href = page;
            } else {
                // zobrazime chybove zpravy
                if (response.errors && response.errors.oldPassword) {
                    $('#formPassword').after('<div class="text-danger">' + response.errors.oldPassword + '</div>');
                }
                if (response.errors && response.errors.confirmOldPassword) {
                    $('#formConfirmPassword').after('<div class="text-danger">' + response.errors.confirmOldPassword + '</div>');
                }
                if (response.errors && response.errors.newPassword) {
                    $('#formNewPassword').after('<div class="text-danger">' + response.errors.newPassword + '</div>');
                }
                if (response.errors && response.errors.confirmNewPassword) {
                    $('#formConfirmNewPassword').after('<div class="text-danger">' + response.errors.confirmNewPassword + '</div>');
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