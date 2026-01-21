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

    protected function view($view, $data = [])
    {
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die('View not found');
        }

        require_once $viewPath;
    }
}
