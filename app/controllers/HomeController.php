<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    public function index() {
        // Thay vì tự viết code lấy sách, ta gọi BookController xử lý
        require_once __DIR__ . '/BookController.php';
        $bookController = new BookController();
        $bookController->index(); 
    }
}