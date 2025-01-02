<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../baglanti.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $process = isset($_GET['process']) ? $_GET['process'] : null;

    if ($process === null) {
        echo json_encode([]);
        exit;
    }

    $data = [];

    /*switch ($process) {
        case '1':
            $admins = getAdmins();
            if ($admins) {
                echo json_encode($admins);
            } else {
                echo json_encode([]);
            }
            break;
        case '2':
            $users = getUsers();
            if ($users) {
                echo json_encode($users);
            } else {
                echo json_encode([]);
            }
            break;
        case '3':
            $categories = getCategories();
            if ($categories) {
                echo json_encode($categories);
            } else {
                echo json_encode([]);
            }
            break;
        case '4':
            $foods = getFoods();
            if ($foods) {
                echo json_encode($foods);
            } else {
                echo json_encode([]);
            }
            break;
        case '5':
            $comments = getComments();
            if ($comments) {
                echo json_encode($comments);
            } else {
                echo json_encode([]);
            }
            break;
        case '6':
            $foods = getUnverifiedFoods();
            if ($foods) {
                echo json_encode($foods);
            } else {
                echo json_encode([]);
            }
            break;
    }*/

    switch ($process) {
        case '1':
            $data = getAdmins();
            break;
        case '2':
            $data = getUsers();
            break;
        case '3':
            $data = getCategories();
            break;
        case '4':
            $data = getFoods();
            break;
        case '5':
            $data = getComments();
            break;
        case '6':
            $data = getUnverifiedFoods();
            break;
        default:
            echo json_encode([]);
            exit;
    }

    $_SESSION['data'] = $data;

    echo json_encode($data);
}

function getAdmins()
{
    global $baglanti;
    $query = "SELECT * FROM admins";
    $result = mysqli_query($baglanti, $query);
    if (!$result) {
        return false;
    }
    $admins = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $admins;
}

function getUsers()
{
    global $baglanti;
    $query = "SELECT * FROM users";
    $result = mysqli_query($baglanti, $query);
    if (!$result) {
        return false;
    }
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $users;
}

function getCategories()
{
    global $baglanti;
    $query = "SELECT * FROM kategoriler";
    $result = mysqli_query($baglanti, $query);
    if (!$result) {
        return false;
    }
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $categories;
}

function getFoods()
{
    global $baglanti;
    $query = "SELECT * FROM tarifler where durum like 'Onaylandı'";
    $result = mysqli_query($baglanti, $query);
    if (!$result) {
        return false;
    }
    $foods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $foods;
}

function getComments()
{
    global $baglanti;
    $query = "SELECT * FROM yorumlar";
    $result = mysqli_query($baglanti, $query);
    if (!$result) {
        return false;
    }
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $comments;
}

function getUnverifiedFoods()
{
    global $baglanti;
    $query = "SELECT * FROM tarifler where durum like 'Beklemede'";
    $result = mysqli_query($baglanti, $query);
    if (!$result) {
        return false;
    }
    $foods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $foods;
}
