<?php
session_start();
include("baglanti.php");

if (isset($_POST['yorum_metni'], $_POST['tarif_id'], $_SESSION['kullanici_id'])) {
    $yorum_metni = mysqli_real_escape_string($baglanti, $_POST['yorum_metni']);
    $tarif_id = (int) $_POST['tarif_id'];
    $kullanici_id = (int) $_SESSION['kullanici_id'];

    $sql = "INSERT INTO yorumlar (tarif_id, kullanici_id, yorum_metni) VALUES ('$tarif_id', '$kullanici_id', '$yorum_metni')";

    if (mysqli_query($baglanti, $sql)) {
        header("Location: tarif_detay.php?id=$tarif_id");
        exit;
    } else {
        echo "Hata: " . mysqli_error($baglanti);
    }
} else {
    echo "Geçersiz istek.";
}

mysqli_close($baglanti);
