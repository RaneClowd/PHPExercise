<html>
    <head>
        <title>Sorry</title>
    </head>


    <body bgcolor="#000000" text="#ffffff">



        <form action="test.php" method=get>

            <?php
            include 'DataStore.php';

            $dataStore = new DataStore();

            foreach ($dataStore->homeroomNames() as $homeroomName) {
                echo "<input type=\"submit\" name=\"homeroom\" value=\"" . $homeroomName . "\">";
            }
            ?>

        </form>

    </body>

</html>