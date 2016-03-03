<?php
include_once 'ViewController.php';

class StudentViewController extends ViewController
{
    public static $parameterKey = "id";

    private $student;

    public function __construct() {
        $studentID = $_REQUEST[StudentViewController::$parameterKey];
        $response = Student::studentWithID($studentID);

        if ( !empty($response->errorMsg)) {
            $this->displayErrorMessage($response->errorMsg);
        } else if ( empty($response->result)) {
            $this->displayErrorMessage("Unable to find student record.");
        } else {
            $this->student = $response->result;
        }
    }

    public function displayHeaderContent() {
        echo "<h1>Hi {$this->student->FirstName}!</h1>";
    }

    public function displayBodyContent() {
        // TODO: Implement displayBodyContent() method.
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