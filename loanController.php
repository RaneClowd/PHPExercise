<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/DataModels/Homeroom.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Common/Response.php';

$bookISBN = trim($_REQUEST["isbn"]);
$homeroomName = trim($_REQUEST["homeroom"]);
$studentId = trim($_REQUEST["studentId"]);

if (empty($homeroomName)) {
    echo responseWithRoomsForLoan($bookISBN);
} else {
    echo responseForLoaningBook();
}

function responseWithRoomsForLoan($bookISBN) {
    global $studentId;
    $response = Homeroom::homeroomsWithBook($bookISBN);

    if ( !empty($response->errorMsg)) {
        $response->errorMsg = "There was a problem retrieving the rooms from which to borrow. Please try again.";
    }

    if ( !empty($response->result)) {
        $resultArray = array();
        foreach ($response->result as $homeroom) {
            array_push($resultArray, "<button class='medButton' onclick=\"checkOutBookForStudent('$bookISBN', $studentId, '{$homeroom['Name']}')\" >{$homeroom['Name']}</button><br>");
            // TODO: this should just give the data and send it to the view, not tell the view how to display it
        }
        $response->result = $resultArray;
    }

    return json_encode($response);
}

function responseForLoaningBook() {
    global $bookISBN, $homeroomName, $studentId;

    $homeroom = new Homeroom($homeroomName);
    $response = $homeroom->checkOutIsbnToStudent($bookISBN, $studentId);

    if ( !empty($response->errorMsg)) {
        $response->errorMsg = "There was a problem getting the book. Please try again.";
    }

    return json_encode($response);
}