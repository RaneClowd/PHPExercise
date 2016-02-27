
function getBookAvailability(bookISBN, studentId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("demo").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", "loan.php?isbn=" + bookISBN + "&studentId=" + studentId, true);
    xmlhttp.send();
}

function checkOutBookForStudent(bookISBN, studentId, homeroomName) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("demo").innerHTML = xmlhttp.responseText;

            setTimeout(refresh, 2000);
        }
    };
    xmlhttp.open("GET", "loan.php?isbn=" + bookISBN + "&studentId=" + studentId + "&homeroom=" + homeroomName, true);
    xmlhttp.send();
}

function returnBookForStudent(studentId) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("demo").innerHTML = xmlhttp.responseText;

            setTimeout(refresh, 2000);
        }
    };
    xmlhttp.open("GET", "return.php?studentId=" + studentId, true);
    xmlhttp.send();
}

function refresh() {
    location.reload(true);
}