<?php
session_start(); // Kullanıcı oturumunu başlat

include("baglanti.php");

// Kullanıcı oturumundan kullanıcı ID'sini al
$kullanici_id = $_SESSION['kullanici_id'];

// Formdan gelen verileri al
$tarifAdi = mysqli_real_escape_string($baglanti, $_POST['tarifAdi']);
$malzemeler = mysqli_real_escape_string($baglanti, $_POST['malzemeler']);
$tarifHazirlanisi = mysqli_real_escape_string($baglanti, $_POST['tarifHazirlanisi']);
$tarifKategori = mysqli_real_escape_string($baglanti, $_POST['tarifKategori']);
$kisiSayisi = mysqli_real_escape_string($baglanti, $_POST['kisiSayisi']);
$hazirlanmaSuresi = mysqli_real_escape_string($baglanti, $_POST['hazirlanmaSuresi']);
$pisirmeSuresi = mysqli_real_escape_string($baglanti, $_POST['pisirmeSuresi']);

// Formun boş alanlarını kontrol et
if (empty($tarifAdi) || empty($malzemeler) || empty($tarifHazirlanisi) || empty($tarifKategori) || empty($kisiSayisi) || empty($hazirlanmaSuresi) || empty($pisirmeSuresi)) {
    // Eğer bir veya daha fazla alan boşsa, hata mesajı göster ve geri dön
    echo "Lütfen tüm alanları doldurun.";
    header("refresh:2; url=tarif_gonder.php"); // 2 saniye sonra önceki sayfaya yönlendir
    exit;
}

// Görsel dosyasının yüklenip yüklenmediğini kontrol et
if ($_FILES['tarifGorsel']['error'] == UPLOAD_ERR_NO_FILE) {
    // Eğer görsel yüklenmediyse, hata mesajı göster ve geri dön
    echo "Lütfen bir görsel seçin.";
    header("refresh:2; url=tarif_gonder.php"); // 2 saniye sonra önceki sayfaya yönlendir
    exit;
}

// Görsel dosyasının boyutunu kontrol et
$maxDosyaBoyutu = 5 * 1024 * 1024; // 5 MB
if ($_FILES['tarifGorsel']['size'] > $maxDosyaBoyutu) {
    // Eğer dosya boyutu izin verilen sınırı aşıyorsa, hata mesajı göster ve geri dön
    echo "Görsel dosyası çok büyük. Lütfen daha küçük bir dosya seçin.";
    header("refresh:2; url=tarif_gonder.php"); // 2 saniye sonra önceki sayfaya yönlendir
    exit;
}

// Görsel dosyasının türünü kontrol et
$izinVerilenTurler = array('image/jpeg', 'image/png', 'image/gif');
if (!in_array($_FILES['tarifGorsel']['type'], $izinVerilenTurler)) {
    // Eğer dosya türü izin verilen türlerden biri değilse, hata mesajı göster ve geri dön
    echo "Geçersiz dosya türü. Lütfen JPEG, PNG veya GIF formatında bir dosya seçin.";
    header("refresh:2; url=tarif_gonder.php"); // 2 saniye sonra önceki sayfaya yönlendir
    exit;
}

// Görsel dosyasını belirtilen klasöre kaydet
$hedefKlasor = 'Kullanici_Tarif_Resimleri/';
$hedefDosya = $hedefKlasor . basename($_FILES['tarifGorsel']['name']);

if (move_uploaded_file($_FILES['tarifGorsel']['tmp_name'], $hedefDosya)) {
    // Dosya başarıyla yüklendi, şimdi veritabanına ekleme işlemini yapalım

    // Veritabanına ekleme işlemi
    $sql = "INSERT INTO tarifler (kullanici_id, kategori_id, tarif_adi, malzemeler, hazirlanisi, kisi_sayisi, hazirlanma_suresi, pisirme_suresi, tarif_gorseli) 
            VALUES ('$kullanici_id', '$tarifKategori', '$tarifAdi', '$malzemeler', '$tarifHazirlanisi', '$kisiSayisi', '$hazirlanmaSuresi', '$pisirmeSuresi', '$hedefDosya')";

    if (mysqli_query($baglanti, $sql)) {
        echo "Tarif başarıyla gönderildi.";
        header("refresh:2; url=profil.php?id=1");
    } else {
        echo "Hata: " . $sql . "<br>" . mysqli_error($baglanti);
    }
} else {
    echo "Dosya yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
    header("refresh:2; url=tarif_gonder.php"); // 2 saniye sonra önceki sayfaya yönlendir
    exit;
}

// Bağlantıyı kapat
mysqli_close($baglanti);
?>
