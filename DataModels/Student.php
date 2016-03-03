<?php
include_once 'Object.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/DataConnection.php';

class Student extends Object
{
    public $ID;
    public $FirstName;
    public $LastName;

    static function studentNamesInRoom($homeroomName) {
        $dataConnection = new DataConnection();
        $response = $dataConnection->callProc("StudentsInRoom('$homeroomName')");

        if ( !empty($response->result)) {
            $studentArray = array();
            foreach ($response->result as $row) {
                $student = new Student();
                $student->populateWithArray($row);

                array_push($studentArray, $student);
            }
            $response->result = $studentArray;
        }

        return $response;
    }

    public static function studentWithID($studentID) {
        $dataConnection = new DataConnection();
        $response = $dataConnection->callProc("StudentWithID($studentID)");

        if ( !empty($response->result)) {
            $student = new Student();
            $student->populateWithArray($response->result[0]);
            $response->result = $student;
        }

        return $response;
    }


    // TODO: delete this older stuff if not needed



    public $homeroom;
    public $book;
    public $loanTimestamp;

    function checkOutBook($book, $dateCheckedOut) {
        $book->student = $this;

        $this->book = $book;
        $this->loanTimestamp = $dateCheckedOut;
    }

    function returnBook() {
        $this->book->student = null;
        $this->book = null;
    }

    function display() {
        echo "$this->FirstName $this->LastName ($this->ID)" . "<br>";

        if ($this->book != null) {
            echo "has book " . $this->book->title . "<br>";
        }
    }
}