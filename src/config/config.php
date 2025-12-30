<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'php_cn');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL - Tự động detect localhost hoặc server
if (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false || strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false) {
    define('BASE_URL', '/PHP-CN/public');
} else {
    define('BASE_URL', '/public');
}

// Gemini API Configuration - Hệ thống xoay vòng API key
define('GEMINI_API_KEYS', [
    'AIzaSyCRLsGRsqHS3uCFxhr8Ksa-XTX1r7D0Ddw',  // Key 1
    'AIzaSyBahJVetr2bIvBUj8pTeIOFNEknqVx17yk',  // Key 2 - DACN project
]);
define('GEMINI_API_KEY', GEMINI_API_KEYS[0]); // Giữ tương thích code cũ
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent');
