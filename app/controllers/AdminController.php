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
        $userModel = $this->model('User');
        $data = [
            'members' => $userModel->getAllMembers()
        ];
        $this->view('admin/members', $data);
    }

    public function member($action = '', $id = '')
    {
        $userModel = $this->model('User');

        if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $userModel->createMember([
                'fullname' => $_POST['fullname'] ?? '',
                'email' => $_POST['email'] ?? '',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? ''
            ]);

            if ($result) {
                header('Location: ' . BASE_URL . '/admin/members');
                exit;
            }
        }

        if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            
            error_log('=== EDIT MEMBER DEBUG ===');
            error_log('POST data: ' . print_r($_POST, true));
            error_log('User ID: ' . $userId);
            error_log('Fullname: ' . $fullname);
            error_log('Email: ' . $email);
            
            if (empty($userId)) {
                error_log('ERROR: User ID is empty!');
                header('Location: ' . BASE_URL . '/admin/members?error=no_user_id');
                exit;
            }
            
            $data = [
                'fullname' => $fullname,
                'email' => $email
            ];
            
            error_log('Calling updateMember with: ' . json_encode($data));
            
            $result = $userModel->updateMember($userId, $data);
            
            error_log('Update result: ' . ($result ? 'true' : 'false'));
            error_log('=== END DEBUG ===');

            if ($result) {
                header('Location: ' . BASE_URL . '/admin/members');
                exit;
            } else {
                header('Location: ' . BASE_URL . '/admin/members?error=update_failed');
                exit;
            }
        }

        if ($action === 'toggle' && !empty($id)) {
            $member = $userModel->getUserById($id);
            if ($member) {
                $newStatus = $member['status'] === 'active' ? 'locked' : 'active';
                $userModel->updateMemberStatus($id, $newStatus);
            }
            header('Location: ' . BASE_URL . '/admin/members');
            exit;
        }

        header('Location: ' . BASE_URL . '/admin/members');
        exit;
    }}
