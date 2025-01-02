<?php
session_start();

// Kullanıcı adı ve soyadı bilgilerini al
$kullaniciAdi = $_SESSION['ad'];
$kullaniciSoyadi = $_SESSION['soyad'];

// Oturum bilgilerini temizle ve oturumu sonlandır
$_SESSION = array();
session_destroy();

// Kullanıcının çıkış yaptığı zamanı al
$timestamp = date("Y-m-d H:i:s");

// Ziyaretçi defterine çıkış bilgisini ve kullanıcı adı soyadı bilgisini ekle
$dosya = fopen("ziyaretci_defteri.php", "a");
fwrite($dosya, $kullaniciAdi . " " . $kullaniciSoyadi . " siteden çıkış yapmıştır. Tarih: " . $timestamp . "\n");
fclose($dosya);

header("refresh: 2; url=index.php");
exit;
