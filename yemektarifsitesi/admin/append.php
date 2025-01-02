<?php

require_once('../baglanti.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $process = isset($_GET['process']) ? $_GET['process'] : null;

    if ($process == null) {
        echo json_encode(['text' => 'Process is missing', 'color' => 'red']);
        exit;
    }

    switch ($process) {
        case '1':
            $result = add("admins", $_POST);
            if ($result == 0) {
                $result = ['text' => 'Admin eklenemedi.', 'color' => 'red'];
            } else {
                $result = ['text' => 'Admin eklendi.', 'color' => 'green'];
            }
            break;
        case '2':
            $result = add("users", $_POST);
            if ($result == 0) {
                $result = ['text' => 'Kullanıcı eklenemedi.', 'color' => 'red'];
            } else {
                $result = ['text' => 'Kullanıcı eklendi.', 'color' => 'green'];
            }
            break;
        case '3':
            $result = add("kategoriler", $_POST);
            if ($result == 0) {
                $result = ['text' => 'Kategori eklenemedi.', 'color' => 'red'];
            } else {
                $result = ['text' => 'Kategori eklendi.', 'color' => 'green'];
            }
            break;
        case '4':
            $result = add("tarifler", $_POST);
            if ($result == 0) {
                $result = ['text' => 'Tarif eklenemedi.', 'color' => 'red'];
            } else {
                $result = ['text' => 'Tarif eklendi.', 'color' => 'green'];
            }
            break;
        case '5':
            $result = add("yorumlar", $_POST);
            if ($result == 0) {
                $result = ['text' => 'Yorum eklenemedi.', 'color' => 'red'];
            } else {
                $result = ['text' => 'Yorum eklendi.', 'color' => 'green'];
            }
            break;
        default:
            $result = ['text' => 'Invalid process', 'color' => 'red'];
            break;
    }

    $_SESSION['edit_messsage'] = json_encode($result);
    $_SESSION['process'] = $process;
    header("Location: adminHomepage.php");
}

function add($tableName, $postData)
{
    global $baglanti;

    $columns = '';
    $values = '';
    $types = '';
    $bindParams = [];

    foreach ($postData as $key => $value) {
        $columns .= "$key,";

        $values .= '?,';

        if (is_numeric($value)) {
            $types .= 'i';
        } else {
            $types .= 's';
        }

        $bindParams[] = &$postData[$key];
    }

    $columns = rtrim($columns, ',');
    $values = rtrim($values, ',');

    $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return 0;
    }

    $bindParams = array_merge([$types], $bindParams);
    call_user_func_array([$stmt, 'bind_param'], $bindParams);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return 1;
    } else {
        return 0;
    }

    $stmt->close();
}


/*
function addAdmin($tableName, $postData)
{
    global $baglanti;

    $postData

    $query = "INSERT INTO $tableName (username, password) VALUES (?, ?)";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Admin added successfully', 'color' => 'green'];
    } else {
        return ['text' => 'Failed to add admin', 'color' => 'red'];
    }

    $stmt->close();
}

function addUser($tableName, $postData)
{
    global $baglanti;

    $username = $postData['username'];
    $password = $postData['password'];

    $query = "INSERT INTO $tableName (username, password) VALUES (?, ?)";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Admin added successfully', 'color' => 'green'];
    } else {
        return ['text' => 'Failed to add admin', 'color' => 'red'];
    }

    $stmt->close();
}

function addCategory($tableName, $postData)
{
    global $baglanti;

    $username = $postData['username'];
    $password = $postData['password'];

    $query = "INSERT INTO $tableName (username, password) VALUES (?, ?)";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Admin added successfully', 'color' => 'green'];
    } else {
        return ['text' => 'Failed to add admin', 'color' => 'red'];
    }

    $stmt->close();
}

function addFood($tableName, $postData)
{
    global $baglanti;

    $username = $postData['username'];
    $password = $postData['password'];

    $query = "INSERT INTO $tableName (username, password) VALUES (?, ?)";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Admin added successfully', 'color' => 'green'];
    } else {
        return ['text' => 'Failed to add admin', 'color' => 'red'];
    }

    $stmt->close();
}

function addComment($tableName, $postData)
{
    global $baglanti;

    $username = $postData['username'];
    $password = $postData['password'];

    $query = "INSERT INTO $tableName (username, password) VALUES (?, ?)";
    $stmt = $baglanti->prepare($query);
    if (!$stmt) {
        return ['text' => 'Failed to prepare statement', 'color' => 'red'];
    }

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['text' => 'Admin added successfully', 'color' => 'green'];
    } else {
        return ['text' => 'Failed to add admin', 'color' => 'red'];
    }

    $stmt->close();
}
*/