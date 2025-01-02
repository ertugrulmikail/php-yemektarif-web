<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include("baglanti.php");

    date_default_timezone_set('Europe/Istanbul');
    $tarif_baslik = "Tarif Detay"; // Default başlık

    // Tarif ID'sini al
    if (isset($_GET['id'])) {
        $tarif_id = $_GET['id'];

        // Veritabanından tarifi çek
        $sql = "SELECT t.*, u.profil_resmi, u.kullanici_adi, u.ad, u.soyad FROM tarifler t JOIN users u ON t.kullanici_id = u.id WHERE t.id = '$tarif_id'";
        $result = mysqli_query($baglanti, $sql);

        // Veritabanı sorgusunda hata olup olmadığını kontrol et
        if (!$result) {
            die("Sorgu hatası: " . mysqli_error($baglanti));
        }

        // Sonucu al
        $tarif = mysqli_fetch_assoc($result);

        // Tarif bilgileri varsa başlığı güncelle
        if ($tarif) {
            $tarif_baslik = htmlspecialchars($tarif['tarif_adi']);
        }

        // Sonuç setini serbest bırak
        mysqli_free_result($result);

        // Yorum sayısını al
        $yorum_sayisi_sql = "SELECT COUNT(*) as toplam FROM yorumlar WHERE tarif_id = '$tarif_id'";
        $yorum_sayisi_result = mysqli_query($baglanti, $yorum_sayisi_sql);
        $yorum_sayisi_row = mysqli_fetch_assoc($yorum_sayisi_result);
        $yorum_sayisi = $yorum_sayisi_row['toplam'];

        // Sonuç setini serbest bırak
        mysqli_free_result($yorum_sayisi_result);
    } else {
        echo "<div class='container mt-3'><p>Geçersiz tarif ID.</p></div>";
    }

    // Bağlantıyı kapat
    mysqli_close($baglanti);
    ?>
    <title><?php echo $tarif_baslik; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        .tarif-detay {
            margin-top: 30px;
        }

        .tarif-baslik {
            font-size: 2rem;
            font-weight: bold;
        }

        .tarif-gorsel {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
        }

        .tarif-sure {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        .tarif-hazirlanisi {
            font-size: 1rem;
            line-height: 1.6;
        }

        .detay-kutu {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            word-wrap: break-word;
        }

        .kullanici-bilgi {
            display: flex;
            align-items: center;
            margin-top: 15px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 8px;
        }

        .kullanici-bilgi img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .kullanici-bilgi .isim {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .kullanici-bilgi small {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .yorum-bilgi {
            display: flex;
            align-items: flex-start;
            margin-top: 15px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 8px;
        }

        .yorum-bilgi img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .yorum-detay {
            flex: 1;
        }

        .yorum-detay .isim {
            font-size: 1rem;
            font-weight: bold;
        }

        .yorum-detay .zaman {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .yorum-detay p {
            margin: 0;
        }
    </style>
</head>

<body>
    <?php
    include("navbar_kontrol.php");

    if (isset($tarif)) {
    ?>
        <div class="container tarif-detay">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="detay-kutu">
                        <h1 class="tarif-baslik"><?php echo htmlspecialchars($tarif['tarif_adi']); ?></h1>
                        <img src="<?php echo htmlspecialchars($tarif['tarif_gorseli']); ?>" class="tarif-gorsel mt-3 mb-3" style="max-width: 100%; height: 600px; object-fit: cover;" alt="Tarif Görseli">
                        <div class="kullanici-bilgi">
                            <img src="<?php echo htmlspecialchars($tarif['profil_resmi']); ?>" alt="Profil Fotoğrafı">
                            <div>
                                <div class="isim"><?php echo htmlspecialchars($tarif['ad']) . " " . htmlspecialchars($tarif['soyad']); ?></div>
                                <small><?php echo htmlspecialchars("@" . $tarif['kullanici_adi']); ?></small>
                            </div>
                        </div>
                        <div class="tarif-sure">
                            <strong>Hazırlama Süresi:</strong> <?php echo htmlspecialchars($tarif['hazirlanma_suresi']); ?> dk
                        </div>
                        <div class="tarif-sure">
                            <strong>Pişirme Süresi:</strong> <?php echo htmlspecialchars($tarif['pisirme_suresi']); ?> dk
                        </div>
                        <div class="tarif-sure">
                            <strong>Malzemeler:</strong> <?php echo htmlspecialchars($tarif['malzemeler']); ?>
                        </div>
                        <div class="hazirlanisi mt-4">
                            <h4>Hazırlanışı:</h4>
                            <p><?php echo nl2br(htmlspecialchars($tarif['hazirlanisi'])); ?></p>
                        </div>
                    </div>

                    <div class="container yorumlar mt-5">
                        <h4>Yorumlar<?php if ($yorum_sayisi > 0) {
                                        echo " ($yorum_sayisi)";
                                    } ?></h4>
                        <?php
                        include("baglanti.php"); // Bağlantıyı yeniden aç

                        $yorum_sql = "SELECT y.*, u.kullanici_adi, u.profil_resmi FROM yorumlar y JOIN users u ON y.kullanici_id = u.id WHERE y.tarif_id = '$tarif_id' ORDER BY y.id DESC";
                        $yorum_result = mysqli_query($baglanti, $yorum_sql);

                        if (mysqli_num_rows($yorum_result) > 0) {
                            while ($yorum = mysqli_fetch_assoc($yorum_result)) {
                                $yorum_metni = trim($yorum['yorum_metni']);
                                $yorum_tarihi = new DateTime($yorum['olusturulma_tarihi']);
                                $simdi = new DateTime();
                                $fark = $simdi->diff($yorum_tarihi);

                                if ($fark->y > 0) {
                                    $zaman_etiketi = $fark->y . " yıl önce";
                                } elseif ($fark->m > 0) {
                                    $zaman_etiketi = $fark->m . " ay önce";
                                } elseif ($fark->d > 0) {
                                    $zaman_etiketi = $fark->d . " gün önce";
                                } elseif ($fark->h > 0) {
                                    $zaman_etiketi = $fark->h . " saat önce";
                                } elseif ($fark->i > 0) {
                                    $zaman_etiketi = $fark->i . " dakika önce";
                                } else {
                                    $zaman_etiketi = "az önce";
                                }

                                echo "<div class='yorum-bilgi'><img src='" . htmlspecialchars($yorum['profil_resmi']) . "' alt='Profil Fotoğrafı'><div class='yorum-detay'><div class='isim'>" . htmlspecialchars($yorum['kullanici_adi']) . ": <span class='yorum-metni' style='font-weight: normal;'> " . htmlspecialchars($yorum_metni) . "</span></div><div class='zaman'>$zaman_etiketi</div></div></div>";
                            }
                        } else {
                            echo "<p>Henüz yorum yapılmamış.</p>";
                        }

                        mysqli_free_result($yorum_result);
                        mysqli_close($baglanti);
                        ?>
                        <?php
                        if (isset($_SESSION['kullanici_id'])) {
                        ?>
                            <div class="yorum-yap mt-4">
                                <h5>Yorum Yap</h5>
                                <form action="yorum_ekle.php" method="POST">
                                    <div class="form-group">
                                        <textarea class="form-control" name="yorum_metni" rows="3" required></textarea>
                                    </div>
                                    <input type="hidden" name="tarif_id" value="<?php echo $tarif_id; ?>">
                                    <button type="submit" class="btn btn-primary float-right mb-5">Gönder</button>
                                </form>
                            </div>
                        <?php
                        } else {
                            echo "<p class='mt-3'>Yorum yapabilmek için <a href='kullanici_giris_ekrani.php'>giriş yapın</a>.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    <?php
    } else {
        echo "<div class='container mt-3'><p>Tarif bulunamadı.</p></div>";
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>

</html>