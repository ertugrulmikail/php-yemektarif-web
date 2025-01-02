<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .dropdown:hover .dropdown-menu {
            display: flex !important;
        }

        .dropdown-menu {
            left: 50% !important;
            transform: translateX(-50%) !important;
            position: absolute !important;
            top: 100% !important;
            left: 50% !important;
            display: none !important;
            flex-direction: column !important;
            align-items: center !important;
        }

        .dropdown-item {
            width: 100% !important;
            text-align: left !important;
        }

        @media (max-width: 767px) {
            .dropdown-menu {
                display: block !important;
                text-align: center !important;
            }

            .dropdown-item {
                width: 100% !important;
                text-align: center !important;
            }

            .border.rounded-pill {
                margin-top: 0.5rem !important;
            }
        }
    </style>

</head>

<body>

    <?php
    if (isset($_SESSION['kullanici_id'])) {
        $ad = $_SESSION['ad'];
        $soyad = $_SESSION['soyad'];
        $email = $_SESSION['email'];
        $kullaniciadi = $_SESSION['kullanici_adi'];
        $profilResmi = $_SESSION['profil_resmi'];
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand me-5" href="index.php">YEMEK KÖŞESİ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="kategoriler.php">Kategoriler</a>
                    </li>
                </ul>
                <div class="border rounded-pill">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="profil.php" id="navbarDropdown" role="button" aria-expanded="true">
                                <img id="profilResmiAvatari" src="<?php echo $profilResmi; ?>" class="rounded-circle img-thumbnail" alt="Profil Resmi" style="width: 45px; height: 40px;">
                                <?php echo $kullaniciadi; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="tarif_gonder.php">Tarif Gönder</a></li>
                                <li><a class="dropdown-item" href="profil.php?id=1">Tariflerim</a></li>
                                <li><a class="dropdown-item" href="profil.php?id=2">Defterim</a></li>
                                <li><a class="dropdown-item" href="cikis.php">Çıkış</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b18fd9083f.js" crossorigin="anonymous"></script>

</body>

</html>