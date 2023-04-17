<?php 
session_start();
require_once 'classes.php';
$login = new Login('', '');
$login->logout();
header('Location: http://admin-theme.oa/login');
exit;
?>