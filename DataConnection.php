<?php

class DataConnection
{
    // TODO: get this shared between instances
    private $dbLink;

    public function callProc($procName) {
        $errorMsg = null;
        $results = null;

        if ( !$this->connectionEstablished()) {
            $errorMsg = "Error reaching database";
        } else {
            $queryResult = $this->dbLink->query("CALL $procName()");

            if ($queryResult == false) {
                $errorMsg = "Error in database";
            } else {
                $results = array();
                while($row = $queryResult->fetch_assoc()) {
                    array_push($results, $row);
                }
            }
        }

        return array("errorMsg" => $errorMsg, "results" => $results);
    }

    private function connectionEstablished() {
        if ( !empty($this->dbLink)) {
            return true;
        } else {
            return $this->connect();
        }
    }

    private function connect() {
        $servername = "db4free.net";
        $username = "raneclowd";
        $password = "raining";
        $database = "skaggsphpdb";
        $this->dbLink = mysqli_connect($servername, $username, $password, $database);

        if (empty($this->dbLink)) {
            return false;
        }
        return true;
    }
}