<?php

class Student
{
    public $studentId;
    public $firstName;
    public $lastName;

    function display() {
        echo "$this->firstName $this->lastName ($this->studentId)" . "<br>";
    }
}