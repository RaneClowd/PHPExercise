<html>
<head>
    <title>Sorry</title>
</head>


<body bgcolor="#000000" text="#ffffff">

<?php
    include 'DataStore.php';

    $dataStore = new DataStore();

    foreach ($dataStore->homeroomNames() as $homeroomName) {
        echo $homeroomName . "<br>";
    }
?>

</body>

</html>