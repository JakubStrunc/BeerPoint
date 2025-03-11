// handler pridavani do kosiku
$(document).on('click', '#pridat', function(event) {
    //console.log("ahoj");
    event.preventDefault();

    //id z eventu
    const produktId = $(this).data('product-id');
    //console.log(produktId)

    // ajax pozadavek pro pridani produktu
    $.ajax({
        url: "http://localhost/index.php?page=Home",
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({action: 'add-product', id: produktId}), // data k odeslani
        success: function (response) {
            console.log(response);
            // povedlo se pridani
            if (response.success) {
                //alert
                showAlert("success", "Produkt byl úspěšně přidán do košíku!");
                console.log("uspech");

            } else {
                //alert
                showAlert("warning", "Produkt již byl přidán do košíku.");
            }
        },
        error: function (xhr, status, error) {
            // chyby
            console.log("Status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
        }
    });
});

// handler pro prihlaseni
$(document).on('click', '#prihlasit', function(event) {
    //console.log("ahoj");
    event.preventDefault();

    // vymazani chybovych zprav
    $('.text-danger').remove();

    // ajax pozadavek pro prihlaseni uzivatele
    $.ajax({
        url: "http://localhost/index.php?page=Home",
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            action: 'login',
            email: $('#signinEmailInput').val(),
            heslo: $('#signinPassInput').val()}), // data k odeslani
        success: function (response) {
            console.log("ahoj");
            console.log(response);

            // povedlo se prihlasit
            if (response.success) {

                //ulozime alert na domovskou stranku
                sessionStorage.setItem("showAlert", "true");
                sessionStorage.setItem("alertType", "success");
                sessionStorage.setItem("alertMessage", "Přihlášení bylo úspěšné!");
                window.location.href = "http://localhost/index.php?page=home";

            } else {
                // zobrazime chybove zpravy
                if (response.error && response.error.email) {
                    $('#signinEmailInput').after('<div class="text-danger">' + response.error.email + '</div>');
                }
                if (response.error && response.error.heslo) {
                    $('#signinPassInput').after('<div class="text-danger">' + response.error.heslo + '</div>');
                }
            }
        },
        error: function (xhr, status, error) {
            // chyby
            console.log("Status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
        }
    });
});


// handler pro registraci
$(document).on('click', '#registerButton', function(event) {
    console.log("Starting registration...");
    event.preventDefault();

    // vymazani chybovych zprav
    $('.text-danger').remove();

    // ajax pozadavek pro registraci uzivatele uzivatele
    $.ajax({
        url: "http://localhost/index.php?page=Home",
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            action: 'register',
            uzivatelske_jmeno: $('#signupFullnameInput').val(),
            email: $('#signupEmailInput').val(),
            heslo: $('#signupPasswordInput').val(),
            heslo2: $('#signupConfirmPasswordInput').val()
        }),// data k odeslani
        success: function(response) {
            console.log("Response received:", response);
            //registrace se povedla
            if (response.success) {
                //ulozime alert na domovskou stranku
                sessionStorage.setItem("showAlert", "true");
                sessionStorage.setItem("alertType", "success"); // Alert type: success
                sessionStorage.setItem("alertMessage", "Registrace byla úspěšná!"); // Success message
                window.location.href = "http://localhost/index.php?index.php?page=home"; // Redirect on success
            } else {
                // zobrazime chybove zpravy
                if (response.error && response.error.uzivatelske_jmeno) {
                    $('#signupFullnameInput').after('<div class="text-danger">' + response.error.uzivatelske_jmeno + '</div>');
                }
                if (response.error && response.error.email) {
                    $('#signupEmailInput').after('<div class="text-danger">' + response.error.email + '</div>');
                }
                if (response.error && response.error.heslo) {
                    $('#signupPasswordInput').after('<div class="text-danger">' + response.error.heslo + '</div>');
                }
                if (response.error && response.error.heslo2) {
                    $('#signupConfirmPasswordInput').after('<div class="text-danger">' + response.error.heslo2 + '</div>');
                }
            }
        },
        error: function(xhr, status, error) {
            // chyby
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Response Text:", xhr.responseText);
        }
    });
});



