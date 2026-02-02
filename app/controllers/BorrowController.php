<?php
// app/controllers/BorrowController.php

class BorrowController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    public function index()
    {
        $borrowModel = $this->model('BorrowRecord');

        $keyword = $_GET['keyword'] ?? '';
        $page = (int) ($_GET['page'] ?? 1);
        $page = max(1, $page); // Đảm bảo page >= 1
        $limit = 10;

        if (!empty($keyword)) {
            $borrows = $borrowModel->searchByMemberName($keyword, $page, $limit);
            $total = $borrowModel->countSearchByMemberName($keyword);
        } else {
            $borrows = $borrowModel->getAllActiveBorrows($page, $limit);
            $total = $borrowModel->countAllActiveBorrows();
        }

        $totalPages = ceil($total / $limit);

        $this->view('admin/borrowing', [
            'borrows' => $borrows,
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'limit' => $limit
        ]);
    }


    public function history()
    {
        $borrowModel = $this->model('BorrowRecord');

        $keyword = $_GET['keyword'] ?? '';
        $page = (int) ($_GET['page'] ?? 1);
        $page = max(1, $page); // Đảm bảo page >= 1
        $limit = 10;

        if (!empty($keyword)) {
            $borrows = $borrowModel->searchHistoryByMemberName($keyword, $page, $limit);
            $total = $borrowModel->countSearchHistoryByMemberName($keyword);
        } else {
            $borrows = $borrowModel->getAllBorrowHistory($page, $limit);
            $total = $borrowModel->countAllBorrowHistory();
        }

        $totalPages = ceil($total / $limit);

        $this->view('admin/history', [
            'borrows' => $borrows,
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'limit' => $limit
        ]);
    }


    public function create()
    {
        $userModel   = $this->model('User');
        $bookModel   = $this->model('Book');
        $copyModel   = $this->model('BookCopy');
        $borrowModel = $this->model('BorrowRecord');

        // ======================
        // SUBMIT FORM
        // ======================
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userId     = $_POST['user_id'];
            $copyIdsStr = $_POST['book_copy_ids'] ?? ''; // Chuỗi các book_copy_id (ví dụ: "1,2,3")
            $copyIds    = array_filter(array_map('trim', explode(',', $copyIdsStr))); // Parse thành mảng
            $dueDate    = $_POST['due_date'];
            $borrowDate = date('Y-m-d');

            // Validate
            if (empty($userId)) {
                die('Vui lòng chọn thành viên');
            }
            if (empty($copyIds)) {
                die('Vui lòng chọn ít nhất 1 quyển sách');
            }
            if ($dueDate <= $borrowDate) {
                die('Hạn trả không hợp lệ');
            }

            // Tạo phiếu mượn
            $borrowId = $borrowModel->createBorrow(
                $userId,
                $borrowDate,
                $dueDate
            );

            // Thêm tất cả sách vào phiếu mượn
            foreach ($copyIds as $copyId) {
                if (!empty($copyId) && is_numeric($copyId)) {
                    $borrowModel->addBorrowCopy($borrowId, $copyId);
                    $copyModel->updateStatus($copyId, 'borrowed');
                }
            }

            header('Location: ' . BASE_URL . '/admin/index');
            exit;
        }

        // ======================
        // HIỂN THỊ FORM
        // ======================
        $members = $userModel->getAllMembers();
        $books   = $bookModel->getBooksHaveAvailable();

        $this->view('admin/create', [
            'members' => $members,
            'books'   => $books
        ]);
    }

    public function returnBook()
    {
        $borrowModel = $this->model('BorrowRecord');
        $borrowModel->returnBook($_POST['borrow_id'], $_POST['book_copy_id']);

        header('Location: ' . BASE_URL . '/borrow/index');
        exit;
    }
    public function getCopies($book_id)
    {
        $copyModel = $this->model('BookCopy');

        $copies = $copyModel->getAvailableByBook($book_id);

        header('Content-Type: application/json');
        echo json_encode($copies);
        exit;
    }
}
