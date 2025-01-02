<?php

session_start();

if (isset($_SESSION['kullanici_id'])) {

    include 'navbar_user.php';
} else {

    include 'navbar_guest.php';
}
