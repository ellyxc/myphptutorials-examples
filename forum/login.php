<?php
session_start();
$hasil = true;
if (!empty($_POST)) {
  $pdo = require 'koneksi.php';
  $sql = "select * from users where email = :email";
  $query = $pdo->prepare($sql);
  $query->execute(array('email' => $_POST['email']));
  $user = $query->fetch();
  if (!$user) {
    $hasil = false;
  } elseif(sha1($_POST['password']) != $user['password']) {
    $hasil = false;
  } else {
    $hasil = true;
    $_SESSION['user']= array(
      'id' => $user['id'],
      'nama' => $user['nama'],
      'email' => $user['email'],
    );

    header("Location: index.php");
    exit;
  }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <title>Forum: Login</title>
  </head>
  <body>
    <?php
      $__menuAktif = 'login';
      include 'menu.php';
    ?>
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <?php
          if (!$hasil) {
            echo '<p class="text-danger">Email atau password salah</p>';
          }
          ?>
          <form method="POST" action="">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" required/>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password" required/>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
          </form>
        </div>
      </div>
    </div>
    <script src="boostrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>