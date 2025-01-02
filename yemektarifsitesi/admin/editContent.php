<?php
require_once('../baglanti.php');

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $process = isset($_GET['process']) ? $_GET['process'] : null;

    if ($process == null || $id == null) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid parameters']);
        exit;
    }
    switch ($process) {
        case '1':
            $admin = editAdmin($id);
            $adminData = json_decode($admin, true);

            if ($adminData && !isset($adminData['error'])) {
                echo $admin;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'adminData alınamadı']);
            }
            break;
        case '2':
            $user = editUser($id);
            $userData = json_decode($user, true);

            if ($userData && !isset($userData['error'])) {
                echo $user;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'userData alınamadı']);
            }
            break;
        case '3':
            $category = editCategory($id);
            $categoryData = json_decode($category, true);

            if ($categoryData && !isset($categoryData['error'])) {
                echo $category;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'categoryData alınamadı']);
            }
            break;
        case '4':
            $food = editFood($id);
            $foodData = json_decode($food, true);

            if ($foodData && !isset($foodData['error'])) {
                echo $food;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'foodData alınamadı']);
            }
            break;
        case '5':
            $comment = editComment($id);
            $commentData = json_decode($comment, true);

            if ($commentData && !isset($commentData['error'])) {
                echo $comment;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'commentData alınamadı']);
            }
            break;
        case '6':
            $food = editFood($id);
            $foodData = json_decode($food, true);

            if ($foodData && !isset($foodData['error'])) {
                echo $food;
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'foodData alınamadı']);
            }
            break;
        default:
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid process']);
            break;
    }
}

function editAdmin($id)
{
    global $baglanti;

    $query = "SELECT * FROM admins WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return json_encode(['error' => 'Failed to prepare statement: ' . $baglanti->error]);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        return json_encode($admin);
    } else {
        return json_encode(['error' => 'Admin bulunamadı']);
    }

    $stmt->close();
    $baglanti->close();
}

function editUser($id)
{
    global $baglanti;

    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return json_encode(['error' => 'Failed to prepare statement: ' . $baglanti->error]);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return json_encode($user);
    } else {
        return json_encode(['error' => 'User bulunamadı']);
    }

    $stmt->close();
    $baglanti->close();
}

function editCategory($id)
{
    global $baglanti;

    $query = "SELECT * FROM kategoriler WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return json_encode(['error' => 'Failed to prepare statement: ' . $baglanti->error]);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        return json_encode($category);
    } else {
        return json_encode(['error' => 'Category bulunamadı']);
    }

    $stmt->close();
    $baglanti->close();
}

function editFood($id)
{
    global $baglanti;

    $query = "SELECT * FROM tarifler WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return json_encode(['error' => 'Failed to prepare statement: ' . $baglanti->error]);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $food = $result->fetch_assoc();
        return json_encode($food);
    } else {
        return json_encode(['error' => 'Tarif bulunamadı']);
    }

    $stmt->close();
    $baglanti->close();
}

function editComment($id)
{
    global $baglanti;

    $query = "SELECT * FROM yorumlar WHERE id = ?";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return json_encode(['error' => 'Failed to prepare statement: ' . $baglanti->error]);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $comment = $result->fetch_assoc();
        return json_encode($comment);
    } else {
        return json_encode(['error' => 'Comment bulunamadı']);
    }

    $stmt->close();
    $baglanti->close();
}
