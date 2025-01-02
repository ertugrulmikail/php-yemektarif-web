<?php
// Veritabanı bağlantısı ve oturum kontrolü
include("baglanti.php");
session_start();

// Kullanıcı oturumda mı kontrol edelim
if (isset($_SESSION['kullanici_id'])) {
    // Kullanıcı oturumda ise, gerekli değişkenleri alalım
    $kullanici_id = $_SESSION['kullanici_id'];
    $tarif_id = (int)$_GET['id'];
    $icon = isset($_GET['icon']) ? $_GET['icon'] : "";
    $kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;

    // SQL Injection önlemi için tarif_id ve kategori_id'yi kontrol edelim
    $tarif_id = mysqli_real_escape_string($baglanti, $tarif_id);
    $kategori_id = mysqli_real_escape_string($baglanti, $kategori_id);

    // Kullanıcının kendi tarifini defterine eklemeye çalıştığını kontrol etme
    $sql_kendi_tarifi = "SELECT * FROM tarifler WHERE id = '$tarif_id' AND kullanici_id = '$kullanici_id'";
    $result_kendi_tarifi = mysqli_query($baglanti, $sql_kendi_tarifi);

    if (mysqli_num_rows($result_kendi_tarifi) > 0) {
        // Kullanıcı kendi tarifini deftere eklemeye çalışıyor, hata mesajı göster
        echo "Kendi tarifinizi deftere ekleyemezsiniz.";
        if ($kategori_id > 0) {
            header("refresh:1; url=kategori_detay.php?id=" . $kategori_id);
        } else {
            header("refresh:1; url=index.php");
        }
        exit;
    } else {
        // Kullanıcının tarifini kontrol etme
        $sql_tarif_kontrol = "SELECT * FROM defterler WHERE tarif_id = '$tarif_id' AND kullanici_id = '$kullanici_id'";
        $result_tarif_kontrol = mysqli_query($baglanti, $sql_tarif_kontrol);

        if ($result_tarif_kontrol) {
            if (mysqli_num_rows($result_tarif_kontrol) > 0) {
                // Kullanıcının defterinde bu tarif zaten ekli, o zaman bu değeri sil
                $sql_sil = "DELETE FROM defterler WHERE tarif_id = '$tarif_id' AND kullanici_id = '$kullanici_id'";
                if (mysqli_query($baglanti, $sql_sil)) {
                    echo "Tarif başarıyla defterden kaldırıldı.";
                    if ($icon == "trash") {
                        header("refresh:1; url=profil.php?id=2");
                    } else if ($kategori_id > 0) {
                        header("refresh:1; url=kategori_detay.php?id=" . $kategori_id);
                    } else {
                        header("refresh:1; url=index.php");
                    }
                    exit;
                } else {
                    echo "Hata: " . $sql_sil . "<br>" . mysqli_error($baglanti);
                }
            } else {
                // Kullanıcının defterinde bu tarif yok, ekleme işlemi yap
                $sql_ekle = "INSERT INTO defterler (tarif_id, kullanici_id) VALUES ('$tarif_id', '$kullanici_id')";

                // Ekleme işlemini gerçekleştirme
                if (mysqli_query($baglanti, $sql_ekle)) {
                    echo "Tarif başarıyla deftere eklendi.";
                    if ($kategori_id > 0) {
                        header("refresh:1; url=kategori_detay.php?id=" . $kategori_id);
                    } else {
                        header("refresh:1; url=index.php");
                    }
                    exit;
                } else {
                    echo "Hata: " . $sql_ekle . "<br>" . mysqli_error($baglanti);
                }
            }
        } else {
            echo "Hata: " . $sql_tarif_kontrol . "<br>" . mysqli_error($baglanti);
        }
    }

    // Veritabanı bağlantısını kapatma
    mysqli_close($baglanti);
} else {
    // Kullanıcı oturumda değilse, bir hata mesajı gösterilebilir veya başka bir işlem yapılabilir
    header("Location: kullanici_giris_ekrani.php");
    exit;
}
