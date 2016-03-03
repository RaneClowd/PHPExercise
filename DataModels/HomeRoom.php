<?php
include_once 'Object.php';
include 'Student.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/DataConnection.php';

class Homeroom extends Object
{

    static function homeroomNames() {
        $dataConnection = new DataConnection();
        return $dataConnection->callProc("HomeroomNames()");
    }




    // TODO: delete these older things if they're no longer needed

    public $name;
    public $students = array();
    public $level;
    public $bookshelf;

    function fillBookshelfWithCopy($bookshelfToCopy) {
        $bookshelfCopy = new Bookshelf();
        foreach ($bookshelfToCopy->books as $book) {
            $bookClone = clone $book;
            $bookClone->homeroom = $this;
            $bookshelfCopy->books[] = $bookClone;
        }

        $this->bookshelf = $bookshelfCopy;
    }

    function display() {
        echo "HomeRoom named $this->name with students (in level $this->level):" . "<br>";
        foreach ($this->students as $student) {
            $student->display();
        }
        echo "<hr>";
        $this->bookshelf->display();
    }
}