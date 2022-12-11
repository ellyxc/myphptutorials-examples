<?php
$pdo = require './koneksi.php';
$query = $pdo->prepare("SELECT * FROM produk");
$query->execute();
?>
<div class="row gy-3">
    <?php
    while($produk = $query->fetch()){
    ?>
    <div class="col-md-3">
        <div class="border rounded h-100">
            <div class="gambar">
                <?php
                $queryGambar = $pdo->prepare("SELECT * FROM gambar_produk
                WHERE id_produk=:id AND utama=:utama");
                $queryGambar->execute([
                    'id' => $produk['id'],
                    'utama' => true,
                ]);
                $gambar = $queryGambar->fetch();
                if ($gambar) {
                    echo '<a href="lihat-produk.php?id='.$produk['id'].'">
                        <img src="images/'.$gambar['gambar'].'" class="rounded-top">
                        </a>';
                }
                ?>
            </div>
            <div class="p-3">
                <div class="nama-produk">
                <a href="lihat-produk.php?id=<?php echo $produk['id'];?>">
                    <?php echo htmlentities($produk['nama']);?>
                </a>
                </div>
                <div class="harga text-success mb-2">
                Rp <?php echo number_format($produk['harga'], 0, ',', '.');?>
                </div>
                <div class="tombol">
                    <a href="tambah-keranjang.php?id=<?php echo $produk['id'];?>"
                    class="btn btn-outline-secondary btn-sm">Tambah</a>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>