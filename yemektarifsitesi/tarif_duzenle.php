<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Düzenle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        /* Önizleme görüntülerinin stilini düzenle */
        #imagePreview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .previewImage {
            max-width: 200px;
            /* Maksimum genişlik */
            max-height: 200px;
            /* Maksimum yükseklik */
            object-fit: cover;
            /* Görüntünün karesinin içine sığması için */
            border: 1px solid #ddd;
            /* Sınır ekle */
            border-radius: 5px;
            /* Köşeleri yuvarla */
        }
    </style>
</head>

<body>
    <?php
    include('navbar_kontrol.php');

    // Oturum kontrolü
    if (isset($_SESSION['kullanici_id'])) {
        // Oturum bilgilerini al
        $ad = $_SESSION['ad'];
        $soyad = $_SESSION['soyad'];
        $email = $_SESSION['email'];
        $kullaniciadi = $_SESSION['kullanici_adi'];
        $profilResmi = $_SESSION['profil_resmi'];

        // Tarif ID'sini al
        if (isset($_GET['id'])) {
            $tarif_id = $_GET['id'];

            // Veritabanı bağlantısını yap
            include('baglanti.php');

            // Tarifi çekme sorgusu
            $sql = "SELECT * FROM tarifler WHERE id = $tarif_id";
            $result = $baglanti->query($sql);

            // Tarif bulunamazsa hata mesajı göster
            if ($result->num_rows == 0) {
                echo "Tarif bulunamadı.";
                exit;
            }

            $tarif = $result->fetch_assoc();
        } else {
            echo "Tarif ID'si belirtilmemiş.";
            exit;
        }

        // Form gönderildiğinde
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Form verilerini al
            $tarif_id = $_POST['tarif_id'];
            $tarif_adi = $_POST['tarifAdi'];
            $malzemeler = $_POST['malzemeler'];
            $tarif_hazirlanisi = $_POST['tarifHazirlanisi'];
            $kisi_sayisi = $_POST['kisiSayisi'];
            $hazirlanma_suresi = $_POST['hazirlanmaSuresi'];
            $pisirme_suresi = $_POST['pisirmeSuresi'];
            $tarif_gorseli = $tarif['tarif_gorseli']; // Önceki görseli alıyoruz, yeni görseli yüklenene kadar
            $kategori_id = $_POST['tarifKategori'];

            // Görsel yükleme işlemi
            if (!empty($_FILES['tarifGorsel']['name'])) {
                // Görsel yükleme işlemi
                $uploads_dir = 'uploads/';
                $tmp_name = $_FILES['tarifGorsel']['tmp_name'];
                $name = $_FILES['tarifGorsel']['name'];
                $tarif_gorseli = "$uploads_dir$name";
                move_uploaded_file($tmp_name, "$uploads_dir/$name");
            }

            // Veritabanı güncelleme sorgusu
            $sql_update = "UPDATE tarifler SET tarif_adi = ?, malzemeler = ?, hazirlanisi = ?, kisi_sayisi = ?, hazirlanma_suresi = ?, pisirme_suresi = ?, tarif_gorseli = ?, kategori_id = ? WHERE id = ?";
            $stmt = $baglanti->prepare($sql_update);
            $stmt->bind_param("ssssssssi", $tarif_adi, $malzemeler, $tarif_hazirlanisi, $kisi_sayisi, $hazirlanma_suresi, $pisirme_suresi, $tarif_gorseli, $kategori_id, $tarif_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo '<div class="alert alert-success mt-3" role="alert">Tarif başarıyla güncellendi.</div>';
            } else {
                echo '<div class="alert alert-danger mt-3" role="alert">Tarif güncellenirken bir hata oluştu.</div>';
            }

            // Bağlantıyı kapat
            $stmt->close();
            $baglanti->close();
        }
    } else {
        // Kullanıcı oturumu yoksa giriş sayfasına yönlendir
        header("Location: index.php");
        exit;
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Tarifi Düzenle</h2>
                    </div>
                    <div class="card-body">
                        <!-- Form başlangıcı -->
                        <form method="post" action="tarif_duzenle_kontrol.php" enctype="multipart/form-data">
                            <!-- Tarif ID -->
                            <input type="hidden" name="tarif_id" value="<?php echo $tarif_id; ?>">
                            <!-- Tarif Kategorisi Seçimi -->
                            <div class="form-group">
                                <label for="tarifKategori">Tarif Kategorisi</label>
                                <select class="form-control" id="tarifKategori" name="tarifKategori" required>
                                    <?php
                                    $sql = "SELECT id, kategori_adi FROM kategoriler";
                                    $result = $baglanti->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $selected = ($row["id"] == $tarif['kategori_id']) ? 'selected' : '';
                                            echo "<option value='" . $row["id"] . "' $selected>" . $row["kategori_adi"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Kategori bulunamadı</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Tarif adı -->
                            <div class="form-group">
                                <label for="tarifAdi">Tarif Adı</label>
                                <input type="text" class="form-control" id="tarifAdi" name="tarifAdi" value="<?php echo $tarif['tarif_adi']; ?>" required>
                            </div>
                            <!-- Malzemeler -->
                            <div class="form-group">
                                <label for="malzemeler">Malzemeler</label>
                                <textarea class="form-control" id="malzemeler" name="malzemeler" rows="3" required><?php echo $tarif['malzemeler']; ?></textarea>
                            </div>
                            <!-- Tarifin Hazırlanışı -->
                            <div class="form-group">
                                <label for="tarifHazirlanisi">Tarifin Hazırlanışı</label>
                                <textarea class="form-control" id="tarifHazirlanisi" name="tarifHazirlanisi" rows="5" required><?php echo $tarif['hazirlanisi']; ?></textarea>
                            </div>

                            <!-- Kişi Sayısı -->
                            <div class="form-group">
                                <label for="kisiSayisi">Kişi Sayısı</label>
                                <input type="text" class="form-control" id="kisiSayisi" name="kisiSayisi" value="<?php echo $tarif['kisi_sayisi']; ?>" required>
                            </div>

                            <!-- Hazırlanma Süresi -->
                            <div class="form-group">
                                <label for="hazirlanmaSuresi">Hazırlanma Süresi</label>
                                <select class="form-control" id="hazirlanmaSuresi" name="hazirlanmaSuresi" required>
                                    <option value="">Seçiniz</option>
                                    <?php
                                    $hazirlanma_sureleri = array("5", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55", "60");
                                    foreach ($hazirlanma_sureleri as $sure) {
                                        echo "<option value='$sure'";
                                        if ($sure == $tarif['hazirlanma_suresi']) {
                                            echo " selected";
                                        }
                                        echo ">$sure dk</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Pişirme Süresi -->
                            <div class="form-group">
                                <label for="pisirmeSuresi">Pişirme Süresi</label>
                                <select class="form-control" id="pisirmeSuresi" name="pisirmeSuresi" required>
                                    <option value="">Seçiniz</option>
                                    <?php
                                    $pisirme_sureleri = array("0", "5", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55", "60");
                                    foreach ($pisirme_sureleri as $sure) {
                                        echo "<option value='$sure'";
                                        if ($sure == $tarif['pisirme_suresi']) {
                                            echo " selected";
                                        }
                                        echo ">$sure dk</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Görsel Ekleme -->
                            <div class="form-group">
                                <label for="tarifGorsel">Tarif Fotoğrafı Ekle</label>
                                <input type="file" class="form-control-file" id="tarifGorsel" name="tarifGorsel" accept="image/*" onchange="previewImage(this)">
                                <div id="imagePreview" class="mt-2" style="display: flex; justify-content: start; align-items: center;">
                                    <?php if (!empty($tarif['tarif_gorseli'])) : ?>
                                        <div style="display: flex; align-items: center;">
                                            <img src="<?php echo $tarif['tarif_gorseli']; ?>" class="previewImage mt-2" alt="Tarif Görseli" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px;">
                                            <button type="button" class="btn btn-danger mt-2 ml-2" onclick="cancelPreview()" style="min-width: 80px; height: 38px;">İptal Et</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Gönder Butonu -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Gönder</button>
                            </div>

                            <!-- JavaScript ve Bootstrap CDN -->
                            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
                            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-xNbOkY5guoUit72iEWyk3O8eRY0O9CY8S0xDXaA9h4A/0mx2aHoA7jNZq5zR9/d9" crossorigin="anonymous"></script>
                            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shCk+pcy1ELspw0p0c8z5+3z3FE/lvqvo0F55" crossorigin="anonymous"></script>
                            <script>
                                // Görsel önizleme fonksiyonu
                                function previewImage(input) {
                                    var imagePreview = document.getElementById('imagePreview');
                                    imagePreview.innerHTML = '';

                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();

                                        reader.onload = function(event) {
                                            var imgElement = document.createElement('img');
                                            imgElement.src = event.target.result;
                                            imgElement.className = 'previewImage'; // Önizleme görüntüsüne sınıf ekleyelim
                                            imagePreview.appendChild(imgElement);

                                            var cancelButton = document.createElement('button');
                                            cancelButton.className = 'btn btn-danger mt-2';
                                            cancelButton.textContent = 'İptal Et';
                                            cancelButton.onclick = function() {
                                                cancelPreview();
                                            };
                                            imagePreview.appendChild(cancelButton);
                                        }

                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }

                                // Önizlemeyi iptal etme fonksiyonu
                                function cancelPreview() {
                                    var imagePreview = document.getElementById('imagePreview');
                                    imagePreview.innerHTML = '';

                                    var input = document.getElementById('tarifGorsel');
                                    input.value = ''; // Dosya seçme inputunu sıfırla

                                    <?php if (!empty($tarif['tarif_gorseli'])) : ?>
                                        var imgElement = document.createElement('img');
                                        imgElement.src = '<?php echo $tarif['tarif_gorseli']; ?>';
                                        imgElement.className = 'previewImage'; // Önizleme görüntüsüne sınıf ekleyelim
                                        imagePreview.appendChild(imgElement);

                                        var cancelButton = document.createElement('button');
                                        cancelButton.className = 'btn btn-danger mt-2';
                                        cancelButton.textContent = 'İptal Et';
                                        cancelButton.onclick = function() {
                                            cancelPreview();
                                        };
                                        imagePreview.appendChild(cancelButton);
                                    <?php endif; ?>
                                }
                            </script>

</body>

</html>