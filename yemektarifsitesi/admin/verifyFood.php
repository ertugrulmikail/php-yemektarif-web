<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../baglanti.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($id !== null) {
        $result = verify($id);
    } else {
        $result = ['text' => "Geçersiz ID.", 'color' => 'red'];
    }

    $_SESSION['edit_message'] = $result;
}

function verify($id)
{
    global $baglanti;

    if ($baglanti->connect_error) {
        return ['text' => "Bağlantı hatası: " . $baglanti->connect_error, 'color' => 'red'];
    }

    $sql = "UPDATE tarifler SET durum = 'Onaylandı' WHERE id = ?";
    $stmt = $baglanti->prepare($sql);

    if (!$stmt) {
        return ['text' => "Hazırlama hatası: " . $baglanti->error, 'color' => 'red'];
    }

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $result = ['text' => $id . "'li yemek onaylandı.", 'color' => 'green'];
    } else {
        $result = ['text' => "Güncelleme hatası: " . $stmt->error, 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();

    return $result;
}
