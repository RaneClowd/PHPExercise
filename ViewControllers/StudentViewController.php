<?php
require_once 'ViewController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DataModels/Student.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DataModels/Book.php';

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
        if ( !empty($this->student->book)) {
            // TODO: show that student has book
            echo "You have a book";
        } else {
            $response = Book::allBooksAndAvailability();

            if ( !empty($response->errorMsg)) {
                $this->displayErrorMessage($response->errorMsg);
            } else {
                foreach ($response->result as $book) {
                    $this->displayListItemForBook($book);
                }
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
}*/

    private function displayListItemForBook($book) {
        static $firstBookDisplayed = false;
        if ($firstBookDisplayed) {
            echo "<div class='line'></div>";
        }

        echo "<h2>$book->Title</h2>";
        echo "by $book->Author<br>";
        echo "(Lexile#: $book->Lexile)<br>";
        $firstBookDisplayed = true;

        if ($book->NumCopiesAvailable > 0) {
            echo "<button class='medButton' onclick=\"getBookAvailability('$book->ISBN', {$this->student->ID})\">Borrow!</button>";
        } else {
            echo "sorry, all checked out";
        }
    }
}