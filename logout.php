<?php
session_start();
$_SESSION = [];
session_destroy();
unset($_SESSION);
header('location:index.php');

?>