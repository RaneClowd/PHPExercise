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

$viewController = viewControllerForPageState();

echo "<div class='content centered'>";
echo "<div class='header'>";
$viewController->displayHeaderContent();
echo "</div>";

echo "<form action=\"index.php\" method=get>";
$viewController->displayBodyContent();
echo "</form>";
echo "</div>";

function viewControllerForPageState() {
    if (isset($_GET[StudentViewController::$parameterKey])) {
        return new StudentViewController();
    } else {
        return new HomeroomViewController();
    }
}

?>

<hr>
<form class="centered" action="allloans.php" method=get>
    <button class="bigButton">View all loans</button>
</form>

</body>
</html>