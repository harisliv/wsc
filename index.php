  <?php include "header.php"; ?>
  <?php include "footer.php"; ?>
  <?php   error_reporting(0);

headernav();
  session_start();
     session_unset();
    unset($_SESSION['id_acadsem']);
    unset($_SESSION['authtoken']);

  ?>
  <!-- /navbar -->

  <!-- content section will be here -->
  <main role="main" class="container starter-template">

      <div class="row">
          <div class="col">

  <h2>Login</h2>
     <form action="loginform.php" method="post">
              <div class='form-group'>
                  <label for='username'>Username</label>
                  <input type='username' class='form-control' id='username' name='username' placeholder='Enter username'>
              </div>

              <div class='form-group'>
                  <label for='password'>Password</label>
                  <input type='password' class='form-control' id='password' name='password' placeholder='Password'>
              </div>

              <input type="submit" value="Submit">
          </form>

    <?php footernav(); ?>
