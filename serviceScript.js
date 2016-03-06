
function getBookAvailability(bookISBN, studentId) {
    $.ajax({url: "loanController.php?isbn=" + bookISBN + "&studentId=" + studentId, success: function(result){
        var response = jQuery.parseJSON(result);
        if ( !response.errorMsg) {
            showPopup("Select room to borrow from:", "", response.result);
        } else {
            showPopup("Error:", "", response.errorMsg);
            // TODO: show ok button on errors
        }
    }});
}

function checkOutBookForStudent(bookISBN, studentId, homeroomName) {
    showPopup("Checking out book...", "", "");

    $.ajax({url: "loanController.php?isbn=" + bookISBN + "&studentId=" + studentId + "&homeroom=" + homeroomName, success: function(result){
        var response = jQuery.parseJSON(result);

        if ( !response.errorMsg) {
            showPopup("The book is yours!", "", "");
            setTimeout(refresh, 2000);
        } else {
            showPopup(response.errorMsg, "", "");
            // TODO: show ok button on errors
        }
    }});
}

function returnBookForStudent(studentId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            showPopup("Return complete with status:", "", xmlhttp.responseText);

            setTimeout(refresh, 2000);
        }
    };
    xmlhttp.open("GET", "return.php?studentId=" + studentId, true);
    xmlhttp.send();
}

function refresh() {
    location.reload(true);
}

function showPopup(title, message, content) {
    $("#popup").show();
    $("#popupTitle").text(title);
    $("#popupMessage").text(message);
    $("#popupContent").html(content);
}

function hidePopup() {
    $("#popup").hide();
}