<?php
session_start();
if (!isset($_SESSION['browser']) || !isset($_SESSION['ip']) || !isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['browser'] != md5($_SERVER['HTTP_USER_AGENT']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    header("Location: login.php");
    exit;
}

$fileAkses = __DIR__.DIRECTORY_SEPARATOR.'akses'.DIRECTORY_SEPARATOR.$_SESSION['user']['tipe'].'.php';
if (!file_exists($fileAkses)) {
    echo 'Terjadi kesalahan, silahkan hubungi Admin!';
    exit;
}

$akses = include $fileAkses;
$url = $_SERVER['REQUEST_URI'];
$urlUtama = '/todo2/';
$url = substr($url, strlen($urlUtama));
die(pathinfo($url, PATHINFO_DIRNAME));
$filename = substr($url[0], strlen($urlUtama));
if (!in_array($filename, $akses)) {
    echo 'Anda tidak diizinkan mengakses halaman ini!';
    exit;
}