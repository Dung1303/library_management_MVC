<?php
// app/controllers/HomeController.php

class HomeController extends Controller
{
    public function index()
    {
        // Lấy dữ liệu từ Model
        $bookModel     = $this->model('Book');
        $categoryModel = $this->model('Category');

        // Cấu hình pagination
        $limit  = 12;
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Lấy các tham số lọc và tìm kiếm
        $filters = [
            'keyword'     => trim($_GET['keyword'] ?? ''),
            'category_id' => (int)($_GET['category_id'] ?? 0)
        ];

        // Lấy dữ liệu sách đã lọc
        $books      = $bookModel->getBooks($filters, $limit, $offset);
        $categories = $categoryModel->getAllCategories();
        $totalBooks = $bookModel->countBooks($filters);
        $totalPages = ceil($totalBooks / $limit);

        // Truyền dữ liệu sang view
        $this->view('home/index', [
            'books'       => $books,
            'categories'  => $categories,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'title'       => 'Home - Library Books',
            'keyword'     => $filters['keyword'],
            'category_id' => $filters['category_id']
        ]);
    }
}
