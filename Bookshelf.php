<?php

class Bookshelf
{
    public $books = array();

    function addBook($title, $author, $ISBN, $lexile) {
        $newBook = new Book();
        $newBook->title = $title;
        $newBook->author = $author;
        $newBook->ISBN = $ISBN;
        $newBook->lexile = $lexile;

        $this->books[] = $newBook;

        $this->display();
    }

    function display() {
        foreach ($this->books as $book) {
            $book->display();
            echo "<br>";
        }
    }
}