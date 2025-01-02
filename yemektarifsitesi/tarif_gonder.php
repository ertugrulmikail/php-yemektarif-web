<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Ekle</title>
    <style>
        /* Önizleme görüntülerinin stilini düzenle */
        #imagePreviews {
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
    ?>

        <?php
        include('baglanti.php');

        // Kategorileri çekme sorgusu
        $sql = "SELECT id, kategori_adi FROM kategoriler";
        $result = $baglanti->query($sql);

        // Sorgu çalışmazsa hata mesajı göster
        if (!$result) {
            die("Sorgu hatası: " . $baglanti->error);
        }

        $options = "";
        if ($result->num_rows > 0) {
            // Veritabanından dönen her satır için option oluşturma
            while ($row = $result->fetch_assoc()) {
                $options .= "<option value='" . $row["id"] . "'>" . $row["kategori_adi"] . "</option>";
            }
        } else {
            $options = "<option value=''>Kategori bulunamadı</option>";
        }
        ?>

        <div class="container mt-3 mb-4">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Tarif Gönder</h2>
                        </div>
                        <div class="card-body">
                            <!-- Form başlangıcı -->
                            <form method="post" action="tarif_gonder_kontrol.php" enctype="multipart/form-data">
                                <!-- Tarif adı -->
                                <div class="mb-3">
                                    <label for="tarifAdi" class="form-label">Tarif Adı</label>
                                    <input type="text" class="form-control" id="tarifAdi" name="tarifAdi" required>
                                </div>
                                <!-- Malzemeler -->
                                <div class="mb-3">
                                    <label for="malzemeler" class="form-label">Malzemeler</label>
                                    <textarea class="form-control" id="malzemeler" name="malzemeler" rows="3" required></textarea>
                                </div>
                                <!-- Tarifin Hazırlanışı -->
                                <div class="mb-3">
                                    <label for="tarifHazirlanisi" class="form-label">Tarifin Hazırlanışı</label>
                                    <textarea class="form-control" id="tarifHazirlanisi" name="tarifHazirlanisi" rows="5" required></textarea>
                                </div>
                                <!-- Görsel Ekleme -->
                                <div class="mb-3">
                                    <label for="tarifGorsel" class="form-label">Tarif Fotoğrafı Ekle</label>
                                    <input type="file" class="form-control" id="tarifGorsel" name="tarifGorsel" onchange="previewImage(this)" accept="image/*">
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>
                                <!-- Tarif Kategorisi Seçimi -->
                                <div class="mb-3">
                                    <label for="tarifKategori" class="form-label">Tarif Kategorisi</label>
                                    <select class="form-control" id="tarifKategori" name="tarifKategori" required>
                                        <option value="">Seçiniz</option>
                                        <?php echo $options; ?>
                                    </select>
                                </div>

                                <!-- Kişi Sayısı -->
                                <div class="mb-3">
                                    <label for="kisiSayisi" class="form-label">Kaç Kişilik</label>
                                    <select class="form-control" id="kisiSayisi" name="kisiSayisi" required>
                                        <option value="">Seçiniz</option>
                                        <option value="1-2">1-2</option>
                                        <option value="3-4">3-4</option>
                                        <option value="5-6">5-6</option>
                                        <option value="7-8">7-8</option>
                                        <option value="9-10">9-10</option>
                                        <option value="11-12">11-12</option>
                                        <option value="12+">12+</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="hazirlanmaSuresi" class="form-label">Hazırlanma Süresi</label>
                                    <select class="form-control" id="hazirlanmaSuresi" name="hazirlanmaSuresi" required>
                                        <option value="">Seçiniz</option>
                                        <option value="5">5dk</option>
                                        <option value="10">10dk</option>
                                        <option value="15">15dk</option>
                                        <option value="20">20dk</option>
                                        <option value="25">25dk</option>
                                        <option value="30">30dk</option>
                                        <option value="35">35dk</option>
                                        <option value="40">40dk</option>
                                        <option value="45">45dk</option>
                                        <option value="50">50dk</option>
                                        <option value="55">55dk</option>
                                        <option value="60">1saat</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pisirmeSuresi" class="form-label">Pişirme Süresi</label>
                                    <select class="form-control" id="pisirmeSuresi" name="pisirmeSuresi" required>
                                        <option value="">Seçiniz</option>
                                        <option value="0">0dk</option>
                                        <option value="5">5dk</option>
                                        <option value="10">10dk</option>
                                        <option value="15">15dk</option>
                                        <option value="20">20dk</option>
                                        <option value="25">25dk</option>
                                        <option value="30">30dk</option>
                                        <option value="35">35dk</option>
                                        <option value="40">40dk</option>
                                        <option value="45">45dk</option>
                                        <option value="50">50dk</option>
                                        <option value="55">55dk</option>
                                        <option value="60">1saat</option>
                                    </select>
                                </div>

                                <!-- Gönder Butonu -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-block">Gönder</button>
                                </div>
                            </form>
                            <!-- Form bitişi -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    } else {
        // Kullanıcı oturumu yoksa giriş sayfasına yönlendir
        header("Location: kullanici_giris_ekrani.php");
        exit;
    }
    ?>

    <!-- JavaScript ve Bootstrap CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <script>
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
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>