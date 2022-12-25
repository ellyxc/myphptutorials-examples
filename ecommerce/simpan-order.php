<?php
session_start();
$error = '';
if (!empty($_POST)) {
    try {
        if ($_POST['password'] != $_POST['password2']) {
            throw new Exception('Password dan ketik ulang password harus sama');
        }

        $pdo = require 'koneksi.php';
        $pdo->beginTransaction();
        $queryUser = $pdo->prepare('INSERT INTO users (nama, email, password, tipe)
        values(:nama, :email, :password,:tipe)');
        $queryUser->execute([
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'password' => sha1($_POST['password']),
            'tipe' => 'pembeli',
        ]);
        $idUser = $pdo->lastInsertId();
        $queryAlamat = $pdo->prepare('INSERT INTO alamat
        (jalan, desa, kecamatan, kabupaten, provinsi, kodepos, id_user)
        values (:jalan, :desa, :kecamatan, :kabupaten, :provinsi, :kodepos, :id_user)');
        $queryAlamat->execute([
            'jalan' => $_POST['jalan'],
            'desa' => $_POST['desa'],
            'kecamatan' => $_POST['kecamatan'],
            'kabupaten' => $_POST['kabupaten'],
            'provinsi' => $_POST['provinsi'],
            'kodepos' => $_POST['kodepos'],
            'id_user' => $idUser,
        ]);
        $idAlamat = $pdo->lastInsertId();
        $queryPesanan = $pdo->prepare('INSERT INTO pesanan
        (id_user, total_harga, tanggal_pesanan, id_alamat)
        values (:id_user, :total_harga, now(), :id_alamat)');
        $queryPesanan->execute([
            'id_user' => $idUser,
            'total_harga' => 0,
            'id_alamat' => $idAlamat,
        ]);
        $idPesanan = $pdo->lastInsertId();
        $total = 0;
        foreach($_SESSION['keranjang'] as $idProduk => $qty) {
            $queryProduk = $pdo->prepare('SELECT * FROM produk where id=:id');
            $queryProduk->execute(['id' => $idProduk]);
            $produk = $queryProduk->fetch();
            $queryItem = $pdo->prepare('INSERT INTO item_pesanan
            (id_pesanan, id_produk, harga, qty)
            values (:id_pesanan, :id_produk, :harga, :qty)');
            $queryItem->execute([
                'id_pesanan' => $idPesanan,
                'id_produk' => $idProduk,
                'harga' => $produk['harga'],
                'qty' => $qty,
            ]);
            $total += ($produk['harga'] * $qty);
        }

        $queryUpdate = $pdo->prepare('UPDATE pesanan set total_harga=:total where id=:id');
        $queryUpdate->execute([
            'id' => $idPesanan,
            'total' => $total,
        ]);
        $pdo->commit();
        // hapus keranjang belanja
        unset($_SESSION['keranjang']);
        header('Location: order-sukses.php?id='.$idPesanan);
        exit;
    } catch(PDOException $e) {
        $pdo->rollback();
        if ($e->errorInfo[0] == 23505) {
            $error = 'Email sudah tercatat, gunakan yang lain';
        } else {
            $error = 'Terjadi kesalahan saat menyimpan data';
        }
        error_log($e->getMessage());
    }
    catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="styles.css" rel="stylesheet">
        <title>Studi Kasus Ecommerce</title>
    </head>
    <body>
    <?php
        include 'menu.php';
        ?>
        <div class="container">
            <?php
            if (!empty($error)) {
                echo '<p class="alert alert-danger">'.$error.'</p>';
            }
            ?>
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <h4>Data Pembeli</h4>
                    <hr/>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input name="nama" type="text" class="form-control"
                        value="<?php echo $_POST['nama'] ?? '';?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control"
                        value="<?php echo $_POST['email'] ?? '';?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ketik Ulang Password</label>
                        <input name="password2" type="password" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>Alamat Pengiriman</h4>
                    <hr/>
                    <div class="mb-3">
                        <label class="form-label">Jalan</label>
                        <input name="jalan" type="text" class="form-control"
                        value="<?php echo $_POST['jalan'] ?? '';?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Desa / kelurahan</label>
                        <input name="desa" type="text" class="form-control"
                        value="<?php echo $_POST['desa'] ?? '';?>">
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input name="kecamatan" type="text"
                            value="<?php echo $_POST['kecamatan'] ?? '';?>" class="form-control">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Kabupaten</label>
                            <input name="kabupaten" type="text" value="<?php echo $_POST['kabupaten'] ?? '';?>" class="form-control">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input name="provinsi" value="<?php echo $_POST['provinsi'] ?? '';?>" type="text" class="form-control">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Kode Pos</label>
                            <input name="kodepos" value="<?php echo $_POST['kodepos'] ?? '';?>" type="text" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Pesan</button>
            </div>
        </form>
        </div>
    </body>
</html>