<?php

include 'Bookshelf.php';

class DataStore
{
    private $bookshelf;

    function __construct() {
        $this->loadBooksIntoBookshelf();
    }

    private function loadBooksIntoBookshelf() {
        $bookshelfFile = fopen("BookShelf.txt", "r") or die("Unable to open file!");
        $this->bookshelf = new Bookshelf();

        while(!feof($bookshelfFile)) {
            $bookLine = fgets($bookshelfFile);
            $bookComponents = explode("\t", $bookLine);

            $this->bookshelf->addBook($bookComponents[0], $bookComponents[1], $bookComponents[2], $bookComponents[4]);
        }
        fclose($bookshelfFile);
    }

    function display() {
        $this->bookshelf->display();
    }
}