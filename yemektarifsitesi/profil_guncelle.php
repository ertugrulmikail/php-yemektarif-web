<?php
session_start();

if (isset($_SESSION['kullanici_id'])) {
    include("baglanti.php");

    // Kullanıcı bilgilerini al
    $ad = mysqli_real_escape_string($baglanti, $_POST['ad']);
    $soyad = mysqli_real_escape_string($baglanti, $_POST['soyad']);
    $email = mysqli_real_escape_string($baglanti, $_POST['email']);
    $kullaniciadi = mysqli_real_escape_string($baglanti, $_POST['kullaniciadi']);

    // Veri doğrulama
    if (mb_strlen($ad) < 3 || mb_strlen($ad) > 15) {
        exit();
    } elseif (mb_strlen($soyad) < 3 || mb_strlen($soyad) > 15) {
        exit();
    } elseif (mb_strlen($kullaniciadi) < 5 || mb_strlen($kullaniciadi) > 25) {
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 25) {
        exit();
    } else {
        // Resim yükleme işlemi
        if (!empty($_FILES['profilResmiYeni']['name'])) {
            $resimDosyaAdi = $_FILES['profilResmiYeni']['name'];
            $resimGeciciAd = $_FILES['profilResmiYeni']['tmp_name'];
            $resimHedef = 'Kullanici_Profil_Resimleri/' . $resimDosyaAdi; // Kaydedilecek dosya yolunu belirleyin

            $dosyaUzantisi = pathinfo($resimHedef, PATHINFO_EXTENSION);
            $izinVerilenUzantilar = array('jpg', 'jpeg', 'png', 'gif');

            if (!in_array(strtolower($dosyaUzantisi), $izinVerilenUzantilar)) {
                echo "Yalnızca JPG, JPEG, PNG ve GIF dosyaları yüklenebilir.";
                exit;
            }

            if (move_uploaded_file($resimGeciciAd, $resimHedef)) {
                // Resim başarıyla yüklendi, veritabanında güncelle
                $update_query = "UPDATE demob.users SET ad=?, soyad=?, email=?, kullanici_adi=?, profil_resmi=? WHERE id=?";
                $stmt = mysqli_prepare($baglanti, $update_query);
                mysqli_stmt_bind_param($stmt, "sssssi", $ad, $soyad, $email, $kullaniciadi, $resimHedef, $_SESSION['kullanici_id']);
                $update_result = mysqli_stmt_execute($stmt);

                if ($update_result) {
                    // Session bilgilerini güncelle
                    $_SESSION['ad'] = $ad;
                    $_SESSION['soyad'] = $soyad;
                    $_SESSION['email'] = $email;
                    $_SESSION['kullanici_adi'] = $kullaniciadi;
                    $_SESSION['profil_resmi'] = $resimHedef;

                    echo "Profil bilgileri ve resim başarıyla güncellendi.";
                    header("refresh: 1; url=profil.php");
                } else {
                    echo "Profil bilgileri güncellenirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Resim yükleme hatası.";
            }
        } else {
            // Eğer kullanıcı yeni bir resim yüklemediyse, önceki resmi kullanarak güncelle
            $update_query = "UPDATE demob.users SET ad=?, soyad=?, email=?, kullanici_adi=? WHERE id=?";
            $stmt = mysqli_prepare($baglanti, $update_query);
            mysqli_stmt_bind_param($stmt, "ssssi", $ad, $soyad, $email, $kullaniciadi, $_SESSION['kullanici_id']);
            $update_result = mysqli_stmt_execute($stmt);

            if ($update_result) {
                // Session bilgilerini güncelle
                $_SESSION['ad'] = $ad;
                $_SESSION['soyad'] = $soyad;
                $_SESSION['email'] = $email;
                $_SESSION['kullanici_adi'] = $kullaniciadi;

                echo "Profil bilgileri güncellendi. Yeni resim yüklenmediği için mevcut profil resmi kullanılmaya devam edilecek.";
                header("refresh: 1; url=profil.php");
            } else {
                echo "Profil bilgileri güncellenirken bir hata oluştu.";
            }
            mysqli_stmt_close($stmt);
        }
    }
} else {
    header("Location: kullanici_giris_ekrani.php");
    exit;
}
