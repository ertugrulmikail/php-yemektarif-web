<?php
session_start();
include("baglanti.php");

if (isset($_GET['tarif_id'])) {
    $tarif_id = (int) $_GET['tarif_id'];

    // Tarifin defterler tablosundan silinmesi
    $sql_defterler = "DELETE FROM defterler WHERE tarif_id = $tarif_id";
    if (!mysqli_query($baglanti, $sql_defterler)) {
        echo "Defterler tablosundan silme işlemi başarısız: " . mysqli_error($baglanti);
        mysqli_close($baglanti);
        exit;
    }

    // Tarifin yorumlar tablosundan silinmesi
    $sql_yorumlar = "DELETE FROM yorumlar WHERE tarif_id = $tarif_id";
    if (!mysqli_query($baglanti, $sql_yorumlar)) {
        echo "Yorumlar tablosundan silme işlemi başarısız: " . mysqli_error($baglanti);
        mysqli_close($baglanti);
        exit;
    }

    // Tarifin tarifler tablosundan silinmesi
    $sql_tarifler = "DELETE FROM tarifler WHERE id = $tarif_id";
    if (mysqli_query($baglanti, $sql_tarifler)) {
        echo "Tarif başarıyla silindi.";
        header("refresh:1; url=profil.php?id=1");
        exit;
    } else {
        echo "Hata: " . mysqli_error($baglanti);
    }
} else {
    echo "Geçersiz istek.";
}

mysqli_close($baglanti);
