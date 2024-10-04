<?php ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
require_once('../src/user.php');
require_once('../includes/db.php');
require_once('../assets/messages.php');
try {
  $db = new DbConnection();
  $conn = $db->connect();
  $user = new User($conn);


  if (!isset($_SESSION['id'])) {
    header('location: login-form.php');
    exit();
  }

  if (isset($_POST['deleteItem'])) {
    if ($user->deleteUser($_POST['deleteItem'])) {
      header('location: dashboard.php');
      exit();
    }
    echo $notify;
  }

  if (isset($_POST['createUser'])) {
    $user = new User($conn);

    $user->setUsername($_POST['username']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);
    $user->setConfirmPassword($_POST['cpass']);

    $user->adminCreateUser();
    header('location: dashboard.php');
    exit();
  }


  if (isset($_POST['updateUser'])) {
    // $user->updateUser($_POST['updateUser']);
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../assets/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

  <title>Dashboard</title>
</head>

<!-- ADMIN CREATE USER MODAL -->
<div class="modal" id="registerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create An User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST">
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required />
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required />
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password"
              required />
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" name="cpass" class="form-control form-control-lg" placeholder="Confirm your password"
              required />
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="createUser">Register</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- ADMIN UPDATE USER MODAL-->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST">
          <div><strong>Fill in only the fields you want to update.</strong></div>
          <br>
          <div data-mdb-input-init class="form-outline mb-4">
            What is the user's email?
            <input type="email" name="id-email" class="form-control form-control-lg" placeholder="Email" />
          </div>
          <hr>
          Data to update
          <br>
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" />
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" />
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnUpdate" name="updateUser">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>

  $(document).ready(function () {
    $('#registerModal').on('shown.bs.modal', function () {
      $(this).focus();
    });

    $('[data-bs-toggle="modal"]').on('shown.bs.modal', function () {
      $(this).focus();
    });

  });
</script>

<body>
  <div class="container" style="display:flex; justify-content: center; padding: 15px 0px 20px 0px">
    <a style="color:blue;font-weight:bold; display: flex; justify-content:center" href="./logout.php">LOGOUT</a>
  </div>
  <div class="container" style="display:flex; justify-content: center; padding: 15px 0px 20px 0px">
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#registerModal"
      style="margin-right: 10px">
      Create User
    </button>
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateModal">Update
      User
    </button>
  </div>

  <form action="#" method="POST">
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
              <?php echo $row['admin'] == 1 ? '' : '<td><button class="btn btn-outline-danger" type="submit" name="deleteItem" value="' . $row['email'] . '" />Delete</button></td>'; ?>
            </tr>

          <?php }
        } else {
          echo "<tr><td colspan='5'>No users found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

  </form>

</body>

</html>