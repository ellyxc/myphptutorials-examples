<?php
$access = require __DIR__.'/pembeli.php';
return array_merge($access, [
    'tambahProduk' => true,
]);