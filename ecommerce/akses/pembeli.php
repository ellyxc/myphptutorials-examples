<?php
$access = require __DIR__.'/tamu.php';
return array_merge($access, [
    'lihatPesanan' => true,
    'lihatDaftarPesanan' => true,
]);