<?php

include 'Student.php';

class HomeRoom
{
    public $name;
    public $students = array();
    public $level;
    public $bookshelf;

    function display() {
        echo "HomeRoom named $this->name with students (in level $this->level):" . "<br>";
        foreach ($this->students as $student) {
            $student->display();
        }
        echo "<hr>";
    }
}