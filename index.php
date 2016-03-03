<html>

<head>
    <title>Library</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="serviceScript.js"></script>

    <link rel="stylesheet" type="text/css" href="Resources/style.css">
</head>


<body>

<div id="popup">
    <div id="mask">
    </div>
    <div id="overlay" class="centered">
        <h3 id="popupTitle"></h3>
        <span id="popupMessage"></span>
        <div class="centered" id="popupContent"></div>
    </div>
</div>

<?php
include 'DataStore.php';
include_once 'ViewControllers/HomeroomViewController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dataStore = new DataStore();

$studentIdKey = "id";


echo "<div class='content centered'>";
if (isset($_GET[$studentIdKey])) {

    $studentId = trim($_GET[$studentIdKey]);
    $student = $dataStore->studentWithId($studentId);

    echo "<div class='header'>";
    echo "<h1>Hi $student->firstName!</h1>";
    echo "</div>";

    if ($student == null) {
        echo "Error: student not found";
    } else {
        displayBookContentForStudent($student);
    }
} else {

    echo "<div class='header'>";
    echo "<h1>Let's get to your books</h1>";
    echo "</div>";

    echo "<form action=\"index.php\" method=get>";
    if (isset($_REQUEST[HomeroomViewController::$parameterKey])) {
        echo "<h2>What is your name?</h2>";

        HomeroomViewController::displayStudentOptions();
    } else {
        echo "<h2>What is your homeroom?</h2>";

        HomeroomViewController::displayHomeRoomNames();
    }
    echo "</form>";
}
echo "</div>";



function displayBookContentForStudent($student) {
    if ($student->book == null) {
        global $dataStore;
        $booksWithAvailability = $dataStore->bookAvailability();

        foreach ($booksWithAvailability as $bookAvailability) {
            displayListItemForBook($bookAvailability, $student);
        }
    } else {
        echo "<h2>You currently have " . $student->book->title . " from the " . $student->book->homeroom->name . " room.</h2>";
        echo "<button class='bigButton' onclick=\"returnBookForStudent($student->studentId)\" >Return</button>";
    }
}

function displayListItemForBook($bookAvailability, $student) {
    $book = $bookAvailability[0];
    $available = $bookAvailability[1];

    static $bookDisplayed = false;
    if ($bookDisplayed) {
        echo "<div class='line'></div>";
    }
    echo "<h2>$book->title</h2>";
    echo "by $book->author<br>";
    echo "(Lexile#: $book->lexile)<br>";
    $bookDisplayed = true;

    if ($available) {
        echo "<button class='medButton' onclick=\"getBookAvailability('$book->ISBN', $student->studentId)\">Borrow!</button>";
    } else {
        echo "sorry, all checked out";
    }
}

?>

<hr>
<form class="centered" action="allloans.php" method=get>
    <button class="bigButton">View all loans</button>
</form>

</body>
</html>