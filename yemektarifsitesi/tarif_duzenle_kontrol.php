<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    // Kullanıcı oturumu yoksa giriş sayfasına yönlendir
    header("Location: kullanici_giris_ekrani.php");
    exit;
}

// Form verilerini al
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $tarif_id = $_POST['tarif_id'];
    $tarif_adi = $_POST['tarifAdi'];
    $malzemeler = $_POST['malzemeler'];
    $tarif_hazirlanisi = $_POST['tarifHazirlanisi'];
    $kisi_sayisi = $_POST['kisiSayisi'];
    $hazirlanma_suresi = $_POST['hazirlanmaSuresi'];
    $pisirme_suresi = $_POST['pisirmeSuresi'];
    $kategori_id = $_POST['tarifKategori'];

    // Veritabanı bağlantısını yap
    include('baglanti.php');

    // Mevcut değerleri al
    $sql_select = "SELECT tarif_adi, malzemeler, hazirlanisi, kisi_sayisi, hazirlanma_suresi, pisirme_suresi, tarif_gorseli, kategori_id FROM tarifler WHERE id = ?";
    $stmt_select = $baglanti->prepare($sql_select);
    $stmt_select->bind_param("i", $tarif_id);
    $stmt_select->execute();
    $stmt_select->bind_result($db_tarif_adi, $db_malzemeler, $db_hazirlanisi, $db_kisi_sayisi, $db_hazirlanma_suresi, $db_pisirme_suresi, $db_tarif_gorseli, $db_kategori_id);
    $stmt_select->fetch();
    $stmt_select->close();

    // Değişiklik olup olmadığını kontrol et
    if ($tarif_adi != $db_tarif_adi || $malzemeler != $db_malzemeler || $tarif_hazirlanisi != $db_hazirlanisi || $kisi_sayisi != $db_kisi_sayisi || $hazirlanma_suresi != $db_hazirlanma_suresi || $pisirme_suresi != $db_pisirme_suresi || $kategori_id != $db_kategori_id || !empty($_FILES['tarifGorsel']['name'])) {
        // Yeni görsel yükleme işlemi
        if (!empty($_FILES['tarifGorsel']['name'])) {
            $uploads_dir = 'Kullanici_Tarif_Resimleri/';
            $tmp_name = $_FILES['tarifGorsel']['tmp_name'];
            $name = $_FILES['tarifGorsel']['name'];
            $tarif_gorseli = "$uploads_dir$name";
            if (!move_uploaded_file($tmp_name, $tarif_gorseli)) {
                echo '<div class="alert alert-danger alert-dismissible mt-3" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        Dosya yükleme hatası.
                    </div>';
                // Dosya yükleme hatası durumunda yönlendirme
                header("refresh: 1; url=tarif_duzenle.php?id=" . $tarif_id);
                exit;
            }
        } else {
            $tarif_gorseli = $db_tarif_gorseli;
        }

        // Veritabanı güncelleme sorgusu
        $sql_update = "UPDATE tarifler SET tarif_adi = ?, malzemeler = ?, hazirlanisi = ?, kisi_sayisi = ?, hazirlanma_suresi = ?, pisirme_suresi = ?, tarif_gorseli = ?, kategori_id = ? WHERE id = ?";
        $stmt = $baglanti->prepare($sql_update);
        $stmt->bind_param("ssssssssi", $tarif_adi, $malzemeler, $tarif_hazirlanisi, $kisi_sayisi, $hazirlanma_suresi, $pisirme_suresi, $tarif_gorseli, $kategori_id, $tarif_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Durumu 'Beklemede' olarak güncelle
            $sql_update_durum = "UPDATE tarifler SET durum = 'Beklemede' WHERE id = ?";
            $stmt_durum = $baglanti->prepare($sql_update_durum);
            $stmt_durum->bind_param("i", $tarif_id);
            $stmt_durum->execute();
            $stmt_durum->close();

            header("refresh: 1; url=profil.php?id=1&alert=success");

        } else {
            header("refresh: 1; url=tarif_duzenle.php?id=" . $tarif_id);

            echo '<script type="text/javascript">
                alert("Tarifinizde herhangi bir değişiklik yapmadınız.");
                window.location.href = "tarif_duzenle.php?id=' . $tarif_id . '";
              </script>';
        }

        // Bağlantıyı kapat
        $stmt->close();
    } else {
        // Değişiklik yoksa yeniden yönlendirme
        echo '<script type="text/javascript">
                alert("Tarifinizde herhangi bir değişiklik yapmadınız.");
                window.location.href = "tarif_duzenle.php?id=' . $tarif_id . '";
              </script>';
    }

    $baglanti->close();
} else {
    echo 'Geçersiz istek.';
}
