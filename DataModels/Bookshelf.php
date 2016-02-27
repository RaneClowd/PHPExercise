<?php

include 'Book.php';

class Bookshelf
{
    public $books = array();

    function bookWithISBN($isbn) {
        foreach ($this->books as $book) {
            if ($book->ISBN == $isbn) {
                return $book;
            }
        }

        return null;
    }

    function addBook($title, $author, $ISBN, $lexile) {
        $newBook = new Book();
        $newBook->title = $title;
        $newBook->author = $author;
        $newBook->ISBN = $ISBN;
        $newBook->lexile = $lexile;

        $this->books[] = $newBook;
    }

    function display() {
        foreach ($this->books as $book) {
            $book->display();
            echo "<br>";
        }
    }
}