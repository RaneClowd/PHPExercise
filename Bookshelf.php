<?php

include 'Book.php';

class Bookshelf
{
    public $books = array();

    function copyOfBookshelf()
    {
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