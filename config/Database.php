<?php
  class Database {
    private $host = 'localhost';
    private $username = 'fakeuser';
    private $password = 'fakepassword';
    private $dbname = 'fakedb';
    private $conn;

    public function connect() {
      $this->conn = new mysqli(
        $this->host, 
        $this->username, 
        $this->password, 
        $this->dbname
      );
      if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
      }
      return $this->conn;
    }

    public function close() {
      $this->conn->close();
    }
    
  }
?>