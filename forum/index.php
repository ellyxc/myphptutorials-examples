<?php
session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <title>Forum PHP</title>
  </head>
  <body>
      <?php
      $__menuAktif = 'home';
      include 'menu.php';
      ?>
      <div class="container">
        <h1>
          <?php
          if (isset($_SESSION['user'])) {
            echo $_SESSION['user']['nama'];
          }
          ?>
          Selamat Datang di Forum PHP
        </h1>
        <?php
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
          $pdo = require 'koneksi.php';
          $sql = "SELECT judul, tanggal, nama, email, topik.id, id_user FROM topik
          INNER JOIN users ON topik.id_user = users.id
          ORDER BY tanggal DESC";
          $query = $pdo->prepare($sql);
          $query->execute();
        ?>
        <h3 class="mt-5">Daftar Topik</h3>
        <hr/>
        <?php
        while($data = $query->fetch()) {
        ?>
        <div class="row">
          <div class="col-auto">
            <img src="//www.gravatar.com/avatar/<?php echo md5($data['email']);?>?s=48&d=monsterid" class="rounded-circle"/>
          </div>
        <figure class="col">
          <blockquote class="blockquote">
            <p>
              <a href="lihat-topik.php?id=<?php echo $data['id'];?>"><?php echo htmlentities($data['judul']);?></a>
            </p>
          </blockquote>
          <figcaption class="blockquote-footer">
            Dari: <?php echo htmlentities($data['nama']);?>
            - <?php echo date('d M Y H:i', strtotime($data['tanggal']));?>
            <?php
            if ($_SESSION['user']['id'] == $data['id_user']) {?>
            - <a href="hapus-topik.php?id=<?php echo $data['id'];?>"
                onclick="return confirm('Apakah Anda yakin menghapus topik ini?')"
                class="text-muted">Hapus</a>
            <?php }?>
          </figcaption>
        </figure>
        </div>
        <?php }?>
        <?php }?>
      </div>
    <script src="boostrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>