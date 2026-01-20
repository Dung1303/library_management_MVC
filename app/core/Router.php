<?php
// app/core/Router.php

class Router
{

    public function run()
    {
        // Parse từ REQUEST_URI nếu không có $_GET['url']
        $url = $_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = trim($url, '/');
        $parts = explode('/', $url);

        // Mặc định
        $controllerName = !empty($parts[0])
            ? ucfirst($parts[0]) . 'Controller'
            : 'HomeController';

        $method = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);

        $controllerPath = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerPath)) {
            die('Controller not found');
        }

        require_once $controllerPath;
        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            die('Method not found');
        }

        call_user_func_array([$controller, $method], $params);
    }
}
