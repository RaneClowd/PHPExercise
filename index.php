<html>

<!--background?: 50C8FF-->
<!--accentdark: 3EDFE8-->
<!--accentlight: 44FFD4-->
<!--baselight: 3E85E8-->
<!--basedark: 4461FF-->

<head>
    <title>Library</title>

    <script src="jquery-1.12.0.min.js"></script>
    <script src="serviceScript.js"></script>

    <style>
        body {
            background-color: #50C8FF;
            margin: 0;
        }

        button {
            padding: 0;
            border: none;
        }

        #popup {
            width:100%;
            height:100%;
            top:0;
            left:0;
            display:none;
            position:fixed;
            overflow:auto
        }

        #mask {
            width:100%;
            height:100%;
            opacity:.65;
            top:0;
            left:0;
            position:fixed;
            background-color:#313131;
            overflow:auto
        }

        #overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400px;
            height: 300px;
            margin-top: -150px;
            margin-left: -200px;

            padding: 30px;

            background-color: #3EDFE8;
        }

        .bigButton {
            width: 30%;
            min-width: 160px;
            height: 5vw;
            min-height: 40px;
            margin-top: 1.5vw;
            margin-bottom : 1.5vw;

            font-size: 150%;

            background-color: #3EDFE8;
        }

        .bigButton:hover {
            background-color: #44FFD4;
        }

        .medButton {
            width: 20%;
            min-width: 160px;
            height: 3vw;
            min-height: 40px;
            margin-top: 1.5vw;
            margin-bottom : 1.5vw;

            font-size: 120%;

            background-color: #3EDFE8;
        }

        .medButton:hover {
            background-color: #44FFD4;
        }

        .line {
            width: 100%;
            height: 2px;
            background-color: #4461FF;
            margin: 5px;
        }

        .content {
            padding:2vw;
        }

        .header {
            background-color: #3E85E8;
            padding: .01vw;
            padding-left: 2vw;
        }

        .centered {
            text-align: center;
        }
    </style>
</head>



<body>

<div id="demo"></div>

<div id="popup">
    <div id="mask">
    </div>
    <div id="overlay">
        <h3 id="popupTitle"></h3>
        <span id="popupMessage"></span>;
    </div>
</div>

<?php
include 'DataStore.php';

$dataStore = new DataStore();

$homeroomNameParamKey = "homeroom";
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
    if (isset($_GET[$homeroomNameParamKey])) {
        echo "<h2>What is your name?</h2>";

        $homeroomName = trim($_GET[$homeroomNameParamKey]);
        $homeroom = $dataStore->homeroomWithName($homeroomName);

        if ($homeroom == null) {
            echo "Error: homeroom not found";
        } else {
            displayStudentOptions($homeroom);
        }
    } else {
        echo "<h2>What is your homeroom?</h2>";

        displayHomeRoomOptions();
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
        echo "you have " . $student->book->title . " from the " . $student->book->homeroom->name . " room<br>";
        echo "<button onclick=\"returnBookForStudent($student->studentId)\" >Return</button>";
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

function displayStudentOptions($homeroom) {
    global $studentIdKey;
    foreach ($homeroom->students as $student) {
        echo "<button class='bigButton' name='$studentIdKey' value='" . $student->studentId . "' type=\"submit\">$student->firstName $student->lastName</button><br>";
    }
}

function displayHomeRoomOptions() {
    global $dataStore, $homeroomNameParamKey;

    foreach ($dataStore->homeroomNames() as $homeroomName) {
        echo "<button class='bigButton' type='submit' name='$homeroomNameParamKey' value='$homeroomName'>$homeroomName</button><br>";
    }
}

?>

<hr>
<form action="allloans.php" method=get>
    <button>View all loans</button>
</form>

</body>
</html>