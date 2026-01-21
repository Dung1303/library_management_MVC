<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    public function index() {
        // Lấy dữ liệu từ Model
        $bookModel = $this->model('Book');
        $categoryModel = $this->model('Category');

        // Lấy các sách được khuyến nghị hoặc sách mới nhất
        $limit = 12;
        $books = $bookModel->getAllBooks($limit);
        $categories = $categoryModel->getAllCategories();

        // Truyền dữ liệu sang view
        $this->view('home/index', [
            'books' => $books,
            'categories' => $categories,
            'title' => 'Trang Chủ - Thư Viện Sách'
        ]);
    }
}