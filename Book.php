<?php

class Book
{
    public $title;
    public $author; // Better to make an author object, but there's other things I want to spend that time on
    public $ISBN;
    public $lexile;
    public $homeroom;
    public $isCheckedOut = false;

    /*function copyOfBook() {
        // TODO: checked out is the only thing i really want to clone. Check to see if clone will work here

        $bookCopy = new Book();
        $bookCopy->title = $this->title;
        $bookCopy->author
    }*/

    function display() {
        echo $this->title . "<br>";
        echo $this->author . "<br>";
        echo $this->ISBN . "<br>";
        echo $this->lexile . "<br>";
        if ($this->isCheckedOut) {
            echo "loaned";
        }
        echo "<br>";
        echo $this->homeroom->name . "<br>";
    }
}