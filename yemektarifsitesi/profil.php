<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    include('navbar_kontrol.php');

    if (isset($_SESSION['kullanici_id'])) {
        $ad = $_SESSION['ad'];
        $soyad = $_SESSION['soyad'];
        $email = $_SESSION['email'];
        $kullaniciadi = $_SESSION['kullanici_adi'];
        $profilResmi = $_SESSION['profil_resmi'];
    ?>

        <div class="container mt-3">
            <div class="row" style="border: 1px; border-radius: 10px; padding: 10px; background-color: grey;">
                <div class="col-md-10 text-start">
                    <img src="<?php echo $profilResmi; ?>" class="rounded-circle img-thumbnail" alt="Profil Resmi" style="width: 110px; height: 100px;">
                    <h2 class="d-inline-block ml-3" style="padding-left: 20px; color: white;"><?php echo $ad, " ", $soyad; ?></h2>
                </div>
                <div class="col-md-2 d-flex align-items-center justify-content-end">
                    <button id="editProfileBtn" class="btn btn-primary">Profil Düzenle</button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-center">
                    <button id="myRecipesBtn" class="btn btn-secondary">Tariflerim</button>
                    <button id="myBookBtn" class="btn btn-secondary">Defterim</button>
                </div>
            </div>



            <div id="profileForm" class="row mt-3" style="display: none;">
                <div class="col-md-8 offset-md-2 mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Profil Bilgileri</h2>
                        </div>
                        <div class="card-body">
                            <form id="profilFormu" method="post" action="profil_guncelle.php" enctype="multipart/form-data" onsubmit="return validateForm()">
                                <div class="mb-3">
                                    <label for="profilResmiYeni">Profil Resmi:</label>
                                    <br>
                                    <img id="profilResmiYeniOnizleme" src="<?php echo $profilResmi; ?>" class="rounded-circle img-thumbnail mt-2" alt="Profil Resmi" style="width: 110px; height: 100px;">
                                    <br>
                                    <input type="file" class="form-control-file mt-3" id="profilResmiYeni" name="profilResmiYeni" onchange="gosterOnizleme(this);">
                                </div>
                                <div class="mb-3">
                                    <label for="ad" class="form-label">Ad</label>
                                    <input type="text" class="form-control" id="ad" name="ad" value="<?php echo $ad; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="soyad" class="form-label">Soyad</label>
                                    <input type="text" class="form-control" id="soyad" name="soyad" value="<?php echo $soyad; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kullaniciadi" class="form-label">Kullanıcı Adı</label>
                                    <input type="text" class="form-control" id="kullaniciadi" name="kullaniciadi" value="<?php echo $kullaniciadi; ?>" required>
                                </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if (isset($_GET["id"]) && $_GET["id"] == 1) {
                if (isset($_GET["alert"]) && $_GET["alert"] == "success") {
                    echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            <strong>Başarılı!</strong> Tarifinizi başarıyla güncellediniz!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
                }
            ?>

                <div id="myRecipes" class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-center">Tariflerim</h2>
                            </div>
                            <div class="card-body row">

                                <?php
                                include("baglanti.php");

                                $kullanici_id = $_SESSION['kullanici_id'];

                                $sql = "SELECT t.*, COUNT(d.tarif_id) AS defter_sayisi FROM tarifler t LEFT JOIN defterler d ON t.id = d.tarif_id WHERE t.kullanici_id = '$kullanici_id' GROUP BY t.id";
                                $result = mysqli_query($baglanti, $sql);

                                if (mysqli_num_rows($result) > 0) {

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $tarif_id = $row['id'];
                                        $tarif_adi = $row['tarif_adi'];
                                ?>

                                        <div class="col-md-3 my-2">
                                            <div class="card">
                                                <a href="tarif_detay.php?id=<?php echo $tarif_id; ?>" class="text-decoration-none text-dark">
                                                    <img src="<?php echo $row['tarif_gorseli']; ?>" class="card-img-top" style="max-width: 100%; height: 250px; object-fit: cover;" alt="...">
                                                </a>
                                                <div class="card-body">
                                                    <a href="tarif_detay.php?id=<?php echo $tarif_id; ?>" class="text-decoration-none text-dark">
                                                        <h4 class="card-title"><?php echo $tarif_adi; ?></h4>
                                                    </a>
                                                    <p class="card-text" style="font-size: 14px;"><?php echo $row['kisi_sayisi']; ?> kişilik</p>
                                                    <div class="d-flex">
                                                        <p class="card-text mr-1" style="font-size: 14px;"><?php echo $row['hazirlanma_suresi']; ?>dk Hazırlık,</p>
                                                        <p class="card-text" style="font-size: 14px;"><?php echo $row['pisirme_suresi']; ?>dk Pişirme</p>
                                                    </div>
                                                </div>
                                                <div class="card-footer d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <p class="card-text"><?php echo $row['durum']; ?></p>
                                                    </div>
                                                    <div>
                                                        <i id="defterIkon" class="fa-solid fa-book" style="color: grey; margin-right: 5px;"></i>
                                                        <span style="margin-right: 10px;"><?php echo $row['defter_sayisi']; ?></span>
                                                        <i class="fa-solid fa-comment text-decoration-none" style="color: LightBlue;"></i>
                                                        <?php
                                                        // Yorum sayısını göster
                                                        $sql_yorum_sayisi = "SELECT COUNT(*) AS yorum_sayisi FROM yorumlar WHERE tarif_id = $tarif_id";
                                                        $result_yorum_sayisi = mysqli_query($baglanti, $sql_yorum_sayisi);
                                                        $row_yorum_sayisi = mysqli_fetch_assoc($result_yorum_sayisi);
                                                        $yorum_sayisi = $row_yorum_sayisi['yorum_sayisi'];
                                                        ?>
                                                        <span><?php echo $yorum_sayisi; ?></span>
                                                        <!-- Boşluk -->
                                                        <span style="margin-right: 10px;"></span>
                                                        <a href="tarif_duzenle.php?id=<?php echo $tarif_id; ?>"><i class="fa-solid fa-pen-to-square" style="color: RoyalBlue;"></i></a>
                                                        <!-- Boşluk -->
                                                        <span style="margin-right: 10px;"></span>
                                                        <i class="fa-solid fa-trash" style="color: red; cursor: pointer;" onclick="confirmDelete('<?php echo $tarif_id; ?>', '<?php echo $tarif_adi; ?>');"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "Tarif bulunamadı.";
                                }

                                mysqli_close($baglanti);
                                ?>

                            </div>
                        </div>
                    </div>
                </div>

            <?php
            } else {
            ?>

                <div id="myRecipes" class="row mt-3" style="display: none;">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-center">Tariflerim</h2>
                            </div>
                            <div class="card-body row">

                                <?php
                                include("baglanti.php");

                                $kullanici_id = $_SESSION['kullanici_id'];

                                $sql = "SELECT t.*, COUNT(d.tarif_id) AS defter_sayisi FROM tarifler t LEFT JOIN defterler d ON t.id = d.tarif_id WHERE t.kullanici_id = '$kullanici_id' GROUP BY t.id";
                                $result = mysqli_query($baglanti, $sql);

                                if (mysqli_num_rows($result) > 0) {

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $tarif_id = $row['id'];
                                        $tarif_adi = $row['tarif_adi'];
                                ?>

                                        <div class="col-md-3 my-2">
                                            <div class="card">
                                                <a href="tarif_detay.php?id=<?php echo $tarif_id; ?>" class="text-decoration-none text-dark">
                                                    <img src="<?php echo $row['tarif_gorseli']; ?>" class="card-img-top" style="max-width: 100%; height: 250px; object-fit: cover;" alt="...">
                                                </a>
                                                <div class="card-body">
                                                    <a href="tarif_detay.php?id=<?php echo $tarif_id; ?>" class="text-decoration-none text-dark">
                                                        <h4 class="card-title"><?php echo $tarif_adi; ?></h4>
                                                    </a>
                                                    <p class="card-text" style="font-size: 14px;"><?php echo $row['kisi_sayisi']; ?> kişilik</p>
                                                    <div class="d-flex">
                                                        <p class="card-text mr-1" style="font-size: 14px;"><?php echo $row['hazirlanma_suresi']; ?>dk Hazırlık,</p>
                                                        <p class="card-text" style="font-size: 14px;"><?php echo $row['pisirme_suresi']; ?>dk Pişirme</p>
                                                    </div>
                                                </div>
                                                <div class="card-footer d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <p class="card-text"><?php echo $row['durum']; ?></p>
                                                    </div>
                                                    <div>
                                                        <i id="defterIkon" class="fa-solid fa-book" style="color: grey; margin-right: 5px;"></i>
                                                        <span style="margin-right: 10px;"><?php echo $row['defter_sayisi']; ?></span>
                                                        <i class="fa-solid fa-comment text-decoration-none" style="color: LightBlue;"></i>
                                                        <?php
                                                        // Yorum sayısını göster
                                                        $sql_yorum_sayisi = "SELECT COUNT(*) AS yorum_sayisi FROM yorumlar WHERE tarif_id = $tarif_id";
                                                        $result_yorum_sayisi = mysqli_query($baglanti, $sql_yorum_sayisi);
                                                        $row_yorum_sayisi = mysqli_fetch_assoc($result_yorum_sayisi);
                                                        $yorum_sayisi = $row_yorum_sayisi['yorum_sayisi'];
                                                        ?>
                                                        <span><?php echo $yorum_sayisi; ?></span>
                                                        <!-- Boşluk -->
                                                        <span style="margin-right: 10px;"></span>
                                                        <a href="tarif_duzenle.php?id=<?php echo $tarif_id; ?>"><i class="fa-solid fa-pen-to-square" style="color: RoyalBlue;"></i></a>
                                                        <!-- Boşluk -->
                                                        <span style="margin-right: 10px;"></span>
                                                        <i class="fa-solid fa-trash" style="color: red; cursor: pointer;" onclick="confirmDelete('<?php echo $tarif_id; ?>', '<?php echo $tarif_adi; ?>');"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "Tarif bulunamadı.";
                                }

                                mysqli_close($baglanti);
                                ?>

                            </div>
                        </div>
                    </div>
                </div>

            <?php
            }
            ?>

            <?php
            if (isset($_GET["id"]) && $_GET["id"] == 2) {
            ?>

                <div id="myBook" class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-center">Defterim</h2>
                            </div>
                            <div class="card-body row">
                                <?php
                                // Veritabanı bağlantısı
                                include("baglanti.php");

                                // Kullanıcının ID'sini al
                                $kullanici_id = $_SESSION['kullanici_id'];

                                // Kullanıcının defterindeki tarifleri seç
                                $sql = "SELECT t.*, u.kullanici_adi, u.profil_resmi FROM tarifler t INNER JOIN defterler d ON t.id = d.tarif_id LEFT JOIN users u ON t.kullanici_id = u.id WHERE d.kullanici_id = $kullanici_id AND t.durum = 'Onaylandı'";
                                $result = mysqli_query($baglanti, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                        <div class="col-md-3 my-2">
                                            <div class="card">
                                                <a href="tarif_detay.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                                    <img src="<?php echo htmlspecialchars($row['tarif_gorseli']); ?>" class="card-img-top" style="max-width: 100%; height: 250px; object-fit: cover;" alt="Tarif Resmi">
                                                </a>
                                                <div class="card-body">
                                                    <a href="tarif_detay.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                                        <h4 class="card-title"><?php echo htmlspecialchars($row['tarif_adi']); ?></h4>
                                                    </a>
                                                    <p class="card-text" style="font-size: 14px;"><?php echo htmlspecialchars($row['kisi_sayisi']); ?> kişilik</p>
                                                    <div class="d-flex">
                                                        <p class="card-text mr-1" style="font-size: 14px;"><?php echo htmlspecialchars($row['hazirlanma_suresi']); ?> dk Hazırlık,</p>
                                                        <p class="card-text" style="font-size: 14px;"><?php echo htmlspecialchars($row['pisirme_suresi']); ?> dk Pişirme</p>
                                                    </div>
                                                </div>
                                                <div class="card-footer d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo htmlspecialchars($row['profil_resmi']); ?>" alt="<?php echo htmlspecialchars($row['kullanici_adi']); ?>" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                                        <span style="font-size: 12px;"><?php echo (mb_strlen($row['kullanici_adi']) > 12) ? htmlspecialchars(mb_substr($row['kullanici_adi'], 0, 12)) . '...' : htmlspecialchars($row['kullanici_adi']); ?></span>
                                                    </div>
                                                    <a href="defter_ekle_kaldir.php?id=<?php echo $row['id']; ?>&icon=trash" style="color: red;"><i class="fa-solid fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "Defterinizde henüz tarif bulunmamaktadır.";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

    <?php
            } else {
    ?>

        <div id="myBook" class="row mt-3" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Defterim</h2>
                    </div>
                    <div class="card-body row">
                        <?php
                        // Veritabanı bağlantısı
                        include("baglanti.php");

                        // Kullanıcının ID'sini al
                        $kullanici_id = $_SESSION['kullanici_id'];

                        // Kullanıcının defterindeki tarifleri seç
                        $sql = "SELECT t.*, u.kullanici_adi, u.profil_resmi FROM tarifler t INNER JOIN defterler d ON t.id = d.tarif_id LEFT JOIN users u ON t.kullanici_id = u.id WHERE d.kullanici_id = $kullanici_id AND t.durum = 'Onaylandı'";
                        $result = mysqli_query($baglanti, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="col-md-3 my-2">
                                    <div class="card">
                                        <a href="tarif_detay.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                            <img src="<?php echo htmlspecialchars($row['tarif_gorseli']); ?>" class="card-img-top" style="max-width: 100%; height: 250px; object-fit: cover;" alt="Tarif Resmi">
                                        </a>
                                        <div class="card-body">
                                            <a href="tarif_detay.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                                <h4 class="card-title"><?php echo htmlspecialchars($row['tarif_adi']); ?></h4>
                                            </a>
                                            <p class="card-text" style="font-size: 14px;"><?php echo htmlspecialchars($row['kisi_sayisi']); ?> kişilik</p>
                                            <div class="d-flex">
                                                <p class="card-text mr-1" style="font-size: 14px;"><?php echo htmlspecialchars($row['hazirlanma_suresi']); ?> dk Hazırlık,</p>
                                                <p class="card-text" style="font-size: 14px;"><?php echo htmlspecialchars($row['pisirme_suresi']); ?> dk Pişirme</p>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo htmlspecialchars($row['profil_resmi']); ?>" alt="<?php echo htmlspecialchars($row['kullanici_adi']); ?>" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                                <span style="font-size: 12px;"><?php echo (mb_strlen($row['kullanici_adi']) > 12) ? htmlspecialchars(mb_substr($row['kullanici_adi'], 0, 12)) . '...' : htmlspecialchars($row['kullanici_adi']); ?></span>
                                            </div>
                                            <a href="defter_ekle_kaldir.php?id=<?php echo $row['id']; ?>&icon=trash" style="color: red;"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "Defterinizde henüz tarif bulunmamaktadır.";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    <?php
            }
    ?>

<?php
    } else {
        header("Location: kullanici_giris_ekrani.php");
        exit;
    }
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha383-DfXdz2htPH0lsSSs5nCTpuj/zy3C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha383-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.6.2/js/bootstrap.min.js" integrity="sha383-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script>
    function confirmDelete(tarif_id, tarif_adi) {
        if (confirm(`"${tarif_adi}" adlı tarifi silmek istediğinizden emin misiniz?`)) {
            window.location.href = `tarif_sil.php?tarif_id=${tarif_id}`;
        } else {
            // Kullanıcı "İptal" dediğinde bir şey yapmaya gerek yok.
            return false;
        }
    }
</script>

<script>
    function gosterOnizleme(input) {
        if (input.files && input.files[0]) {
            var okuyucu = new FileReader();
            okuyucu.onload = function(e) {
                document.getElementById('profilResmiYeniOnizleme').src = e.target.result;
            };
            okuyucu.readAsDataURL(input.files[0]);
        }
    }

    function validateForm() {
        var ad = document.getElementById('ad').value;
        var soyad = document.getElementById('soyad').value;
        var email = document.getElementById('email').value;
        var kullaniciadi = document.getElementById('kullaniciadi').value;

        if (ad.length < 3) {
            alert('Ad en az 3 karakter olmalıdır.');
            return false;
        } else if (ad.length > 15) {
            alert('Ad en fazla 15 karakter olmalıdır.');
            return false;
        }

        if (soyad.length < 3) {
            alert('Soyad en az 3 karakter olmalıdır.');
            return false;
        } else if (soyad.length > 15) {
            alert('Soyad en fazla 15 karakter olmalıdır.');
            return false;
        }

        if (kullaniciadi.length < 5) {
            alert('Kullanıcı adı en az 5 karakter olmalıdır.');
            return false;
        } else if (kullaniciadi.length > 25) {
            alert('Kullanıcı adı en fazla 25 karakter olmalıdır.');
            return false;
        }

        if (email.length > 25) {
            alert('Email en fazla 25 karakter olmalıdır.');
            return false;
        } else if (!validateEmail(email)) {
            alert('Geçerli bir e-posta adresi giriniz.');
            return false;
        }

        return true;
    }

    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    document.addEventListener("DOMContentLoaded", function() {
        var editProfileBtn = document.getElementById("editProfileBtn");
        var myRecipesBtn = document.getElementById("myRecipesBtn");
        var myBookBtn = document.getElementById("myBookBtn");
        var profileForm = document.getElementById("profileForm");
        var myRecipes = document.getElementById("myRecipes");
        var myBook = document.getElementById("myBook");

        editProfileBtn.addEventListener("click", function() {
            profileForm.style.display = "block";
            myRecipes.style.display = "none";
            myBook.style.display = "none"; // Eğer myBook bölümü varsa, onu gizle
        });

        myRecipesBtn.addEventListener("click", function() {
            profileForm.style.display = "none";
            myRecipes.style.display = "block";
            myBook.style.display = "none"; // Eğer myBook bölümü varsa, onu gizle
        });

        myBookBtn.addEventListener("click", function() {
            profileForm.style.display = "none";
            myRecipes.style.display = "none";
            myBook.style.display = "block";
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var editProfileBtn = document.getElementById("editProfileBtn");
        var profileForm = document.getElementById("profileForm");
        var adInput = document.getElementById("ad");
        var soyadInput = document.getElementById("soyad");
        var emailInput = document.getElementById("email");
        var kullaniciadiInput = document.getElementById("kullaniciadi");

        editProfileBtn.addEventListener("click", function() {
            profileForm.style.display = "block";
            myRecipes.style.display = "none";
            myBook.style.display = "none";

            // Profil düzenle butonuna tıklandığında inputlara değerleri doldur
            adInput.value = "<?php echo $ad; ?>";
            soyadInput.value = "<?php echo $soyad; ?>";
            emailInput.value = "<?php echo $email; ?>";
            kullaniciadiInput.value = "<?php echo $kullaniciadi; ?>";
        });
    });
</script>

</body>

</html>