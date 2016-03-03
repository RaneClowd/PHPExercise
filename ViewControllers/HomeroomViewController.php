<?php
include_once 'ViewController.php';
include_once '../DataModels/Homeroom.php';

class HomeroomViewController extends ViewController
{
    public static $parameterKey = "homeroom";

    public static function displayHomeRoomNames() {
        $response = Homeroom::homeroomNames();
        if ( !empty($response["errorMsg"])) {
            HomeroomViewController::displayErrorMessage($response["errorMsg"]);
        } else {
            foreach ($response["results"] as $homeroomName) {
                echo "<button class='bigButton' type='submit' name='" . HomeroomViewController::$parameterKey . "' value='{$homeroomName['Name']}'>{$homeroomName['Name']}</button><br>";
            }
        }
    }

    public static function displayStudentOptions() {

        foreach ($students as $student) {
            echo "<button class='bigButton' name='$studentIdKey' value='" . $student->studentId . "' type=\"submit\">$student->firstName $student->lastName</button><br>";
        }
    }
}