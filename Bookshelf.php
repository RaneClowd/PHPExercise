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

    function copyOfBookshelf() {
        // this is essentially what I would have done in clone anyway
        $bookshelfCopy = new Bookshelf();
        foreach ($this->books as $book) {
            $bookshelfCopy->books[] = clone $book;
        }

        return $bookshelfCopy;
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