<?php
include("baglanti.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
$email = $_POST['email'];

// Kullanıcıyı bul
$query_email = mysqli_query($baglanti, "SELECT Email FROM demob.users WHERE Email='$email'");
$row_email = mysqli_num_rows($query_email);

if ($row_email == 1) {
    // Eğer kullanıcı bulunduysa, doğrulama kodu oluştur
    $dogrulama_kodu = rand(100000, 999999); // 6 haneli bir rastgele doğrulama kodu oluştur

    // Doğrulama kodunu yeni tabloya ekleyin
    $insert_query = mysqli_query($baglanti, "INSERT INTO dogrulama_kodlari (email, dogrulama_kodu) VALUES ('$email', '$dogrulama_kodu')");

    if ($insert_query) {
        // Doğrulama kodunu gönder
        try {
            require 'PHPMailer/src/Exception.php';
            require 'PHPMailer/src/PHPMailer.php';
            require 'PHPMailer/src/SMTP.php';

            $mail = new PHPMailer(true);
            // SMTP ayarları
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // SMTP sunucu adresi
            $mail->SMTPAuth = true;
            $mail->Username = 'gogolay.1905@gmail.com'; // SMTP hesap kullanıcı adı
            $mail->Password = 'tmkn vhor okpv swcr'; // SMTP hesap şifresi
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // E-posta bilgileri
            $mail->setFrom('gogolay.1905@gmail.com', 'My Website');
            $mail->addAddress($email); // Kullanıcı e-posta adresi
            $mail->isHTML(true);
            $mail->Subject = 'Dogrulama Kodu';
            $mail->Body = 'Hesabınızı doğrulamak için aşağıdaki kodu kullanın: ' . $dogrulama_kodu;

            // E-postayı gönder
            $mail->send();

            // Kullanıcıyı doğrulama sayfasına yönlendir
            $_SESSION['email'] = $email; // Doğrulama kodunu girmek için kullanıcının e-posta adresini sakla
            header("Location: dogrulama_kodu_onaylama.php");
            exit;
        } catch (Exception $e) {
            echo "E-posta gönderirken bir hata oluştu: {$mail->ErrorInfo}";
        }
    } else {
        echo "Doğrulama kodu eklenirken bir hata oluştu.";
    }
} else {
    echo "Girdiğiniz emaile ait bir hesap bulunamamıştır";
}
