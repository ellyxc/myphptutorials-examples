<?php
$error = '';
if (!empty($_POST)) {
    $pdo = require '../koneksi.php';
    try {
        $pdo->beginTransaction();
        $queryKode = $pdo->prepare('SELECT count(*) as jml FROM produk where kode=:kode1');
        $queryKode->execute(['kode1' => $_POST['kode']]);
        $count = $queryKode->fetchColumn();
        if ($count > 0) {
            throw new Exception('Kode produk sudah digunakan masukkan yang lain');
        }

        $query = $pdo->prepare('INSERT INTO produk
        (kode, nama, harga, stok, deskripsi) VALUES
        (:kode, :nama, :harga, :stok,:deskripsi)');
        $query->execute([
            'kode' => $_POST['kode'],
            'nama' => $_POST['nama'],
            'harga' => $_POST['harga'],
            'stok' => $_POST['stok'],
            'deskripsi' => $_POST['deskripsi'],
        ]);
        $produkId = $pdo->lastInsertId();
        //proses gambar utama
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (isset($_FILES['gambar_utama'])
            && $_FILES['gambar_utama']['error'] == 0
            && $_FILES['gambar_utama']['size'] > 0) {
            $tipeFile = $finfo->file($_FILES['gambar_utama']['tmp_name']);
            if (!in_array($tipeFile, ['image/png','image/jpeg'])) {
                throw new Exception("Gambar tidak bisa diterima1");
            }

            $filename = md5(random_bytes(10)).'.'
                .pathinfo($_FILES['gambar_utama']['name'], PATHINFO_EXTENSION);
            $queryGambarUtama = $pdo->prepare('INSERT INTO gambar_produk
            (id_produk, gambar, utama) VALUES (:id_produk, :gambar, true)');
            $queryGambarUtama->execute([
                'id_produk' => $produkId,
                'gambar' => $filename
            ]);
            move_uploaded_file($_FILES['gambar_utama']['tmp_name'], '../images/'.$filename);
        }
        foreach($_FILES['gambar']['name'] as $index => $name) {
            if (empty($name)) {
                continue;
            }
            if ($_FILES['gambar']['error'][$index] != 0 || $_FILES['gambar']['size'][$index] <= 0) {
                // echo '<pre>';
                // print_r($_FILES['gambar']);
                throw new Exception('gambar tidak bisa diupload');
            }

            $tipeFile = $finfo->file($_FILES['gambar']['tmp_name'][$index]);
            if (!in_array($tipeFile, ['image/png', 'image/jpeg'])) {
                throw new Exception('Gambar tidak bisa diterima2');
            }

            $filename = md5(random_bytes(10)).'.'
            .pathinfo($name, PATHINFO_EXTENSION);
            $queryGambar = $pdo->prepare('INSERT INTO gambar_produk
            (id_produk, gambar, utama) VALUES (:id_produk, :gambar, false)');
            $queryGambar->execute([
                'id_produk' => $produkId,
                'gambar' => $filename
            ]);

            move_uploaded_file($_FILES['gambar']['tmp_name'][$index], '../images/'.$filename);
        }
        $pdo->commit();
        header("Location: tambah-produk.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <title>Tambah Produk</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    if ($error) {
                        echo '<p class="alert alert-danger">'.$error.'</p>';
                    }
                    ?>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Gambar Utama</label>
                            <input type="file" name="gambar_utama" required
                            accept="image/png,image/jpeg" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kode</label>
                            <input type="text" name="kode" class="form-control" required
                            value="<?php echo $_POST['kode'] ?? '';?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required
                            value="<?php echo $_POST['nama'] ?? '';?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control" required
                            value="<?php echo $_POST['harga'] ?? '';?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control"
                            value="<?php echo $_POST['stok'] ?? '0';?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Lain</label>
                            <input type="file" multiple name="gambar[]"
                                accept="image/png,image/jpg" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi"
                            class="form-control"><?php echo $_POST['deskripsi'] ?? '';?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>