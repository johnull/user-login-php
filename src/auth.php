<?php
require_once('../includes/db.php');
class Auth
{
  private $email;
  private $pass;
  private $conn;

  function __construct($db)
  {
    $this->conn = $db;

  }

  public function userLogin($email, $pass)
  {
    if (empty($email) || empty($pass)) {
      printf("<p style='color: red;'>Email and password are required.</p>");
      return null;
    }
  }
}
?>