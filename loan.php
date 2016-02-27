<?php
include 'DataStore.php';

$bookISBN = trim($_REQUEST["isbn"]);
$homeroomName = trim($_REQUEST["homeroom"]);
$studentId = trim($_REQUEST["studentId"]);

$dataStore = new DataStore();

if (empty($homeroomName)) {
    respondWithRoomsForLoan($bookISBN);
} else {
    loanBook();
}

function respondWithRoomsForLoan() {
    global $dataStore, $bookISBN, $studentId;

    $namesWithBook = $dataStore->homeroomNamesWithBook($bookISBN);

    // TODO: check for length of results and error message

    foreach ($namesWithBook as $homeroomName) {
        echo "<button class='medButton' onclick=\"checkOutBookForStudent('$bookISBN', $studentId, '$homeroomName')\" >$homeroomName</button><br>";
    }
}

function loanBook() {
    global $bookISBN, $homeroomName, $studentId, $dataStore;

    $dataStore = new DataStore();
    $errorMessage = $dataStore->checkoutBookWithISBNAndStudentFromHomeroom($bookISBN, $studentId, $homeroomName);

    if (!empty($errorMessage)) {
        echo "Error: " . $errorMessage;
    } else {
        echo "book checked out";
    }
}