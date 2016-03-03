<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/DataConnection.php';

class Student
{
    public $ID;
    public $FirstName;
    public $LastName;

    static function studentNamesInRoom($homeroomName) {
        $dataConnection = new DataConnection();
        $response = $dataConnection->callProc("StudentsInRoom($homeroomName)");



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