<?php
require_once('../baglanti.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $process = isset($_GET['process']) ? $_GET['process'] : null;

    if ($process == null) {
        echo json_encode(['text' => 'Process or ID is missing', 'color' => 'red']);
        exit;
    }

    foreach ($_POST as $key => $value) {
        if ($key === 'sifre') {
            $postData[$key] = password_hash($value, PASSWORD_DEFAULT);
        } else {
            $postData[$key] = $value;
        }
    }

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    switch ($process) {
        case '1':
            $result = updateAdmin($postData, $id);
            break;
        case '2':
            $result = updateUser($postData, $id);
            break;
        case '3':
            $result = updateCategory($postData, $id);
            break;
        case '4':
            $result = updateFood($postData, $id);
            break;
        case '5':
            $result = updateComment($postData, $id);
            break;
        case '6':
            $result = updateFood($postData, $id);
            break;
    }

    $_SESSION['edit_messsage'] = json_encode($result);
    $_SESSION['process'] = $process;
    header("Location: adminHomepage.php");
}

function updateAdmin($data, $id)
{
    global $baglanti;

    $query = "UPDATE admins SET ";
    $params = array();
    foreach ($data as $key => $value) {
        $query .= "$key = ?, ";
        $params[] = &$data[$key];
    }

    $query = rtrim($query, ', ');
    $query .= " WHERE id = ?";

    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement: ', 'color' => 'green'];
    }

    $types = str_repeat('s', count($data)) . 'i';
    $bind_params = array_merge(array($types), $params, array(&$id));
    $bind_params_ref = array();
    foreach ($bind_params as $key => $value) {
        $bind_params_ref[$key] = &$bind_params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $bind_params_ref);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Admin güncellendi', 'color' => 'green'];
    } else {
        return ['text' => 'Admin güncellenemedi', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}


function updateUser($data, $id)
{
    global $baglanti;

    $query = "UPDATE users SET ";
    $params = array();
    foreach ($data as $key => $value) {
        $query .= "$key = ?, ";
        $params[] = &$data[$key];
    }

    $query = rtrim($query, ', ');
    $query .= " WHERE id = ?";

    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement:', 'color' => 'red'];
    }

    $types = str_repeat('s', count($data)) . 'i';
    $bind_params = array_merge(array($types), $params, array(&$id));
    $bind_params_ref = array();
    foreach ($bind_params as $key => $value) {
        $bind_params_ref[$key] = &$bind_params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $bind_params_ref);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'User güncellendi', 'color' => 'green'];
    } else {
        return ['text' => 'User güncellenemedi', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}

function updateCategory($data, $id)
{
    global $baglanti;

    $query = "UPDATE kategoriler SET ";
    $params = array();
    foreach ($data as $key => $value) {
        $query .= "$key = ?, ";
        $params[] = &$data[$key];
    }

    $query = rtrim($query, ', ');
    $query .= " WHERE id = ?";

    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement: ', 'color' => 'red'];
    }

    $types = str_repeat('s', count($data)) . 'i';
    $bind_params = array_merge(array($types), $params, array(&$id));
    $bind_params_ref = array();
    foreach ($bind_params as $key => $value) {
        $bind_params_ref[$key] = &$bind_params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $bind_params_ref);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Kategori güncellendi', 'color' => 'green'];
    } else {
        return ['text' => 'Kategori güncellenemedi', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}

function updateFood($data, $id)
{
    global $baglanti;

    $query = "UPDATE tarifler SET ";
    $params = array();
    foreach ($data as $key => $value) {
        $query .= "$key = ?, ";
        $params[] = &$data[$key];
    }

    $query = rtrim($query, ', ');
    $query .= " WHERE id = ?";
    echo $query;
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement: ', 'color' => 'red'];
    }

    $types = str_repeat('s', count($data)) . 'i';
    $bind_params = array_merge(array($types), $params, array(&$id));
    $bind_params_ref = array();
    foreach ($bind_params as $key => $value) {
        $bind_params_ref[$key] = &$bind_params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $bind_params_ref);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Tarif güncellendi', 'color' => 'green'];
    } else {
        return  ['text' => 'Tarif güncellenemedi', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}

function updateComment($data, $id)
{
    global $baglanti;

    $query = "UPDATE yorumlar SET ";
    $params = array();
    foreach ($data as $key => $value) {
        $query .= "$key = ?, ";
        $params[] = &$data[$key];
    }
    $query = rtrim($query, ', ');
    $query .= " WHERE id = ?";

    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement: ', 'color' => 'red'];
    }

    $types = str_repeat('s', count($data)) . 'i';
    $bind_params = array_merge(array($types), $params, array(&$id));
    $bind_params_ref = array();
    foreach ($bind_params as $key => $value) {
        $bind_params_ref[$key] = &$bind_params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $bind_params_ref);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Yorum güncellendi', 'color' => 'green'];
    } else {
        return ['text' => 'Yorum güncellenemedi', 'color' => 'red'];
    }

    $stmt->close();
    $baglanti->close();
}
