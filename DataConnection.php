<?php

class DataConnection
{
    private $dbLink;

    public function homeroomNamesList() {
        $errorMsg = null;
        $results = null;

        if ( !$this->connectionEstablished()) {
            $errorMsg = "Error reaching database";
        } else {
            $queryResult = $this->dbLink->query("CALL HomeroomNames()");
            $results = array();
            while($row = $queryResult->fetch_assoc()) {
                array_push($results, $row["Name"]);
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

        if (!$this->dbLink) {
            return false;
        }
        return true;
    }
}