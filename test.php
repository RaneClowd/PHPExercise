<html>
<head>
    <title>Sorry</title>
</head>


<body bgcolor="#000000" text="#ffffff">

<?php
    include 'Book.php';

    function readBooks() {
        $bookshelfFile = fopen("BookShelf.txt", "r") or die("Unable to open file!");

        // Output one line until end-of-file
        while(!feof($bookshelfFile)) {
            $bookLine = fgets($bookshelfFile);
            $bookComponents = explode("\t", $bookLine);

            $book = new Book();
            $book->title = $bookComponents[0];
            $book->author = $bookComponents[1];
            $book->ISBN = $bookComponents[2];
            $book->lexile = $bookComponents[4];

            $book->display();
            echo "<br>";
        }
        fclose($bookshelfFile);
    }

    readBooks();
?>

</body>

</html>