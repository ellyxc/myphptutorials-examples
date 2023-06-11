<?php
require_once __DIR__.'/cek-akses.php';
require_once __DIR__.'/i18n/translate.php';
if (!empty($_POST)) {
    foreach($_POST['qty'] as $id => $jumlah) {
        $_SESSION['keranjang'][$id] = max($jumlah, 1);
    };

    header('Location: keranjang.php');
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
        <title>Keranjang Belanja</title>
    </head>
    <body>
        <?php
        include 'menu.php';
        ?>
        <div class="container">
            <?php if (isset($_SESSION['keranjang'])) {?>
            <form action="" method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th class="text-end">Harga</th>
                        <th  class="text-end">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pdo = require_once 'koneksi.php';
                    $sql = 'SELECT * FROM produk where id in(';
                    $idProduk = array_keys($_SESSION['keranjang']);
                    $sql .= trim(str_repeat('?,', count($idProduk)), ',');
                    $sql .= ')';
                    $queery = $pdo->prepare($sql);
                    $queery->execute($idProduk);
                    $total = 0;
                    $jumlah = 0;
                    while($produk = $queery->fetch()) {
                        $total += $produk['harga'] * $_SESSION['keranjang'][$produk['id']];
                        $jumlah += $_SESSION['keranjang'][$produk['id']];
                    ?>
                    <tr>
                        <td><?php echo htmlentities($produk['nama']);?></td>
                        <td><input type="number" name="qty[<?php echo $produk['id'];?>]"
                            value="<?php echo $_SESSION['keranjang'][$produk['id']];?>"
                            class="form-control w-auto"/></td>
                        <td class="text-end">Rp <?php echo number_format($produk['harga'], 0, ',', '.');?></td>
                        <td class="text-end">Rp <?php echo number_format($produk['harga'] * $_SESSION['keranjang'][$produk['id']], 0, ',', '.');?></td>
                        <td>
                            <a href="hapus-keranjang.php?id=<?php echo $produk['id'];?>"
                            onclick="return confirm('Apakah Anda yakin menghapus produk ini dari keranjang belanja?');">Hapus</a>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Jumlah</td>
                        <td><?php _t('{0, plural, other{# Produk}}', $jumlah);?></td>
                        <td class="text-end">Total</td>
                        <td class="text-end h4 text-success"><?php formatCurrency($total);?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="text-end">
                <?php
                if (hasAccess('editKeranjang')) {
                ?>
                <button type="submit" class="btn btn-secondary">Update</button>
                <?php }?>
                <a href="simpan-order.php" class="btn btn-primary">Pesan Sekarang</a>
            </div>
            </form>
            <?php } else {?>
                <p>Keranjang Belanja Kosong</p>
            <?php }?>
        </div>
    </body>
</html>