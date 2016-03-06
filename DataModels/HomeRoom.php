<?php
include_once 'Object.php';
include 'Student.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/DataConnection.php';

class Homeroom extends Object
{
    private $Name;

    public function __construct($name) {
        $this->Name = $name;
    }

    public static function homeroomNames() {
        $dataConnection = new DataConnection();
        return $dataConnection->callProc("HomeroomNames()");
    }

    public static function homeroomsWithBook($isbn) {
        $dataConnection = new DataConnection();
        return $dataConnection->callProc("HomeroomsWithBook('$isbn')");
    }

    public function checkOutIsbnToStudent($bookISBN, $studentID) {
        $dataConnection = new DataConnection();
        return $dataConnection->callProc("LoanIsbnFromHomeroomToStudent($studentID, '$bookISBN', '{$this->Name}')");
    }
}