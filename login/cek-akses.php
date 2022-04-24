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