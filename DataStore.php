<?php

include 'DataModels/Bookshelf.php';
include 'DataModels/Homeroom.php';

class DataStore
{
    public $homerooms = array();
    private $commonBookshelf;

    private $dbLink;

    function __construct() {
        $servername = "db4free.net";
        $username = "raneclowd";
        $password = "raining";
        $database = "skaggsphpdb";
        //$this->dbLink = mysqli_connect($servername, $username, $password, $database);

        if (!$this->dbLink) {
            //echo "not using database!!!";
            $this->commonBookshelf = $this->loadMasterBookshelf();
            $this->loadHomeRooms();
        }
    }

    private function loadMasterBookshelf() {
        $bookshelf = new Bookshelf();

        if ($this->dbLink) {
            $sql = "SELECT Title, Author, ISBN, Lexile FROM Books";
            $result = $this->dbLink->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $bookshelf->addBook($row["Title"], $row["Author"], $row["ISBN"], $row["Lexile"]);
                }
            }
        } else {
            $bookshelfFile = fopen("Resources/BookShelf.txt", "r") or die("Unable to open file!");

            while(!feof($bookshelfFile)) {
                $bookLine = fgets($bookshelfFile);
                $bookComponents = explode("\t", $bookLine);

                $bookshelf->addBook($bookComponents[0], $bookComponents[1], $bookComponents[2], $bookComponents[4]);
            }
            fclose($bookshelfFile);
        }

        return $bookshelf;
    }

    private function loadHomeRooms() {
        $studentFile = simplexml_load_file("data.xml") or die("Error: Cannot create object");
        $loanConnectionsToMake = array();

        foreach ($studentFile->children() as $homeRoomNode) {
            $homeRoom = new Homeroom();
            $homeRoom->name = $homeRoomNode->Name;
            $homeRoom->level = $homeRoomNode->Level;
            $homeRoom->fillBookshelfWithCopy($this->commonBookshelf);

            foreach ($homeRoomNode->Students->children() as $studentNode) {
                $student = new Student();
                $student->FirstName = $studentNode->FirstName;
                $student->LastName = $studentNode->LastName;
                $student->ID = $studentNode->ID;

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

        if ($this->dbLink) {
            $sql = "SELECT Name FROM Homerooms";
            $result = $this->dbLink->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($names, $row["Name"]);
                }
            }
        } else {
            foreach ($this->homerooms as $homeroom) {
                array_push($names, $homeroom->name);
            }
        }
        return $names;
    }

    function homeroomNamesWithBook($bookISBN) {
        $names = array();

        if ($this->dbLink) {
            $sql = "SELECT Homerooms.Name FROM (Homerooms INNER JOIN Books) LEFT JOIN Loans ON Books.ISBN = Loans.ISBN WHERE Books.ISBN='$bookISBN' AND Loans.ISBN IS NULL";
            $result = $this->dbLink->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($names, $row["Name"]);
                }
            }
        } else {
            foreach ($this->homerooms as $homeroom) {
                $bookInRoom = $homeroom->bookshelf->bookWithISBN($bookISBN);

                // TODO: error if book not found

                if (!$bookInRoom->isCheckedOut()) {
                    array_push($names, $homeroom->name);
                }
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

    function studentsInHomeRoom($homeRoomName) {

        if ($this->dbLink) {
            $studentArray = array();
            $sql = "SELECT FirstName, LastName, ID FROM Students WHERE Homeroom = '$homeRoomName'";
            $result = $this->dbLink->query($sql);


            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $student = new Student();
                    $student->FirstName = $row["FirstName"];
                    $student->LastName = $row["LastName"];
                    $student->ID = $row["ID"];

                    array_push($studentArray, $student);
                }
            }
            return $studentArray;
        } else {
            $homeroom = $this->homeroomWithName($homeRoomName);
            return $homeroom->students;
        }

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