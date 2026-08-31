<?php
session_start();

$supported_langs = ['tr', 'en', 'es', 'ar', 'ru']; // desteklenen diller
$default_lang = 'tr';

if (isset($_GET['lang']) && in_array($_GET['lang'], $supported_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // ?lang=... parametresini siler
    exit;
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = $default_lang;
}
$lang = $_SESSION['lang'];

// Çeviri dosyasını dahil et
$translations = include 'translations.php'; // Çeviri verileri başka bir dosyada olacak
return $translations[$lang];
?>
