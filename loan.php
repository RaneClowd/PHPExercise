<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/DataModels/Homeroom.php';

$bookISBN = trim($_REQUEST["isbn"]);
$homeroomName = trim($_REQUEST["homeroom"]);
$studentId = trim($_REQUEST["studentId"]);

if (empty($homeroomName)) {
    respondWithRoomsForLoan($bookISBN);
} else {
    loanBook();
}

function respondWithRoomsForLoan($bookISBN) {
    global $studentId;
    $response = Homeroom::homeroomsWithBook($bookISBN);

    // TODO: check for length of results and error message

    foreach ($response->result as $homeroom) {
        echo "<button class='medButton' onclick=\"checkOutBookForStudent('$bookISBN', $studentId, '{$homeroom['Name']}')\" >{$homeroom['Name']}</button><br>";
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