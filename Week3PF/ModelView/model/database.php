<?php

    // global $dbname, $querty, $localhost, $user, $pass;

    // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //     $dbname = $_POST['name'];
    //     $query = $_POST['query'];
    //     $localhost = $_POST['localhost'];
    //     $user = $_POST['user'];
    //     $pass = $_POST['password'];
    // }


    class ConnectAndCreate {
        private $name = 'ecommerce';
        private $localhost = 'localhost';
        private $user = 'postgres';
        private $pass = 'qazQAZ123';
        private $port = 5432;

        private $dbconn;

        public function __construct() {
            $connection_string = "host={$this->localhost} port={$this->port} dbname={$this->name} user={$this->user} password={$this->pass} ";
            $this->dbconn = pg_connect($connection_string);
        }


        public function query($query) {
            $result = pg_query($this->dbconn, $query);
            return $result;
        }

        public function escape($query) {
            return pg_escape_string($this->dbconn ,$query);
        }


        public function create() {
            $connection_string = "host={$this->localhost} port={$this->port} dbname={$this->name} user={$this->user} password={$this->pass} ";
            $dbconn = pg_connect($connection_string);

            if ($dbconn) {

                echo '<br>' . "Connected to " . $this->name;
            } else {
                echo "Error connecting to the database";
            }
        }


    }

    
?>

