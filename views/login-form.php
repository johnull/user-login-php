<?php


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" </head>

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
                  <div data-mdb-input-init class="form-outline mb-4"></div>
                  <input type="text" name="username" class="form-control form-control-lg" placeholder="Username"
                    required />
                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password"
                      required />
                  </div>

                  <div class="d-flex justify-content-center">
                    <button type="submit" name="register" data-mdb-button-init data-mdb-ripple-init
                      class="btn btn-success btn-block btn-lg gradient-custom-4"
                      style="width: 18rem; background-color: #1e66f5; text-color: #cecece">Sign In</button>
                  </div>

                  <p class="text-center text-muted mt-5 mb-0">Don't have an account yes? <a href="registration-form.php"
                      class="fw-bold text-body"><u>Create here</u></a></p>
              </div>


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