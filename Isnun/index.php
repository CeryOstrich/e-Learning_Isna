<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: Login_Page.php");
} else {
    header("Location: Login_Page.php");
}
exit;