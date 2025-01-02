<?php

$host = "127.0.0.1";
$kullanici = "admin";
$sifre = "admin123";
$veritabani = "demob";
$tablo = "users";
$baglanti = mysqli_connect($host, $kullanici, $sifre);

if ($baglanti) {
} else {
    echo "Bağlantı başarısız", "<br>";
}
@mysqli_select_db($baglanti, $veritabani) or die("Veri tabanına bağlanılamadı");
