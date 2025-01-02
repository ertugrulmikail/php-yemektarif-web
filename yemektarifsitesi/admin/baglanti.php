<?php
$host = "localhost";
$kullanici = "root";
$sifre = "B1593572846+";
$veritabani = "berat";
$baglanti = mysqli_connect($host, $kullanici, $sifre);

@mysqli_select_db($baglanti, $veritabani) or die("Veri tabanına bağlanılamadı");
