<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$pdo = require './koneksi.php';
$query = $pdo->prepare('SELECT * from pesanan where id=:id');
$query->execute(['id' => $_GET['id']]);
$pesanan = $query->fetch();
if (!$pesanan) {
    header('Location: index.php');
    exit;
}

$queryPembeli = $pdo->prepare('SELECT * from users where id=:id');
$queryPembeli->execute(['id' => $pesanan['id_user']]);
$pembeli = $queryPembeli->fetch();

$gatewayUrl = 'https://app.sandbox.midtrans.com';
$curl = curl_init($gatewayUrl.'/snap/v1/transactions');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_USERPWD, 'ganti_dengan_midtrans_server_key:');
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
    'transaction_details' => [
        'order_id' => 'order-'.$pesanan['id'],
        'gross_amount' => $pesanan['total_harga'],
    ],
    'credit_card' => [
        'secure' => true
    ],
    'customer_details' => [
        'first_name' => $pembeli['nama'],
        'email' => $pembeli['email'],
    ]
]));

$response = curl_exec($curl);
$token = json_decode($response, true);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="styles.css" rel="stylesheet">
        <title>Studi Kasus Ecommerce</title>
        <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="ganti_dengan_midtrans_client_key"></script>
    </head>
    <body>
    <?php
        include 'menu.php';
        ?>
        <div class="container">
            <div class="alert alert-success">Pesanan Tersimpan</div>
            <p>Terima kasih atas pesanan Anda. Kami akan segera proses pesanana Anda.
                ID Pesanan Anda Adalah:
                <a href="login.php?back=lihat-pesanan.php?id=<?php echo htmlentities($_GET['id']);?>"><?php echo htmlentities($_GET['id']);?></a>
            </p>
            <?php
            if (isset($token['token'])) {?>
            <p>
                <a class="btn btn-lg btn-primary" href="<?php echo $token['redirect_url'];?>">Bayar Sekarang</a>
            </p>
            <?php }?>
        </div>
        <script>
            var btnBayar = document.getElementById('bayar');
            btnBayar.addEventListener('click', function(event) {
                event.preventDefault();
                window.snap.pay('<?php echo $token['token'];?>', {
                    onSuccess: function() {
                        alert('Pembayaran Berhasil');
                    },
                    onError: function() {
                        alert('Pembayaran Gagal');
                    }
                });
            })
        </script>
    </body>
</html>
