<?php
include '../includes/config.php';
session_start();
$_SESSION = [];
session_destroy();
header("Location:" . BASE_URL . "index.php");
exit();
?>