<?php

abstract class ViewController
{
    abstract public function displayHeaderContent();
    abstract public function displayBodyContent();

    protected function displayErrorMessage($errorMsg) {
        // TODO: display errors gracefully
        echo $errorMsg . "<br>";
    }
}