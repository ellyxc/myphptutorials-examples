<?php
require_once __DIR__.'/cek-akses.php';
checkUserAccess('lihatDaftarPesanan');
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
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pdo = require __DIR__.'/koneksi.php';
                    $query = $pdo->prepare('SELECT * FROM pesanan WHERE id_user=:id');
                    $query->execute(['id' => $_SESSION['user']['id']]);
                    while($pesanan = $query->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo htmlentities($pesanan['id']);?></td>
                        <td><?php echo htmlentities($pesanan['tanggal_pesanan']);?></td>
                        <td><?php echo number_format($pesanan['total_harga'], 0,',','.');?></td>
                        <td>
                            <a href="lihat-pesanan.php?id=<?php echo htmlentities($pesanan['id']);?>">Lihat</a>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </body>
</html>