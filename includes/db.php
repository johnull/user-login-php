<?php
class DbConnection {
  private $host = "localhost";
  private $username = "root";
  private $password = "root";
  private $database = "vault";
 
  protected $conn;
  
  public function connect() {
    $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
    
    if ($this->conn->connect_errno) {
      printf("Failed to connect to MySQL: %s\n", $this->conn->connect_error);
      return null;
    }
    
    return $this->conn;
  }
  
  public function __destruct() {
    if ($this->conn) {
      $this->conn->close();
    }
  }
}
?>