<?php
require_once __DIR__.'/cek-akses.php';
checkUserAccess('home');
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
        <?php include_once 'list-produk.php'; ?>
        </div>
    </body>
</html>