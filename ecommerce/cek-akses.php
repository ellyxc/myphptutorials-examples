<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function hasLogin() {
    return isset($_SESSION['user']) && empty($_SESSION['user']['id']) == false;
}

function hasAccess($operation) {
    $role = 'tamu';
    if (hasLogin()) {
        $role = $_SESSION['user']['role'];
    }

    $accessFile = __DIR__.'/akses/'.$role.'.php';
    if (!file_exists($accessFile)) {
        $accessFile = __DIR__.'/akses/tamu.php';
    }

    $access = require $accessFile;
    return $access[$operation] ?? false;
}

function checkUserAccess($operation) {
    $hasAccess = hasAccess($operation);
    if (!$hasAccess && !hasLogin()) {
        header('Location: login.php');
        exit;
    }

    if (!$hasAccess) {
        echo 'Anda tidak punya akses';
        exit;
    }
}