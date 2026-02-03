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
        
        // Lấy page từ URL, mặc định là 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // 10 thành viên mỗi trang
        
        // Lấy dữ liệu phân trang
        $members = $userModel->getMembersWithPagination($page, $limit);
        $totalMembers = $userModel->getTotalMembersCount();
        $totalPages = ceil($totalMembers / $limit);
        
        // Đảm bảo page không vượt quá tổng số trang
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
        
        $data = [
            'members' => $members,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalMembers' => $totalMembers,
            'limit' => $limit
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
                $_SESSION['success_message'] = 'Member added successfully!';
                header('Location: ' . BASE_URL . '/admin/members');
                exit;
            }
        }

        if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            
            if (empty($userId)) {
                header('Location: ' . BASE_URL . '/admin/members?error=no_user_id');
                exit;
            }
            
            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'username' => $username
            ];
            
            $result = $userModel->updateMember($userId, $data);

            if ($result) {
                $_SESSION['success_message'] = 'Member updated successfully!';
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
