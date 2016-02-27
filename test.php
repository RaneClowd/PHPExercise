<html>
<head>
    <title>Sorry</title>
</head>


<body bgcolor="#000000" text="#ffffff">

<?php
    include 'Book.php';
    include 'Bookshelf.php';

    function loadBooks() {
        $bookshelfFile = fopen("BookShelf.txt", "r") or die("Unable to open file!");
        $bookshelf = new Bookshelf();

        // Output one line until end-of-file
        while(!feof($bookshelfFile)) {
            $bookLine = fgets($bookshelfFile);
            $bookComponents = explode("\t", $bookLine);

            $bookshelf->addBook($bookComponents[0], $bookComponents[1], $bookComponents[2], $bookComponents[4]);
        }
        fclose($bookshelfFile);

        $bookshelf->display();
    }

    loadBooks();
?>

</body>

</html>