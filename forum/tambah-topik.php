<?php
require_once 'cek-akses.php';
if (!empty($_POST)) {
    $pdo = require 'koneksi.php';
    $sql = "insert into topik (judul, deskripsi, tanggal, id_user)
    values (:judul, :deskripsi, now(), :id_user)";
    $query = $pdo->prepare($sql);
    $query->execute(array(
        'judul' => $_POST['judul'],
        'deskripsi' => $_POST['deskripsi'],
        'id_user' => $_SESSION['user']['id'],
    ));
    header("Location: tambah-topik.php?sukses=1");
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <title>Forum PHP: Tambah Topik</title>
  </head>
  <body>
      <?php
      $__menuAktif = 'tambah_topik';
      include 'menu.php';
      ?>
      <div class="container">
          <?php
          if (isset($_GET['sukses']) && $_GET['sukses'] == '1') {
              echo '<p class="text-success">Topik berhasil dikirim</p>';
          }
          ?>
        <div class="row">
            <div class="col-md-6">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>
        </div>
      </div>
    <script src="boostrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>