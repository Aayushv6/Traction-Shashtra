<?php
session_start();
require '../backend/config.php';
session_unset();
session_destroy();
header('Location: ../frontend/login.html');
?>
