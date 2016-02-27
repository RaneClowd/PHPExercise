<?php

include 'DataModels/Bookshelf.php';
include 'DataModels/HomeRoom.php';

class DataStore
{
    public $homerooms = array();
    private $commonBookshelf;

    function __construct() {
        $this->commonBookshelf = $this->loadMasterBookshelf();
        $this->loadHomeRooms();

        // TODO: figure out what's going on with the timestamp's timezone
        date_default_timezone_set("America/Chicago");
    }

    private function loadMasterBookshelf() {
        $bookshelfFile = fopen("Resources/BookShelf.txt", "r") or die("Unable to open file!");
        $bookshelf = new Bookshelf();

        while(!feof($bookshelfFile)) {
            $bookLine = fgets($bookshelfFile);
            $bookComponents = explode("\t", $bookLine);

            $bookshelf->addBook($bookComponents[0], $bookComponents[1], $bookComponents[2], $bookComponents[4]);
        }
        fclose($bookshelfFile);

        return $bookshelf;
    }

    private function loadHomeRooms() {
        $studentFile = simplexml_load_file("data.xml") or die("Error: Cannot create object");
        $loanConnectionsToMake = array();

        foreach ($studentFile->children() as $homeRoomNode) {
            $homeRoom = new HomeRoom();
            $homeRoom->name = $homeRoomNode->Name;
            $homeRoom->level = $homeRoomNode->Level;
            $homeRoom->fillBookshelfWithCopy($this->commonBookshelf);

            foreach ($homeRoomNode->Students->children() as $studentNode) {
                $student = new Student();
                $student->firstName = $studentNode->FirstName;
                $student->lastName = $studentNode->LastName;
                $student->studentId = $studentNode->ID;

                if (!empty($studentNode->Loan)) {
                    $loanTimestamp = strtotime($studentNode->Loan->Date);
                    $loanConnectionsToMake[] = array($student, $studentNode->Loan->Homeroom, $studentNode->Loan->ISBN, $loanTimestamp);
                }

                // TODO: make sure there's no GC-type problems with cyclical refs like this (and on book-homerooms)
                $homeRoom->students[] = $student;
                $student->homeroom = $homeRoom;
            }

            $this->homerooms[] = $homeRoom;
        }

        foreach ($loanConnectionsToMake as $loanConnection) {
            $bookHomeroom = $this->homeroomWithName($loanConnection[1]);

            $bookStudentHas = $bookHomeroom->bookshelf->bookWithISBN($loanConnection[2]);
            $bookStudentHas->homeroom = $bookHomeroom;

            $loanTimestamp = $loanConnection[3];
            if ($bookStudentHas == null) {
                echo "Error: couldn't match book to student";
            } else {
                $loanConnection[0]->checkOutBook($bookStudentHas, $loanTimestamp);
            }
        }
    }

    function homeroomNames() {
        $names = array();
        foreach ($this->homerooms as $homeroom) {
            array_push($names, $homeroom->name);
        }
        return $names;
    }

    function homeroomNamesWithBook($bookISBN) {
        $names = array();
        foreach ($this->homerooms as $homeroom) {
            $bookInRoom = $homeroom->bookshelf->bookWithISBN($bookISBN);

            // TODO: error if book not found

            if (!$bookInRoom->isCheckedOut()) {
                array_push($names, $homeroom->name);
            }
        }
        return $names;

        // TODO: return array of error and results
    }

    function homeroomWithName($homeroomName) {
        foreach ($this->homerooms as $homeroom) {
            if (strcmp($homeroom->name, $homeroomName) == 0) {
                return $homeroom;
            }
        }

        return null;
    }

    function studentWithId($studentId) {
        foreach ($this->homerooms as $homeroom) {
            foreach ($homeroom->students as $student) {
                if ($student->studentId == $studentId) {
                    return $student;
                }
            }
        }

        return null;
    }

    function bookAvailability() {
        $bookAvailability = array();
        $booksWithUnknownStatus = $this->commonBookshelf->books;

        foreach ($booksWithUnknownStatus as $bookThatMightBeAvailable) {
            $available = false;
            $studentsWithBook = array();

            foreach ($this->homerooms as $homeroom) {
                $availabilityEntry = array();
                $bookFromHomeroom = $homeroom->bookshelf->bookWithISBN($bookThatMightBeAvailable->ISBN);
                array_push($availabilityEntry, $bookFromHomeroom);

                if ($bookFromHomeroom->isCheckedOut()) {
                    array_push($studentsWithBook, $bookFromHomeroom->student);
                } else {
                    $available = true;
                }
            }

            array_push($bookAvailability, array($bookThatMightBeAvailable, $available, $studentsWithBook));
        }

        return $bookAvailability;
    }

    function checkoutBookWithISBNAndStudentFromHomeroom($isbn, $studentId, $homeroomName) {
        $student = $this->studentWithId($studentId);
        if ($student == null) {
            return "Unable to find student";
        }


        $homeroom = $this->homeroomWithName($homeroomName);
        if (empty($homeroom)) {
            return "Unable to find homeroom";
        }

        $book = $homeroom->bookshelf->bookWithISBN($isbn);
        if ($book == null) {
            return "Unable to find book";
        }

        $student->checkOutBook($book, time());
        $this->saveBackToXML();

        return false;
    }

    function returnBookForStudentWithID($studentId) {
        $student = $this->studentWithId($studentId);
        if (empty($student)) {
            return "Unable to find student";
        }

        if (empty($student->book)) {
            return "Unable to find book";
        }

        $student->returnBook();
        $this->saveBackToXML();

        return false;
    }

    function saveBackToXML() {
        $root = simplexml_load_string('<HomeRooms></HomeRooms>');

        foreach($this->homerooms as $homeroom) {
            $homeroomNode = $root->addChild('HomeRoom');
            $homeroomNode->addChild('Name', $homeroom->name);
            $homeroomNode->addChild('Level', $homeroom->level);

            $studentsRootNode = $homeroomNode->addChild('Students');
            foreach ($homeroom->students as $student) {
                $studentNode = $studentsRootNode->addChild('Student');
                $studentNode->addChild('ID', $student->studentId);
                $studentNode->addChild('FirstName', $student->firstName);
                $studentNode->addChild('LastName', $student->lastName);

                if (!empty($student->book)) {
                    $loanNode = $studentNode->addChild('Loan');
                    $loanNode->addChild('ISBN', $student->book->ISBN);
                    $loanNode->addChild('Homeroom', $student->book->homeroom->name);

                    $dateString = date("m/d/Y H:i", $student->loanTimestamp);
                    $loanNode->addChild('Date', $dateString);
                }
            }
        }

        $root->asXml('data.xml');
    }

    function display() {
        $this->bookshelf->display();
        foreach ($this->homerooms as $homeroom) {
            $homeroom->display();
        }
    }
}