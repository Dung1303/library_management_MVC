<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    public function index() {
        // Lấy dữ liệu từ Model
        $bookModel = $this->model('Book');
        $categoryModel = $this->model('Category');

        // Cấu hình pagination
        $limit = 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Lấy dữ liệu sách
        $books = $bookModel->getAllBooks($limit, $offset);
        $categories = $categoryModel->getAllCategories();
        $totalBooks = $bookModel->getTotalBooksCount();
        $totalPages = ceil($totalBooks / $limit);

        // Truyền dữ liệu sang view
        $this->view('home/index', [
            'books' => $books,
            'categories' => $categories,
            'page' => $page,
            'totalPages' => $totalPages,
            'title' => 'Trang Chủ - Thư Viện Sách'
        ]);
    }
}