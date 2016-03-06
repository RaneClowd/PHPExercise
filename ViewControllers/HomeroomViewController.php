<?php
include_once 'ViewController.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/DataModels/Homeroom.php';
include_once 'StudentViewController.php';

class HomeroomViewController extends ViewController
{
    public static $parameterKey = "homeroom";

    public function displayHeaderContent() {
        echo "<h1>Let's get to your books</h1>";
    }

    public function displayBodyContent()
    {
        echo "<form action=\"index.php\" method=get>";
        if (isset($_REQUEST[HomeroomViewController::$parameterKey])) {
            echo "<h2>What is your name?</h2>";

            HomeroomViewController::displayStudentOptions($_REQUEST[HomeroomViewController::$parameterKey]);
        } else {
            echo "<h2>What is your homeroom?</h2>";

            HomeroomViewController::displayHomeRoomNames();
        }
        echo "</form>";
    }

    private static function displayHomeRoomNames() {
        $response = Homeroom::homeroomNames();
        if ( !empty($response->errorMsg)) {
            HomeroomViewController::displayErrorMessage($response["errorMsg"]);
        } else {
            foreach ($response->result as $homeroomName) {
                echo "<button class='bigButton' type='submit' name='" . HomeroomViewController::$parameterKey . "' value='{$homeroomName['Name']}'>{$homeroomName['Name']}</button><br>";
            }
        }
    }

    private static function displayStudentOptions($homeroomName) {
        $response = Student::studentNamesInRoom($homeroomName);
        if ( !empty($response->errorMsg)) {
            HomeroomViewController::displayErrorMessage($response["errorMsg"]);
        } else {
            foreach ($response->result as $student) {
                echo "<button class='bigButton' name='" . StudentViewController::$parameterKey . "' value='" . $student->ID . "' type=\"submit\">$student->FirstName $student->LastName</button><br>";
            }
        }
    }
}