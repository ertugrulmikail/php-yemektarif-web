<?php
include("baglanti.php");
session_start();

$kullaniciadi = $_POST['kullaniciadi'];
$sifre = $_POST['sifre'];

$query_kullanici = mysqli_query($baglanti, "SELECT * FROM demob.users WHERE kullanici_adi='$kullaniciadi' AND sifre='$sifre'");
$row_kullanici = mysqli_fetch_assoc($query_kullanici);

if ($row_kullanici) {

    $_SESSION['kullanici_id'] = $row_kullanici['id'];
    $_SESSION['ad'] = $row_kullanici['ad'];
    $_SESSION['soyad'] = $row_kullanici['soyad'];
    $_SESSION['email'] = $row_kullanici['email'];
    $_SESSION['kullanici_adi'] = $row_kullanici['kullanici_adi'];
    $_SESSION['profil_resmi'] = $row_kullanici['profil_resmi'];

    echo "Giriş başarılı";

    header("refresh: 3; url=index.php");

    $ziyaretci_defteri = fopen("ziyaretci_defteri.php", "a");

    $zaman = date('Y-m-d H:i:s');
    $giris_bilgisi = $_SESSION['ad'] . " " . $_SESSION['soyad'] . " siteye giriş yapmıştır. Tarih: " . $zaman . "\n";
    fwrite($ziyaretci_defteri, $giris_bilgisi);

    fclose($ziyaretci_defteri);
} else {
    echo "Hatalı kullanıcı adı veya şifre girdiniz";

    header("refresh: 3; url=kullanici_giris_ekrani.php");
}
