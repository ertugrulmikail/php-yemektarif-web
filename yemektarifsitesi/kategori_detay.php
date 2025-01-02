<?php
// Veritabanı bağlantısı
include("baglanti.php");

// Kategori id'sini al
if (isset($_GET['id'])) {
    $kategori_id = (int) $_GET['id'];

    // Kategori bilgilerini veritabanından al
    $sql_kategori = "SELECT * FROM kategoriler WHERE id = $kategori_id";
    $result_kategori = mysqli_query($baglanti, $sql_kategori);

    if (mysqli_num_rows($result_kategori) > 0) {
        $row_kategori = mysqli_fetch_assoc($result_kategori);
        $kategori_adi = $row_kategori['kategori_adi'];
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($kategori_adi); ?></title>
            <!-- Bootstrap CSS -->
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <!-- Font Awesome CSS -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
            <style>
                .card-img-top {
                    max-width: 100%;
                    height: 200px;
                    object-fit: cover;
                }
            </style>
        </head>

        <body>

            <?php include("navbar_kontrol.php"); ?>

            <div class="container mt-3">
                <h2 class="text-center mb-4"><?php echo htmlspecialchars($kategori_adi); ?></h2>

                <div class="row">
                    <?php
                    // Kategoriye ait tarifleri çek
                    $sql_tarifler = "SELECT t.*, u.kullanici_adi, u.profil_resmi, COUNT(d.tarif_id) AS defter_sayisi 
                                     FROM tarifler t 
                                     LEFT JOIN defterler d ON t.id = d.tarif_id 
                                     INNER JOIN users u ON t.kullanici_id = u.id 
                                     WHERE t.durum = 'Onaylandı' AND t.kategori_id = $kategori_id 
                                     GROUP BY t.id";
                    $result_tarifler = mysqli_query($baglanti, $sql_tarifler);

                    if (mysqli_num_rows($result_tarifler) > 0) {
                        while ($row_tarif = mysqli_fetch_assoc($result_tarifler)) {
                    ?>
                            <div class="col-md-3 my-2">
                                <div class="card">
                                    <a href="tarif_detay.php?id=<?php echo $row_tarif['id']; ?>" class="text-decoration-none text-dark">
                                        <img src="<?php echo htmlspecialchars($row_tarif['tarif_gorseli']); ?>" class="card-img-top" alt="...">
                                    </a>
                                    <div class="card-body">
                                        <a href="tarif_detay.php?id=<?php echo $row_tarif['id']; ?>" class="text-decoration-none text-dark">
                                            <h4 class="card-title"><?php echo htmlspecialchars($row_tarif['tarif_adi']); ?></h4>
                                        </a>
                                        <p class="card-text" style="font-size: 14px;"><?php echo htmlspecialchars($row_tarif['kisi_sayisi']); ?> kişilik</p>
                                        <div class="d-flex">
                                            <p class="card-text mr-1" style="font-size: 14px;"><?php echo htmlspecialchars($row_tarif['hazirlanma_suresi']); ?> dk Hazırlık,</p>
                                            <p class="card-text" style="font-size: 14px;"><?php echo htmlspecialchars($row_tarif['pisirme_suresi']); ?> dk Pişirme</p>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars($row_tarif['profil_resmi']); ?>" alt="<?php echo htmlspecialchars($row_tarif['kullanici_adi']); ?>" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                            <span style="font-size: 12px;"><?php echo (mb_strlen($row_tarif['kullanici_adi']) > 12) ? htmlspecialchars(mb_substr($row_tarif['kullanici_adi'], 0, 12)) . '...' : htmlspecialchars($row_tarif['kullanici_adi']); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="defter_ekle_kaldir.php?id=<?php echo $row_tarif['id']; ?>&kategori_id=<?php echo $kategori_id; ?>" class="text-decoration-none"><i id="defterIkon" class="fa-solid fa-book" style="color: grey; margin-right: 5px;"></i></a>
                                            <span style="margin-right: 10px;"><?php echo htmlspecialchars($row_tarif['defter_sayisi']); ?></span>
                                            <i class="fa-solid fa-comment text-decoration-none" style="color: LightBlue; margin-right: 5px;"></i>
                                            <?php
                                            // Yorum sayısını göster
                                            $tarif_id = $row_tarif['id'];
                                            $sql_yorum_sayisi = "SELECT COUNT(*) AS yorum_sayisi FROM yorumlar WHERE tarif_id = $tarif_id";
                                            $result_yorum_sayisi = mysqli_query($baglanti, $sql_yorum_sayisi);
                                            $row_yorum_sayisi = mysqli_fetch_assoc($result_yorum_sayisi);
                                            $yorum_sayisi = $row_yorum_sayisi['yorum_sayisi'];
                                            ?>
                                            <span><?php echo htmlspecialchars($yorum_sayisi); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<div class='col'>Bu kategoriye ait tarif bulunamadı.</div>";
                    }

                    ?>
                </div>
            </div>

            <!-- Bootstrap JS ve diğer scriptler -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        </body>

        </html>
<?php
    } else {
        echo "Kategori bulunamadı.";
    }
} else {
    echo "Geçersiz istek.";
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>