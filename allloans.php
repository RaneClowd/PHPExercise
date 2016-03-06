<html>
<head>
    <title>Loan Data</title>
    <link rel="stylesheet" type="text/css" href="Resources/style.css">
</head>

<body>

<?php

$dataStore = new DataStore();

echo "<table border='1' width='100%'>";
echo "<th>Date</th>";
echo "<th>Student</th>";
echo "<th>Book Title</th>";
echo "<th>Homeroom</th>";
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
    echo "<td>$student->FirstName $student->LastName</td>";
    echo "<td>" . $student->book->title . "</td>";
    echo "<td>" . $student->book->homeroom->name . "</td>";
    echo "</tr>";
}

?>

</body>
</html>