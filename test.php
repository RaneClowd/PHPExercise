<html>
    <head>
        <title>Sorry</title>
    </head>


    <body bgcolor="#000000" text="#ffffff">



        <form action="test.php" method=get>

            <?php
            include 'DataStore.php';

            $dataStore = new DataStore();

            $homeroomNameParamKey = "homeroom";
            $studentIdKey = "id";

            if (isset($_GET[$homeroomNameParamKey])) {
                $homeroomName = trim($_GET[$homeroomNameParamKey]);
                $homeroom = $dataStore->homeroomWithName($homeroomName);

                if ($homeroom == null) {
                    echo "Error: homeroom not found";
                } else {
                    displayStudentOptions($homeroom);
                }
            } elseif (isset($_GET[$studentIdKey])) {
                $studentId = trim($_GET[$studentIdKey]);
                $student = $dataStore->studentWithId($studentId);

                if ($student == null) {
                    echo "Error: student not found";
                } else {
                    displayBooksPageForStudent($student);
                }
            } else {
                displayHomeRoomOptions();
            }

            function displayBooksPageForStudent($student) {;
                foreach ($student->homeroom->bookshelf->books as $book) {
                    displayListItemForBook($book);
                }
            }

            function displayListItemForBook($book) {
                echo $book->title;

                if ($book->isCheckedOut) {
                    echo " (checked out)";
                }

                echo "<br>";
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

        </form>

    </body>

</html>