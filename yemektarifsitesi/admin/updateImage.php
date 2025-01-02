<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function logError($message)
{
    file_put_contents('error_log.txt', $message . PHP_EOL, FILE_APPEND);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$process = isset($_GET['process']) ? intval($_GET['process']) : 0;

if ($id > 0 && isset($_FILES['image'])) {
    $extensions = ['jpg', 'jpeg', 'png'];
    $uploadDir = '../Kullanici_Profil_Resimleri/';
    if ($process == 4 || $process == 6) {
        $uploadDir = '../Kullanici_Tarif_Resimleri/';
    }
    $fileName = $id;

    $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $extensions)) {
        $error = 'Invalid file type. Only JPG, JPEG, and PNG are allowed.';
        logError($error);
        echo json_encode(['success' => false, 'message' => $error]);
        exit;
    }

    $uploadFile = $uploadDir . $fileName . '.' . $fileExtension;

    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        foreach ($extensions as $ext) {
            $existingFile = $uploadDir . $fileName . '.' . $ext;
            if (file_exists($existingFile) && $existingFile != $uploadFile) {
                unlink($existingFile);
            }
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            echo json_encode(['success' => true, 'newFilename' => $fileName . '.' . $fileExtension]);
        } else {
            $error = 'Error moving the uploaded file';
            logError($error);
            echo json_encode(['success' => false, 'message' => $error]);
        }
    } else {
        $error = 'File upload error: ' . $_FILES['image']['error'];
        logError($error);
        echo json_encode(['success' => false, 'message' => $error]);
    }
} else {
    $error = 'Invalid request';
    logError($error);
    echo json_encode(['success' => false, 'message' => $error]);
}
