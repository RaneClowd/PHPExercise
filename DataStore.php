<?php

include 'Bookshelf.php';
include 'HomeRoom.php';

class DataStore
{
    private $bookshelf;
    private $homerooms = array();

    function __construct() {
        $this->loadBooksIntoBookshelf();
        $this->loadHomeRooms();
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

    private function loadHomeRooms() {
        $studentFile = simplexml_load_file("Students.xml") or die("Error: Cannot create object");
        foreach ($studentFile->children() as $homeRoomNode) {
            $homeRoom = new HomeRoom();
            $homeRoom->name = $homeRoomNode->Name;
            $homeRoom->level = $homeRoomNode->Level;
            foreach ($homeRoomNode->Students->children() as $studentNode) {
                $student = new Student();
                $student->firstName = $studentNode->FirstName;
                $student->lastName = $studentNode->LastName;
                $student->studentId = $studentNode->ID;

                $homeRoom->students[] = $student;
            }

            $this->homerooms[] = $homeRoom;
        }
    }

    function homeroomNames() {
        $names = array();
        foreach ($this->homerooms as $homeroom) {
            array_push($names, $homeroom->name);
        }
        return $names;
    }

    function display() {
        $this->bookshelf->display();
        foreach ($this->homerooms as $homeroom) {
            $homeroom->display();
        }
    }
}