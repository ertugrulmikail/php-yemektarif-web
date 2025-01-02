<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function logError($message)
{
    file_put_contents('error_log.txt', $message . PHP_EOL, FILE_APPEND);
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$filename = isset($_POST['filename']) ? $_POST['filename'] : '';

if ($id > 0 && !empty($filename)) {
    $mysqli = new mysqli('localhost', 'username', 'password', 'database');

    if ($mysqli->connect_error) {
        $error = 'Database connection error: ' . $mysqli->connect_error;
        logError($error);
        echo $error;
        exit;
    }

    $stmt = $mysqli->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('si', $filename, $id);
        if ($stmt->execute()) {
            echo 'Database updated successfully';
        } else {
            $error = 'Error updating database: ' . $stmt->error;
            logError($error);
            echo $error;
        }
        $stmt->close();
    } else {
        $error = 'Error preparing statement: ' . $mysqli->error;
        logError($error);
        echo $error;
    }

    $mysqli->close();
} else {
    $error = 'Invalid request';
    logError($error);
    echo $error;
}
