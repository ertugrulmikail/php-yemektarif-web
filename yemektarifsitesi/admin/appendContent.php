<?php
require_once('../baglanti.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $process = isset($_GET['process']) ? $_GET['process'] : null;

    if ($process == null) {
        echo json_encode(['text' => 'Process or ID is missing', 'color' => 'red']);
        exit;
    }

    switch ($process) {
        case '1':
            $result = getTableStructure("admins");
            break;
        case '2':
            $result = getTableStructure("users");
            break;
        case '3':
            $result = getTableStructure("kategoriler");
            break;
        case '4':
            $result = getTableStructure("tarifler");
            break;
        case '5':
            $result = getTableStructure("yorumlar");
            break;
        default:
            $result = ['text' => 'Invalid process', 'color' => 'red'];
            break;
    }

    echo json_encode($result);
}

function getTableStructure($tableName)
{
    global $baglanti;

    $columns = [];
    $query = "DESCRIBE $tableName";
    $result = $baglanti->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
        return $columns;
    } else {
        return ['error' => 'Table not found'];
    }
}
