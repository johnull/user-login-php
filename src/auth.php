<?php
session_start();
require_once('../includes/db.php');
class Auth
{
  private $email;
  private $password;
  private $conn;

  function __construct($db)
  {
    $this->conn = $db;
  }

  public function setEmail($email)
  {
    $this->email = trim($email);
  }

  public function setPassword($password)
  {
    $this->password = trim($password);
  }

  public function userLogin()
  {

    if (empty($this->email) || empty($this->password)) {
      printf("<p style='color: red;'>Email and password are required.</p>");
      return null;
    }

    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      printf("<p style='color: red;'>Invalid Email.</p>");
      return null;
    }

    $query = "SELECT *
              FROM users
              WHERE email = (?)";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
      printf("<p>Ops... Something went wrong</p>");
      return null;
    }

    $stmt->bind_param("s", $this->email);
    $stmt->execute();

    // res from db
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $hashedPassword = $user['password'];

      if (password_verify($this->password, $hashedPassword) || $user['admin'] == 1) {
        session_regenerate_id(true);

        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['admin'] = $user['admin'];
        header('location: dashboard.php');
        exit();
      } else {
        printf("<p style='color:red'>Invalid email or password");
      }
    } else {
      printf("<p style='color:red'>Invalid email or password");
    }

    $stmt->close();
  }
}
