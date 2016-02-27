<html>

<head>
    <title>Library</title>
</head>

<script src="serviceScript.js"></script>


<body bgcolor="#000000" text="#ffffff">

<div id="demo"></div>

<?php
include 'DataStore.php';

$dataStore = new DataStore();

$homeroomNameParamKey = "homeroom";
$studentIdKey = "id";

if (isset($_GET[$studentIdKey])) {
    $studentId = trim($_GET[$studentIdKey]);
    $student = $dataStore->studentWithId($studentId);

    if ($student == null) {
        echo "Error: student not found";
    } else {
        displayBookContentForStudent($student);
    }
} else {
    echo "<form action=\"index.php\" method=get>";
    if (isset($_GET[$homeroomNameParamKey])) {
        $homeroomName = trim($_GET[$homeroomNameParamKey]);
        $homeroom = $dataStore->homeroomWithName($homeroomName);

        if ($homeroom == null) {
            echo "Error: homeroom not found";
        } else {
            displayStudentOptions($homeroom);
        }
    } else {
        displayHomeRoomOptions();
    }
    echo "</form>";
}



function displayBookContentForStudent($student) {
    if ($student->book == null) {
        global $dataStore;
        $booksWithAvailability = $dataStore->bookAvailability();

        echo "<table>";
        foreach ($booksWithAvailability as $bookAvailability) {
            displayListItemForBook($bookAvailability, $student);
        }
        echo "</table>";
    } else {
        echo "you have " . $student->book->title . " from the " . $student->book->homeroom->name . " room<br>";
        echo "<button onclick=\"returnBookForStudent($student->studentId)\" >Return</button>";
    }
}

function displayListItemForBook($bookAvailability, $student) {
    $book = $bookAvailability[0];
    $available = $bookAvailability[1];
    $studentsWithBook = $bookAvailability[2];

    echo "<tr>";
    echo "<td>" . $book->title . "</td>";

    if ($available) {
        echo "<td><button onclick=\"getBookAvailability('$book->ISBN', $student->studentId)\" >Borrow!</button></td>";
    } else {
        echo "<td>(all checked out)</td>";
    }

    echo "<td>";
    foreach ($studentsWithBook as $student) {
        echo $student->firstName . ",";
    }
    echo "</td>";

    echo "</tr>";
}

function displayStudentOptions($homeroom) {
    global $studentIdKey;
    foreach ($homeroom->students as $student) {
        echo "<button name=\"" . $studentIdKey ."\" value=\"" . $student->studentId . "\" type=\"submit\">$student->firstName $student->lastName</button><br>";
    }
}

function displayHomeRoomOptions() {
    global $dataStore, $homeroomNameParamKey;
    foreach ($dataStore->homeroomNames() as $homeroomName) {
        echo "<input type=\"submit\" name=\"" . $homeroomNameParamKey . "\" value=\"" . $homeroomName . "\">";
    }
}

?>

<hr>
<form action="allloans.php" method=get>
    <button>View all loans</button>
</form>

</body>
</html>