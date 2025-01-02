<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>

    <?php
    include('navbar_kontrol.php');
    ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Kullanıcı Kayıt</h2>
                    </div>
                    <div class="card-body">
                        <form id="kayitFormu" action="kullanici_kayit_olustur.php" method="post" enctype="multipart/form-data" onsubmit="return formuDogrula();">
                            <div class="form-group">
                                <label for="profilResmi">Profil Resmi:</label>
                                <br>
                                <img id="profilResmiOnizleme" src="default_profil_resmi.png" class="rounded-circle img-thumbnail mb-2" alt="Profil Resmi" style="width: 110px; height: 100px;">
                                <input type="file" class="form-control-file ml-2 mt-1" id="profilResmi" name="profilResmi" onchange="gosterOnizleme(this);">
                            </div>
                            <div class="form-group">
                                <label for="ad">Ad:</label>
                                <input type="text" class="form-control" name="ad" id="ad" placeholder="Adınız" required>
                            </div>
                            <div class="form-group">
                                <label for="soyad">Soyad:</label>
                                <input type="text" class="form-control" name="soyad" id="soyad" placeholder="Soyadınız" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Emailiniz" required>
                            </div>
                            <div class="form-group">
                                <label for="kullaniciadi">Kullanıcı Adı:</label>
                                <input type="text" class="form-control" name="kullaniciadi" id="kullaniciadi" placeholder="Kullanıcı Adı" required>
                            </div>
                            <div class="form-group">
                                <label for="sifre">Şifre:</label>
                                <input type="password" class="form-control" name="sifre" id="sifre" placeholder="Şifre" required>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Oluştur</button>
                            <button type="reset" class="btn btn-secondary mr-2 float-right">Temizle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <script>
        function gosterOnizleme(input) {
            if (input.files && input.files[0]) {
                var okuyucu = new FileReader();

                okuyucu.onload = function(e) {
                    $('#profilResmiOnizleme').attr('src', e.target.result);
                };

                okuyucu.readAsDataURL(input.files[0]);
            }
        }

        function formuDogrula() {
            var ad = document.getElementById('ad').value;
            var soyad = document.getElementById('soyad').value;
            var email = document.getElementById('email').value;
            var kullaniciadi = document.getElementById('kullaniciadi').value;
            var sifre = document.getElementById('sifre').value;
            var mesaj = '';

            if (ad.length < 3) {
                mesaj += 'Ad en az 3 karakter olmalıdır.\n';
            } else if (ad.length > 15) {
                mesaj += 'Ad en fazla 15 karakter uzunluğunda olabilir.\n';
            }

            if (soyad.length < 3) {
                mesaj += 'Soyad en az 3 karakter olmalıdır.\n';
            } else if (soyad.length > 15) {
                mesaj += 'Soyad en fazla 15 karakter uzunluğunda olabilir.\n';
            }

            if (kullaniciadi.length < 5) {
                mesaj += 'Kullanıcı adı en az 5 karakter olmalıdır.\n';
            } else if (kullaniciadi.length > 25) {
                mesaj += 'Kullanıcı adı en fazla 25 karakter uzunluğunda olabilir.\n';
            }

            if (sifre.length < 5) {
                mesaj += 'Şifre en az 5 karakter olmalıdır.\n';
            } else if (sifre.length > 25) {
                mesaj += 'Şifre en fazla 25 karakter uzunluğunda olabilir.\n';
            }

            if (!validateEmail(email)) {
                mesaj += 'Geçerli bir e-posta adresi giriniz.\n';
            } else if (email.length > 25) {
                mesaj += 'E-posta en fazla 25 karakter uzunluğunda olabilir.\n';
            }

            if (mesaj) {
                alert(mesaj);
                return false;
            }

            return true;
        }

        function validateEmail(email) {
            var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return re.test(String(email).toLowerCase());
        }
    </script>

</body>

</html>