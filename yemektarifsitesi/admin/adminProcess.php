<?php
require_once('../baglanti.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function login($username, $password)
{
    global $baglanti;

    $query = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($baglanti, $query);

    if (!$result) {
        return "Veritabanı hatası: " . mysqli_error($baglanti);
    }

    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['admin'] = $row;
        $_SESSION['redirect_messsage'] = '{"status_code": 1, "text": "Giriş yapıldı.", "color": "green"}';
        header("Location: adminHomepage.php");
    } else {
        $_SESSION['redirect_messsage'] = '{"status_code": 2, "text": "Giriş bilgileri hatalı.", "color": "red"}';
        header("Location: admin_login.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    login($username, $password);
}
