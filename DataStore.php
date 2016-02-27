<?php

include 'Bookshelf.php';
include 'HomeRoom.php';

class DataStore
{
    private $homerooms = array();
    private $commonBookshelf;

    function __construct() {
        $this->commonBookshelf = $this->loadMasterBookshelf();
        $this->loadHomeRooms();
    }

    private function loadMasterBookshelf() {
        $bookshelfFile = fopen("BookShelf.txt", "r") or die("Unable to open file!");
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
                    $loanConnectionsToMake[] = array($student, $studentNode->Loan->Homeroom, $studentNode->Loan->ISBN);
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

            if ($bookStudentHas == null) {
                echo "Error: couldn't match book to student";
            } else {
                $loanConnection[0]->checkOutBook($bookStudentHas);
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

            if (!$bookInRoom->isCheckedOut) {
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
            $bookFoundToBeAvailable = false;

            foreach ($this->homerooms as $homeroom) {
                $bookFromHomeroom = $homeroom->bookshelf->bookWithISBN($bookThatMightBeAvailable->ISBN);
                if (!$bookFromHomeroom->isCheckedOut) {
                    $bookFoundToBeAvailable = true;
                    break;
                }
            }

            array_push($bookAvailability, array($bookThatMightBeAvailable, $bookFoundToBeAvailable));
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

        $student->checkOutBook($book);
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