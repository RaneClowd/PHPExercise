<html>
<head>
    <title>Sorry</title>
</head>

<body bgcolor="#000000" text="#ffffff">

<?php
include 'DataStore.php';

$dataStore = new DataStore();

echo "<table>";

foreach ($dataStore->homerooms as $homeroom) {
    foreach ($homeroom->students as $student) {
        if (!empty($student->book)) {
            displayLoanInformationForStudent($student);
        }
    }
}

echo "</table>";

function displayLoanInformationForStudent($student) {
    echo "<tr>";
    echo "<td>" . date("m/d/Y H:i", $student->loanTimestamp) . "</td>";
    echo "<td>$student->firstName $student->lastName</td>";
    echo "<td>" . $student->book->title . " from " . $student->book->homeroom->name . "</td>";
    echo "</tr>";
}

?>

</body>
</html>