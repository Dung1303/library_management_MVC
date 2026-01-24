<?php
// app/config/config.php

// Tự động detect base URL từ $_SERVER
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

define('BASE_URL', $protocol . $host . $path);
define('APP_NAME', 'library_management_MVC');

date_default_timezone_set('Asia/Ho_Chi_Minh');