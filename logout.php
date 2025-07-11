<?php
require_once 'config/init.php';

// Perform logout
session_destroy();

// Redirect to homepage
header('Location: /');
exit;
?>