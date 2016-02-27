<?php

class Book
{
    public $title;
    public $author; // Better to make an author object, but there's other things I want to spend that time on
    public $ISBN;
    public $lexile;

    function display() {
        echo $this->title . "<br>";
        echo $this->author . "<br>";
        echo $this->ISBN . "<br>";
        echo $this->lexile . "<br>";
    }
}