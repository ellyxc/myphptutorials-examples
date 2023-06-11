<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['__lang']) && !empty($_GET['__lang'])) {
    $_SESSION['lang'] = $_GET['__lang'];
}

function getCurrentLanguage() {
    if (isset($_SESSION['lang'])) {
        return $_SESSION['lang'];
    }

    return 'id_ID';
}

function getLanguages() {
    return [
        'id_ID' => [
            'title' => 'Indonesia',
            'label' => 'ID',
        ],
        'en_US' => [
            'title' => 'English US',
            'label' => 'EN'
        ]
    ];
}

// function _t($text, ...$values) {
//     $file = __DIR__ .'/langs/'.getCurrentLanguage().'.php';
//     if (!file_exists($file)) {
//         echo vsprintf($text, $values);
//         return;
//     }

//     $tranlation = include $file;
//     echo  vsprintf($tranlation[$text] ?? $text, $values);
// }

function _t($text, ...$values) {
    $locale = getCurrentLanguage();
    putenv('LC_ALL='.$locale);
    setlocale(LC_ALL, $locale);
    bindtextdomain('messages', __DIR__ .'/locales');
    textdomain('messages');
    echo MessageFormatter::formatMessage($locale, gettext($text), $values);
}

function formatCurrency($amount) {
    $format = new NumberFormatter(getCurrentLanguage(), NumberFormatter::CURRENCY);
    echo $format->formatCurrency($amount, 'IDR');
}