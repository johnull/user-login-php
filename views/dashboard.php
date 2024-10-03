<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
require_once('../src/user.php');
require_once('../includes/db.php');

try {
  $db = new DbConnection();
  $conn = $db->connect();
  $user = new User($conn);

  if (!isset($_SESSION['id'])) {
    header('location: login-form.php');
    exit();
  }

  if ($_SESSION['admin'] === 1) {
    $users = $user->getAllUsers(isAdmin: true);
  } else {
    printf("Hello, %s\n", $_SESSION['username']);
    printf("<a style=\"font-weight:bold; display: flex; justify-content:center\" href=\"./logout.php\">LOGOUT</a>");
    exit();
  }
} catch (Exception $e) {
  echo "Opss... Something went wrong.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/style.css">
  <title>Dashboard</title>
</head>

<body>
  <a style="font-weight:bold; display: flex; justify-content:center" href="./logout.php">LOGOUT</a>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Username</th>
        <th scope="col">Email</th>
        <th scope="col">Admin</th>
        <th scope="col">Password</th>
        <th scope="col">Created at</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($users) {
        foreach ($users as $row) {
      ?>
          <tr>
            <th scope="row"><?php echo $row['id'] ?></th>
            <td><?php echo $row['username'] ?></td>
            <td><?php echo $row['email'] ?></td>
            <td><?php echo $row['admin'] == 1 ? '<div class="rainbow rainbow_text_animated">-^_^-' : 'No' ?></td>
            <td><?php echo $row['password'] ?></td>
            <td><?php echo $row['created_at'] ?></td>
          </tr>
      <?php }
      } else {
        echo "<tr><td colspan='5'>No users found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</body>

</html>