<?php
class User
{
  private $conn;
  private $table_name = "users";

  private $username;
  private $email;
  private $password;
  private $cpass;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function setUsername($username)
  {
    $this->username = trim($username);
  }

  public function setEmail($email)
  {
    $this->email = trim($email);
  }

  public function setPassword($password)
  {
    $this->password = trim($password);
  }

  public function setConfirmPassword($cpass)
  {
    $this->cpass = trim($cpass);
  }

  public function userCreate()
  {

    if (empty($this->username) || empty($this->email) || empty($this->password) || empty($this->cpass)) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            All fields are required.
          </div>
        </div>');
      return null;
    }

    // validationg email format
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Invalid email format.
          </div>
        </div>');
      return null;
    }

    // validationg pass

    if (strlen($this->password) < 8 || !preg_match('/[A-Za-z]/', $this->password) || !preg_match('/[0-9]/', $this->password)) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Password must be at least 8 characters long and contain both letters and numbers.
          </div>
        </div>');
      return null;
    }

    if ($this->password !== $this->cpass) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Passwords do not match.
          </div>
        </div>');
      return null;
    }

    // verifying if email exists
    $query = "SELECT *
                FROM $this->table_name
                WHERE email = ?";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Ops... Something went wrong
          </div>
        </div>');
      return null;
    }

    $stmt->bind_param("s", $this->email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert">
            Unable to register. Try again.
          </div>
        </div>');
      return null;
    }

    // ----------------------

    // create new user with hashed pass
    $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

    $query = "INSERT 
              INTO users(username, email, password)
              VALUES (?, ?, ?)";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sss", $this->username, $this->email, $hashedPassword);

    if ($stmt->execute()) {
      printf("<p>Successful. Redirecting to login...</p>");
      header("refresh:3; url=login-form.php");
      exit();
    } else {
      printf("<p>Ops... Something went wrong.</p>");
    }

    $stmt->close();
  }

  public function adminCreateUser()
  {
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Invalid email format.
          </div>
        </div>');

      return null;
    }

    if (strlen($this->password) < 8 || !preg_match('/[A-Za-z]/', $this->password) || !preg_match('/[0-9]/', $this->password)) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Password must be at least 8 characters long and contain both letters and numbers.
          </div>
        </div>');

      return null;
    }

    if ($this->password !== $this->cpass) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Password does not match!
          </div>
        </div>');

      return null;
    }

    $query = "SELECT *
    FROM $this->table_name
    WHERE email = ?";

    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Ops... Something went wrong
          </div>
        </div>');
      return null;
    }

    $stmt->bind_param("s", $this->email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      printf('
        <div class="container-error" style="display:flex; justify-content: center;">
          <div class="alert alert-danger" role="alert" style="width: auto">
            Unable to register.
          </div>
        </div>');
      return null;
    }

    $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

    $query = "INSERT
              INTO users(username, email, password)
              VALUES (?, ?, ?)";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sss", $this->username, $this->email, $hashedPassword);

    if (!$stmt)
      die('MySQL prepare error:' . $this->conn->error);

    if ($stmt->execute()) {
      if ($stmt->affected_rows > 0) {
        $stmt->close();
        header('location: dashboard.php');
        return true;
      } else {
        $stmt->close();
        return false;
      }
    } else {
      die("MySQL execute error: " . $stmt->error);
    }
  }

  public function deleteUser($email)
  {
    $query = "DELETE FROM users WHERE email = ?";

    $stmt = $this->conn->prepare($query);

    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
      if ($stmt->affected_rows > 0) {
        header('location: dashboard.php');
        $stmt->close();
        return true;
      } else {
        $stmt->close();
        return false;
      }
    } else {
      die("MySQL execute error: " . $stmt->error);
    }
  }

  public function updateUser(int $id, $columnToChange, array $valueToChange)
  {
    $query = "SELECT email
              FROM users
              WHERE id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $res = $stmt->get_result();

    $cols = array();

    if ($res->num_rows > 0) {
      $queryUpdate = "UPDATE users SET $columnToChange = ? WHERE id = ? ";
      $stmt = $this->conn->prepare($queryUpdate);

      foreach ($valueToChange as $row) {
        $cols[] = "$columnToChange = '$row'";
      }

      foreach ($valueToChange as $value) {
        $stmt->bind_param("si", $value, $id);
        $stmt->execute();
        $queryUpdate = "";
        header('location: dashboard.php');
      }
      return true;
    }

    return false;
  }

  public function getEmailByID($id)
  {
    try {
      $query = "SELECT email FROM users WHERE id = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id);
      $stmt->execute();

      $res = $stmt->get_result();

      if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        $email = $row['email'];

        return $email;
      } else {
        echo '';
      }
    } catch (Exception $e) {
      echo 'user not found';
    }

  }

  public function getAllUsers($isAdmin)
  {
    if ($isAdmin) {
      $query = "SELECT * FROM users";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      $result = $stmt->get_result();

      $users = [];

      while ($row = $result->fetch_assoc()) {
        $users[] = $row;
      }

      return $users;
    }

    return [];
  }
}