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

if ($id > 0) {
    $extensions = ['jpg', 'png', 'jpeg'];
    $fileFound = false;

    foreach ($extensions as $ext) {
        $imagePath = "../Kullanici_Profil_Resimleri/$id.$ext";
        if ($process == 4 || $process == 6) {
            $imagePath = "../Kullanici_Tarif_Resimleri/$id.$ext";
        }
        logError('Checking path: ' . $imagePath);
        if (file_exists($imagePath)) {
            if (unlink($imagePath)) {
                echo 'Image deleted successfully';
            } else {
                $error = 'Error deleting the image';
                logError($error);
                echo $error;
            }
            $fileFound = true;
            break;
        }
    }

    if (!$fileFound) {
        $error = 'Image file does not exist for ID ' . $id;
        logError($error);
        echo $error;
    }
} else {
    $error = 'Invalid image ID';
    logError($error);
    echo $error;
}
