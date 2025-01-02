<?php
session_start();

$response = ['status' => 0, 'data' => []];

if (isset($_GET['id']) && isset($_GET['process'])) {
    $id = $_GET['id']; // String olarak al
    $processId = (int)$_GET['process'];

    if ($processId < 7 && $processId > 0) {
        $data = $_SESSION['data'] ?? [];

        foreach ($data as $item) {
            if (isset($item['id']) && $item['id'] === $id) {
                $response['status'] = 1;
                $response['data'] = $item;
                $_SESSION['selectedData'] = $item;
                echo json_encode($response);
                exit;
            }
        }

        // Data bulunamazsa:
        $_SESSION['selectedData'] = [];
        echo json_encode($response);
    } else {
        $response['error'] = 'Invalid processId';
        echo json_encode($response);
    }
} else {
    $response['error'] = 'Invalid request';
    echo json_encode($response);
}
