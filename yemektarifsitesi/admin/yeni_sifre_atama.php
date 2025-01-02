<?php
include("../baglanti.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$email = $_SESSION['email'];
$yeni_sifre = $_POST['yenisifre'];
$yeni_sifre_dogrula = $_POST['yenisifredogrula'];

// Yeni şifrenin minimum uzunluğunu kontrol et
if (strlen($yeni_sifre) < 5) {
    echo "Yeni şifre en az 5 karakter olmalıdır.";
} elseif ($yeni_sifre != $yeni_sifre_dogrula) {
    echo "Girilen yeni şifreler eşleşmiyor, lütfen aynı şifreyi iki kez girin.";
} else {
    $query_yeni_sifre = mysqli_query($baglanti,"UPDATE users SET sifre = '$yeni_sifre' WHERE email = '$email'");

    if ($query_yeni_sifre) {
        echo "Şifre sıfırlama işlemi başarılı";
        session_unset();
        session_destroy();
        header("Location: anasayfa.php");
    } else {
        echo "Şifre değiştirme işlemi sırasında bir hata oluştu";
    }
}
?>