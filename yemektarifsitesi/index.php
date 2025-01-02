<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@6.0.0-beta3/css/all.min.css" integrity="sha384-D/pQSnwRl16M4ZEBwJKT4QW4v3V1+wEK9jOoA1jokwC+Xm8W3S/T7H6XZ+MFSw8S" crossorigin="anonymous">
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
        <!-- Ana içerik kısmı -->
        <div class="row">
            <div class="col-md-9">
                <!-- Ana içerik -->
                <div class="row">
                    <?php
                    include("baglanti.php");

                    // Tüm tariflerin sorgulanması
                    $sql = "SELECT t.*, u.kullanici_adi, u.profil_resmi, COUNT(d.tarif_id) AS defter_sayisi 
                        FROM tarifler t 
                        LEFT JOIN defterler d ON t.id = d.tarif_id 
                        INNER JOIN users u ON t.kullanici_id = u.id 
                        WHERE t.durum = 'Onaylandı' 
                        GROUP BY t.id";
                    $result = mysqli_query($baglanti, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <div class="col-md-4 my-2">
                                <div class="card">
                                    <a href="tarif_detay.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                        <img src="<?php echo $row['tarif_gorseli']; ?>" class="card-img-top" alt="...">
                                    </a>
                                    <div class="card-body">
                                        <a href="tarif_detay.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                            <h4 class="card-title"><?php echo $row['tarif_adi']; ?></h4>
                                        </a>
                                        <p class="card-text" style="font-size: 14px;"><?php echo $row['kisi_sayisi']; ?> kişilik</p>
                                        <div class="d-flex">
                                            <p class="card-text mr-1" style="font-size: 14px;"><?php echo $row['hazirlanma_suresi']; ?>dk Hazırlık,</p>
                                            <p class="card-text" style="font-size: 14px;"><?php echo $row['pisirme_suresi']; ?>dk Pişirme</p>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo $row['profil_resmi']; ?>" alt="<?php echo $row['kullanici_adi']; ?>" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                            <span style="font-size: 12px;"><?php echo (mb_strlen($row['kullanici_adi']) > 12) ? mb_substr($row['kullanici_adi'], 0, 12) . '...' : $row['kullanici_adi']; ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="defter_ekle_kaldir.php?id=<?php echo $row['id']; ?>" class="text-decoration-none"><i id="defterIkon" class="fa-solid fa-book" style="color: grey; margin-right: 5px;"></i></a>
                                            <span style="margin-right: 10px;"><?php echo $row['defter_sayisi']; ?></span>
                                            <i class="fa-solid fa-comment text-decoration-none" style="color: LightBlue; margin-right: 5px;"></i>
                                            <?php
                                            // Yorum sayısını göster
                                            $tarif_id = $row['id'];
                                            $sql_yorum_sayisi = "SELECT COUNT(*) AS yorum_sayisi FROM yorumlar WHERE tarif_id = $tarif_id";
                                            $result_yorum_sayisi = mysqli_query($baglanti, $sql_yorum_sayisi);
                                            $row_yorum_sayisi = mysqli_fetch_assoc($result_yorum_sayisi);
                                            $yorum_sayisi = $row_yorum_sayisi['yorum_sayisi'];
                                            ?>
                                            <span><?php echo $yorum_sayisi; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<div class='col'>Henüz tarif bulunamadı.</div>";
                    }

                    mysqli_close($baglanti);
                    ?>
                </div>
            </div>
            <!-- Sağ panel -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        En Popüler Tarifler
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php
                        include("baglanti.php");

                        // En popüler 5 tarifi sorgulayalım
                        $sql_populer_tarifler = "SELECT t.*, COUNT(d.tarif_id) AS defter_sayisi 
                                            FROM tarifler t 
                                            LEFT JOIN defterler d ON t.id = d.tarif_id 
                                            WHERE t.durum = 'Onaylandı' 
                                            GROUP BY t.id 
                                            ORDER BY defter_sayisi DESC 
                                            LIMIT 10";
                        $result_populer_tarifler = mysqli_query($baglanti, $sql_populer_tarifler);

                        if (mysqli_num_rows($result_populer_tarifler) > 0) {
                            while ($row_populer = mysqli_fetch_assoc($result_populer_tarifler)) {
                        ?>
                                <li class="list-group-item">
                                    <a href="tarif_detay.php?id=<?php echo $row_populer['id']; ?>" class="text-decoration-none" style="color: dimgray;">
                                        <div class="media">
                                            <img src="<?php echo $row_populer['tarif_gorseli']; ?>" class="mr-3" alt="..." style="width: 60px; height: 60px; object-fit: cover;">
                                            <div class="media-body d-flex align-items-center">
                                                <div class="d-flex justify-content-center align-items-center" style="height: 60px;">
                                                    <?php echo $row_populer['tarif_adi']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                        <?php
                            }
                        } else {
                            echo "<li class='list-group-item'>Henüz popüler tarif bulunamadı.</li>";
                        }

                        mysqli_close($baglanti);
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

</body>

</html>