<?php
class AdminController extends Controller
{
    public function index()
    {
        $this->overview();
    }

    public function overview()
    {
        // Lấy dữ liệu từ database
        $bookModel = $this->model('Book');
        $userModel = $this->model('User');
        $borrowModel = $this->model('BorrowRecord');
        
        // Extract dữ liệu thống kê
        $totalBooks = $bookModel->getTotalBooksCount();
        $totalMembers = $userModel->getTotalUsersCount();
        $availableBooks = $bookModel->getAvailableCopiesCount();
        $overdueBooks = $borrowModel->getOverdueBooksCount();
        $newBooksThisMonth = $borrowModel->getTotalBorrowingCount();
        
        $data = [
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'availableBooks' => $availableBooks,
            'overdueBooks' => $overdueBooks,
            'newBooksThisMonth' => $newBooksThisMonth,
            'contentView' => __DIR__ . '/../views/admin/overview.php'
        ];
        
        // Gọi Layout chính, layout này sẽ include viewPath ở trên
        $this->view('layouts/admin_layout', $data);
    }

    public function dashboard()
    {
        // Kiểm tra bảo mật: Phải đăng nhập và là admin mới được vào
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            // Nếu không phải admin thì đá về trang chủ hoặc login
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        // Gọi view dashboard
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - LibraSys'
        ]);
    }

    public function books()
    {
        $bookModel = $this->model('Book');
        $data = [
            'books' => $bookModel->getAllBooks()
        ];
        $this->view('admin/books', $data);
    }

    public function members()
    {
        $data = [
            'members' => []
        ];
        $this->view('admin/members', $data);
    }
}