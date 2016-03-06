<?php

$studentId = trim($_REQUEST["studentId"]);

$dataStore = new DataStore();
$errorMessage = $dataStore->returnBookForStudentWithID($studentId);

if (!empty($errorMessage)) {
    echo "Error: " . $errorMessage;
} else {
    echo "book returned";
}