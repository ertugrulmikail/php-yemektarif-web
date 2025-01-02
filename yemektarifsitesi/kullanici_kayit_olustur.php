<?php
include("baglanti.php");

$ad = mysqli_real_escape_string($baglanti, $_POST['ad']);
$soyad = mysqli_real_escape_string($baglanti, $_POST['soyad']);
$email = mysqli_real_escape_string($baglanti, $_POST['email']);
$kullaniciadi = mysqli_real_escape_string($baglanti, $_POST['kullaniciadi']);
$sifre = mysqli_real_escape_string($baglanti, $_POST['sifre']);

// Format kontrolü
if (mb_strlen($ad) < 3 || mb_strlen($ad) > 15) {
    exit();
} elseif (mb_strlen($soyad) < 3 || mb_strlen($soyad) > 15) {
    exit();
} elseif (mb_strlen($kullaniciadi) < 5 || mb_strlen($kullaniciadi) > 25) {
    exit();
} elseif (mb_strlen($sifre) < 5 || mb_strlen($sifre) > 25) {
    exit();
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 25) {
    exit();
} else {
    // Kullanıcı adı ve e-posta kontrolü
    $kullaniciadi_kontrol = mysqli_query($baglanti, "SELECT kullanici_adi FROM users WHERE kullanici_adi='$kullaniciadi'");
    $email_kontrol = mysqli_query($baglanti, "SELECT email FROM users WHERE email='$email'");

    $row_kullaniciadi = mysqli_num_rows($kullaniciadi_kontrol);
    $row_email = mysqli_num_rows($email_kontrol);

    if ($row_kullaniciadi == 1) {
        echo "Girmiş olduğunuz kullanıcı adına sahip biri bulunmaktadır.";
        exit();
    } elseif ($row_email == 1) {
        echo "Girmiş olduğunuz emaile sahip biri bulunmaktadır.";
        exit();
    } else {
        // Resim yükleme işlemi
        if (isset($_FILES['profilResmi']) && $_FILES['profilResmi']['error'] === UPLOAD_ERR_OK) {
            $resimDosyaAdi = $_FILES['profilResmi']['name'];
            $resimGeciciAd = $_FILES['profilResmi']['tmp_name'];
            $resimUzanti = pathinfo($resimDosyaAdi, PATHINFO_EXTENSION); // Dosya uzantısını al
            $resimAdi = uniqid() . '.' . $resimUzanti; // Benzersiz bir dosya adı oluştur
            $resimHedef = 'Kullanici_Profil_Resimleri/' . $resimAdi; // Kaydedilecek dosya yolunu belirle

            move_uploaded_file($resimGeciciAd, $resimHedef);
        } else {
            // Kullanıcı resim yüklemediyse, default olarak kullanılacak resmin dosya yolunu belirleyin
            $resimHedef = 'default_profil_resmi.png';
        }

        // Güvenli sorgu hazırlamak için prepared statement kullanımı
        $ekleme_sorgu = "INSERT INTO users(ad, soyad, email, kullanici_adi, sifre, profil_resmi) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($baglanti, $ekleme_sorgu);

        // Hata kontrolü
        if ($stmt === false) {
            echo "SQL sorgusu hazırlanırken bir hata oluştu.";
            exit();
        }

        // Parametreleri bağlama
        mysqli_stmt_bind_param($stmt, "ssssss", $ad, $soyad, $email, $kullaniciadi, $sifre, $resimHedef);

        // Sorguyu çalıştırma
        $ekleme = mysqli_stmt_execute($stmt);

        if ($ekleme) {
            echo "Ekleme işlemi başarılı", "<br>";
            header("refresh: 3; url=index.php");
            exit();
        } else {
            echo "Ekleme işlemi başarısız", "<br>";
            header("refresh: 3; url=kullanici_kayit_formu.php");
            exit();
        }

        mysqli_stmt_close($stmt);
    }
}
