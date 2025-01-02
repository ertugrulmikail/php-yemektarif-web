<?php
session_start();
include("baglanti.php");

// Kullanıcıdan gelen doğrulama kodunu al
$dogrulamaKodu = $_POST['dogrulamaKodu'];

// Kullanıcının doğrulama kodunu kontrol etmek için kaydedilmiş doğrulama kodunu al
$email = $_SESSION['email'];
$query = mysqli_query($baglanti, "SELECT * FROM dogrulama_kodlari WHERE email='$email' ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_assoc($query);
$dogrulamaKoduKayitli = $row['dogrulama_kodu'];

// Kullanıcının girdiği doğrulama kodunu ve kaydedilmiş doğrulama kodunu karşılaştır
if ($dogrulamaKodu == $dogrulamaKoduKayitli) {
    
    $deleteQuery = mysqli_query($baglanti, "DELETE FROM dogrulama_kodlari WHERE email='$email'");
    if (!$deleteQuery) {
        echo "Doğrulama kodu silinirken bir hata oluştu.";
    } else {
        echo "Doğrulama işlemi başarılı!";
        header("Location: yeni_sifre_alma.php");
    }
} else {
    // Doğrulama kodları eşleşmezse, hata mesajı göster
    echo "Doğrulama kodu hatalı. Lütfen tekrar deneyin.";
}
