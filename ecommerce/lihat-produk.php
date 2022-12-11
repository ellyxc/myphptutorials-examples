<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$pdo = require_once 'koneksi.php';
$query = $pdo->prepare('SELECT * FROM produk WHERE id=:id');
$query->execute(['id' => $_GET['id']]);
$produk = $query->fetch();
if (!$produk) {
    header("Location: index.php");
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
        <title><?php echo htmlentities($produk['nama']);?> - Lihat Produk</title>
    </head>
    <body>
        <?php
        include 'menu.php';
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <?php
                    $queryGambar = $pdo->prepare('SELECT * FROM gambar_produk
                    WHERE id_produk=:id order by utama asc');
                    $queryGambar->execute(['id' => $produk['id']]);
                    $slide = '';
                    $indicator = '';
                    $i = 0;
                    while($gambar = $queryGambar->fetch()) {
                        $slide .= ' <div class="carousel-item '.($i == 0 ?'active' : '').'">
                        <img src="images/'.$gambar['gambar'].'" class="d-block w-100">
                        </div>';
                        $indicator .= '<button type="button" data-bs-target="#gambarProduk"
                            data-bs-slide-to="'.$i.'" class="'.($i == 0 ?'active' : '').'"></button>';
                        $i ++;
                    }
                    ?>
                    <div id="gambarProduk" class="carousel slide" data-bs-ride="true">
                        <div class="carousel-indicators">
                            <?php echo $indicator;?>
                        </div>
                        <div class="carousel-inner">
                            <?php echo $slide;?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#gambarProduk" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#gambarProduk" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="mt-4">
                    <?php
                    echo nl2br(htmlentities($produk['deskripsi']));
                    ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <h1><?php echo htmlentities($produk['nama']);?></h1>
                    <h2 class="text-success">Rp <?php echo number_format($produk['harga'], 0, ',', '.');?></h2>
                    <p>Stok: <?php echo htmlentities($produk['stok']);?></p>
                    <form method="POST" action="tambah-keranjang.php?id=<?php echo $produk['id'];?>">
                    <div class="row g-2">
                        <div class="col-3">
                            <input type="number" value="1" name="qty" class="form-control"/>
                        </div>
                        <div class="col-9">
                            <button class="btn btn-primary w-100" type="submit">Tambah ke Keranjang</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>