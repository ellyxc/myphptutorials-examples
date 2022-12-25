<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
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
        <title>Studi Kasus Ecommerce</title>
    </head>
    <body>
    <?php
        include 'menu.php';
        ?>
        <div class="container">
            <div class="alert alert-success">Pesanan Tersimpan</div>
            <p>Terima kasih atas pesanan Anda. Kami akan segera proses pesanana Anda.
                ID Pesanan Anda Adalah: <?php echo htmlentities($_GET['id']);?>
            </p>
        </div>
    </body>
</html>