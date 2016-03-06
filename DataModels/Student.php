<?php
require_once 'Object.php';
require_once 'Book.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DataConnection.php';

class Student extends Object
{
    public $ID;
    public $FirstName;
    public $LastName;
    public $book;
    public $LoanTime;

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

            if ( !empty($response->result[0]["Title"])) {
                $student->book = new Book();
                $student->book->populateWithArray($response->result[0]);
            }

            $response->result = $student;
        }

        return $response;
    }
}