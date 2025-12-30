<?php
    // Hiển thị lỗi để debug
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Start session ngay từ đầu
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Bật output buffering để tránh lỗi header
    ob_start();
    
    require_once '../app/core/App.php';
    require_once '../app/core/Controller.php';
    require_once '../app/core/Database.php';
    require_once '../app/core/Schema.php';
    require_once '../config/config.php';
    
    Schema::run();
    $app = new App();
    
    // Flush output buffer
    ob_end_flush();