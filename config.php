<?php
$validLangs = ['tr', 'en', 'ar', 'ru', 'es'];

if (isset($_GET['lang']) && in_array($_GET['lang'], $validLangs)) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/");
} elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $validLangs)) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'tr';
}
?>
