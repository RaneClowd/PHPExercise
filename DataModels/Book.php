<?php
require_once 'Object.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/DataConnection.php';

class Book extends Object
{
    public $Title;
    public $Author; // Better to make an author object, but there's other things I want to spend that time on
    public $ISBN;
    public $Lexile;
    public $NumCopiesAvailable;

    public static function allBooksAndAvailability() {
        $dataConnection = new DataConnection();
        $response = $dataConnection->callProc("ListBooksAndCopiesFree()");

        if ( !empty($response->result)) {
            $bookArray = array();
            foreach ($response->result as $row) {
                $book = new Book();
                $book->populateWithArray($row);

                array_push($bookArray, $book);
            }
            $response->result = $bookArray;
        }

        return $response;
    }
}