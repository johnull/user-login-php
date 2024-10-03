<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
  $db = new DbConnection();
  $conn = $db->connect();
} catch (Exception $e) {
  printf("Error: %s", $e);
}

require_once("../includes/db.php");
require_once("../src/user.php");

$dbConnection = new DbConnection();
$conn = $dbConnection->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = new User($conn);

  $user->setUsername($_POST['username']);
  $user->setEmail($_POST['email']);
  $user->setPassword($_POST['password']);
  $user->setConfirmPassword($_POST['cpass']);

  $user->userCreate();
}

?>
<!doctype html>
<html>

<head>
  <title>Register</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
  <section class="vh-100 bg-image">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
      <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-9 col-lg-7 col-xl-6">
            <div class="card" style="border-radius: 40px;width:30rem;">
              <div class="card-body p-5" style="width:30rem; border: 2px solid #7287fd; border-radius: 40px;">
                <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                <form action="#" method="POST">
                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username"
                      required />
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email"
                      required />
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password"
                      required />
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" name="cpass" class="form-control form-control-lg"
                      placeholder="Confirm your password" required />
                  </div>

                  <div class="d-flex justify-content-center">
                    <button type="submit" name="register" data-mdb-button-init data-mdb-ripple-init
                      class="btn btn-success btn-block btn-lg gradient-custom-4"
                      style="width: 18rem; background-color: #1e66f5; text-color: #cecece">Register</button>
                  </div>

                  <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="login-form.php"
                      class="fw-bold text-body"><u>Login here</u></a></p>

                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>