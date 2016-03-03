<?php
include_once 'ViewController.php';

class StudentViewController extends ViewController
{
    public static $parameterKey = "id";

    public static function displayStudentView() {
        $studentID = $_REQUEST[StudentViewController::$parameterKey];
        $response = Student::studentWithID($studentID);

        if ( !empty($response->errorMsg)) {
            StudentViewController::displayErrorMessage($response->errorMsg);
        } else if ( empty($response->result)) {
            StudentViewController::displayErrorMessage("Unable to find student record.");
        } else {
            $student = $response->result;

            echo "<div class='header'>";
            var_dump($student);
            echo "<h1>Hi $student->FirstName!</h1>";
            echo "</div>";

            if ($student == null) {
                //echo "Error: student not found";
            } else {
                //displayBookContentForStudent($student);
            }
        }
    }

    /*function displayBookContentForStudent($student) {
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
}*/
}