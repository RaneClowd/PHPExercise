<html>
    <head>
        <title>Sorry</title>
    </head>

    <script>
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
    </script>


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
        echo "<form action=\"test.php\" method=get>";
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