<?php

include 'Bookshelf.php';
include 'HomeRoom.php';

class DataStore
{
    private $homerooms = array();

    function __construct() {
        $bookshelf = $this->loadMasterBookshelf();
        $this->loadHomeRoomsAndGiveBookshelf($bookshelf);
    }

    private function loadMasterBookshelf() {
        $bookshelfFile = fopen("BookShelf.txt", "r") or die("Unable to open file!");
        $bookshelf = new Bookshelf();

        while(!feof($bookshelfFile)) {
            $bookLine = fgets($bookshelfFile);
            $bookComponents = explode("\t", $bookLine);

            $bookshelf->addBook($bookComponents[0], $bookComponents[1], $bookComponents[2], $bookComponents[4]);
        }
        fclose($bookshelfFile);

        return $bookshelf;
    }

    private function loadHomeRoomsAndGiveBookshelf($bookshelf) {
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

            $homeRoom->bookshelf = $bookshelf->copyOfBookshelf();
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

    function homeroomWithName($homeroomName) {
        foreach ($this->homerooms as $homeroom) {
            if ($homeroom->name == $homeroomName) {
                return $homeroom;
            }
        }

        return null;
    }

    function studentWithId($studentId) {
        foreach ($this->homerooms as $homeroom) {
            foreach ($homeroom->students as $student) {
                if ($student->studentId == $studentId) {
                    return $student;
                }
            }
        }

        return null;
    }

    function display() {
        $this->bookshelf->display();
        foreach ($this->homerooms as $homeroom) {
            $homeroom->display();
        }
    }
}