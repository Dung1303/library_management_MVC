<?php
class AdminController extends Controller
{
    public function __construct()
    {
        // Bảo mật: Yêu cầu đăng nhập với vai trò admin cho tất cả các chức năng trong controller này
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        // Lấy dữ liệu từ các model để hiển thị trên dashboard
        $bookModel = $this->model('Book');
        $userModel = $this->model('User');
        $borrowModel = $this->model('BorrowRecord');

        // Lấy dữ liệu thống kê
        $totalBooks = $bookModel->getTotalBooksCount();
        $totalMembers = $userModel->getTotalUsersCount();
        $availableBooks = $bookModel->getAvailableCopiesCount();
        $overdueBooks = $borrowModel->getOverdueBooksCount();
        $currentlyBorrowed = $borrowModel->getTotalBorrowingCount();

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - LibraSys',
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'availableBooks' => $availableBooks,
            'overdueBooks' => $overdueBooks,
            'currentlyBorrowed' => $currentlyBorrowed,
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
