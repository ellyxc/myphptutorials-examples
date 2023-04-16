<?php
require_once __DIR__.'/cek-akses.php';
checkUserAccess('lihatPesanan');
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$pdo = require 'koneksi.php';
$query = $pdo->prepare('SELECT * FROM pesanan p
    INNER JOIN alamat a ON p.id_alamat = a.id
    WHERE p.id=:id AND p.id_user=:id_user');
$query->execute([
    'id' => $_GET['id'],
    'id_user' => $_SESSION['user']['id']
]);
$pesanan = $query->fetch();
if (!$pesanan) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="styles.css" rel="stylesheet">
        <title>Lihat Pesanan</title>
    </head>
    <body>
    <?php
        include 'menu.php';
        ?>
        <div class="container">
            <h2>Order #<?php echo htmlentities($pesanan['id']);?></h2>
            <p>Tanggal: <?php echo htmlentities($pesanan['tanggal_pesanan']);?></p>
            <div class="card">
                <div class="card-header">Alamat Pengiriman</div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-md-3">Jalan</dt>
                        <dd class="col-md-9"><?php echo htmlentities($pesanan['jalan']);?></dd>
                        <dt class="col-md-3">Desa</dt>
                        <dd class="col-md-9"><?php echo htmlentities($pesanan['desa']);?></dd>
                        <dt class="col-md-3">Kecamatan</dt>
                        <dd class="col-md-9"><?php echo htmlentities($pesanan['kecamatan']);?></dd>
                        <dt class="col-md-3">Kabupaten</dt>
                        <dd class="col-md-9"><?php echo htmlentities($pesanan['kabupaten']);?></dd>
                        <dt class="col-md-3">Provinsi</dt>
                        <dd class="col-md-9"><?php echo htmlentities($pesanan['provinsi']);?></dd>
                        <dt class="col-md-3">Kode POS</dt>
                        <dd class="col-md-9"><?php echo htmlentities($pesanan['kodepos']);?></dd>
                    </dl>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">Item Pesanan</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $queryItem = $pdo->prepare('SELECT i.id, i.qty, i.harga, p.nama
                            FROM item_pesanan i
                            INNER JOIN produk p ON i.id_produk = p.id
                            WHERE id_pesanan=:id');
                            $queryItem->execute(['id' => $pesanan['id']]);
                            while($item = $queryItem->fetch()) {
                            ?>
                            <tr>
                                <td><?php echo htmlentities($item['nama']);?></td>
                                <td>Rp <?php echo number_format($item['harga'], 0, ',', '.');?></td>
                                <td><?php echo htmlentities($item['qty']);?></td>
                                <td>Rp <?php echo number_format($item['qty'] * $item['harga'], 0, ',', '.');?></td>
                            </tr>
                            <?php }?>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td>Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.');?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>