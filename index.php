<html>

<head>
    <title>Library</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="serviceScript.js"></script>

    <link rel="stylesheet" type="text/css" href="Resources/style.css">
</head>


<body>

<div id="popup">
    <div id="mask">
    </div>
    <div id="overlay" class="centered">
        <h3 id="popupTitle"></h3>
        <span id="popupMessage"></span>
        <div class="centered" id="popupContent"></div>
    </div>
</div>

<?php
include_once 'ViewControllers/HomeroomViewController.php';
include_once 'ViewControllers/StudentViewController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<div class='content centered'>";
if (isset($_GET[StudentViewController::$parameterKey])) {
    StudentViewController::displayStudentView();
} else {

    echo "<div class='header'>";
    echo "<h1>Let's get to your books</h1>";
    echo "</div>";

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
echo "</div>";

?>

<hr>
<form class="centered" action="allloans.php" method=get>
    <button class="bigButton">View all loans</button>
</form>

</body>
</html>