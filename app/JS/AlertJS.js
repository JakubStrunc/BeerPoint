/**
 * vytvori alert
 * @param type typ alertu
 * @param message zprava v alertu
 */
function showAlert(type, message) {
    // html kod pro alert
    const alertHtml = `
        <div id="alert" class="alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 mt-5 me-3" role="alert" style="z-index: 1055; display: none">
          
          ${message} 
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    console.log("Alert")

    // pridni alertu na stranku
    const alertElement = $(alertHtml);
    $("body").append(alertElement);
    $("#alert").show()

    // schovani po 1,5 sekunde
    setTimeout(function () {
        $("#alert").hide();
        let produkt = document.getElementById('alert');
        produkt.remove();
    }, 1500);

}

// handlovani alerty na jine strance
$(document).ready(function() {
    // existuje neco v
    if (sessionStorage.getItem("showAlert") === "true") {
        // zobraz alert sessionStorage
        console.log("jsem zde")
        showAlert(sessionStorage.getItem("alertType"), sessionStorage.getItem("alertMessage"));

        // vymaz hodnotu z sessionStorage
        sessionStorage.removeItem("showAlert");
        sessionStorage.removeItem("alertType");
        sessionStorage.removeItem("alertMessage");
    }
});
