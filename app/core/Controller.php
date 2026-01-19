<?php
// app/core/Controller.php

class Controller
{

    protected function model($model)
    {
        $modelPath = __DIR__ . '/../models/' . $model . '.php';

        if (!file_exists($modelPath)) {
            die('Model not found');
        }

        require_once $modelPath;
        return new $model();
    }
    // ... (hàm model giữ nguyên)
    protected function view($view, $data = [])
    {
        // 1. Giải nén dữ liệu để dùng trong các file view
        extract($data);

        // 2. Xác định đường dẫn các file
        $headerPath = __DIR__ . '/../views/layouts/header.php';
        $footerPath = __DIR__ . '/../views/layouts/footer.php';
        $mainViewPath = __DIR__ . '/../views/' . $view . '.php';

        // 3. Nhúng Header
        if (file_exists($headerPath)) {
            require_once $headerPath;
        }

        // 4. Nhúng nội dung chính (ví dụ: member/home.php)
        if (file_exists($mainViewPath)) {
            require_once $mainViewPath;
        } else {
            die("View $view not found!");
        }

        // 5. Nhúng Footer
        if (file_exists($footerPath)) {
            require_once $footerPath;
        }
    }
}