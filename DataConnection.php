<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/Common/Response.php';

class DataConnection
{
    // TODO: get this shared between instances
    private $dbLink;

    public function callProc($procName) {
        $response = new Response();

        if ( !$this->connectionEstablished()) {
            $response->errorMsg = "Error reaching database";
        } else {
            $queryResult = $this->dbLink->query("CALL $procName");

            if ($queryResult == false) {
                $response->errorMsg = "Error in database: " . mysqli_error($this->dbLink);
            } else if ($queryResult !== true) { // TODO: handle this better. Just making sure it's not only a bool response for now
                $response->result = array();
                while($row = $queryResult->fetch_assoc()) {
                    array_push($response->result, $row);
                }
            }
        }

        return $response;
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