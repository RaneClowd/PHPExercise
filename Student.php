<?php

// would include book here, but that causes it to be included twice elsewhere (DataStore)
// TODO: find way to include if needed

class Student
{
    public $studentId;
    public $firstName;
    public $lastName;
    public $book;

    function setBookCheckedOut(Book $book) {
        $book->checkedOut = true;
        $this->book = $book;
    }

    function display() {
        echo "$this->firstName $this->lastName ($this->studentId)" . "<br>";
    }
}