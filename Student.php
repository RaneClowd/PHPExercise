<?php

// would include book here, but that causes it to be included twice elsewhere (HomeRoom)
// TODO: find way to include if needed

class Student
{
    public $studentId;
    public $firstName;
    public $lastName;

    public $homeroom;
    public $book;

    function checkOutBook($book) {
        $book->isCheckedOut = true;
        $this->book = $book;
    }

    function returnBook() {
        $this->book->isCheckedOut = false;
        $this->book = null;
    }

    function display() {
        echo "$this->firstName $this->lastName ($this->studentId)" . "<br>";

        if ($this->book != null) {
            echo "has book " . $this->book->title . "<br>";
        }
    }
}