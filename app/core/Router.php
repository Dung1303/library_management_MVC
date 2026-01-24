<?php
// app/core/Router.php

class Router
{

    public function run()
    {
        // Lấy URL từ REQUEST_URI sau khi Apache rewrite
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Loại bỏ mọi thứ trước /public/
        if (strpos($requestUri, '/public/') !== false) {
            $requestUri = substr($requestUri, strpos($requestUri, '/public/') + 8); // +8 để bỏ '/public/'
        } elseif (substr($requestUri, -7) === '/public') {
            $requestUri = ''; // Xử lý trường hợp URL kết thúc bằng /public
        } else {
            $requestUri = trim($requestUri, '/');
        }

        $requestUri = trim($requestUri, '/');
        $requestUri = preg_replace('/\.php$/', '', $requestUri); // Loại .php nếu có
        $requestUri = preg_replace('/^index\/?/', '', $requestUri); // Loại "index" từ đầu

        // Parse URL thành phần
        $parts = !empty($requestUri) ? explode('/', $requestUri) : [];
        $parts = array_filter($parts, fn($p) => !empty($p)); // Loại bỏ phần tử rỗng
        $parts = array_values($parts); // Reindex array

        // Mặc định
        $controllerName = !empty($parts[0])
            ? ucfirst($parts[0]) . 'Controller'
            : 'HomeController';

        $method = !empty($parts[1]) ? $parts[1] : 'index';
        $params = array_slice($parts, 2);

        $controllerPath = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerPath)) {
            die('Controller not found: ' . $controllerPath . ' | Parts: ' . json_encode($parts));
        }

        require_once $controllerPath;
        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            die('Method not found');
        }

        call_user_func_array([$controller, $method], $params);
    }
}
