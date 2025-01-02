<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriler</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php
    include("navbar_kontrol.php");
    ?>

    <div class="container mt-3">
        <h2 class="text-center mb-4">Kategoriler</h2>

        <div class="row justify-content-center">
            <?php
            // Veritabanı bağlantısı ve kategorilerin çekilmesi
            include("baglanti.php");

            $sql = "SELECT * FROM kategoriler";
            $result = mysqli_query($baglanti, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="col-md-3 mb-3">
                        <a href="kategori_detay.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-block"><?php echo $row['kategori_adi']; ?></a>
                    </div>
            <?php
                }
            } else {
                echo "Kategori bulunamadı.";
            }

            mysqli_close($baglanti);
            ?>
        </div>

    </div>
</body>

</html>