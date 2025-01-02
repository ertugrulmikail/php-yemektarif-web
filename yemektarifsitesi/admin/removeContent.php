<?php
require_once('../baglanti.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $process = isset($_GET['process']) ? $_GET['process'] : null;

    if ($process == null || $id == null) {
        echo json_encode(['text' => 'Process or ID is missing', 'color' => 'red']);
        exit;
    }

    switch ($process) {
        case '1':
            $result = removeAdmin($id);
            break;
        case '2':
            $result = removeUser($id);
            break;
        case '3':
            $result = removeCategory($id);
            break;
        case '4':
            $result = removeFood($id);
            break;
        case '5':
            $result = removeComment($id);
            break;
        default:
            $result = ['text' => 'Invalid process', 'color' => 'red'];
            break;
    }

    $_SESSION['edit_messsage'] = json_encode($result);
    $_SESSION['process'] = $process;
    header("Location: adminHomepage.php");
}

function removeAdmin($id)
{
    global $baglanti;

    $query = "DELETE FROM admins WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Admin silindi.', 'color' => 'green'];
    } else {
        return ['text' => 'Admin silinemedi.', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}

function removeUser($id)
{
    global $baglanti;

    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'User silindi.', 'color' => 'green'];
    } else {
        return ['text' => 'User silinemedi.', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}

function removeCategory($id)
{
    global $baglanti;

    $query = "DELETE FROM kategoriler WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Kategori silindi.', 'color' => 'green'];
    } else {
        return ['text' => 'Kategori silinemedi.', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}

function removeFood($id)
{
    global $baglanti;

    $query = "DELETE FROM tarifler WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Yemek silindi.', 'color' => 'green'];
    } else {
        return ['text' => 'Yemek silinemedi.', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}

function removeComment($id)
{
    global $baglanti;

    $query = "DELETE FROM yorumlar WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Yorum silindi.', 'color' => 'green'];
    } else {
        return ['text' => 'Yorum silinemedi.', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}
