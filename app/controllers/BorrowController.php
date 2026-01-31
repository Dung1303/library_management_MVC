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

    if (!empty($keyword)) {
        $borrows = $borrowModel->searchByMemberName($keyword);
    } else {
        $borrows = $borrowModel->getAllActiveBorrows();
    }

    $this->view('admin/borrow', [
        'mode'    => 'list',
        'borrows' => $borrows,
        'keyword' => $keyword   // ⭐ DÒNG QUAN TRỌNG
    ]);
}


   public function history()
{
    $borrowModel = $this->model('BorrowRecord');

    $this->view('admin/borrow', [
        'mode'    => 'history',
        'borrows' => $borrowModel->getAllBorrowHistory()
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

        $userId   = $_POST['user_id'];
        $copyId   = $_POST['book_copy_id'];
        $dueDate  = $_POST['due_date'];
        $borrowDate = date('Y-m-d');

        if ($dueDate <= $borrowDate) {
            die('Hạn trả không hợp lệ');
        }

        $borrowId = $borrowModel->createBorrow(
            $userId,
            $borrowDate,
            $dueDate
        );

        $borrowModel->addBorrowCopy($borrowId, $copyId);
        $copyModel->updateStatus($copyId, 'borrowed');
$copyId = $_POST['book_copy_id'];

if (empty($copyId) || !is_numeric($copyId)) {
    die('Vui lòng chọn bản sao hợp lệ');
}

        header('Location: ' . BASE_URL . '/admin/index');
        exit;
    }

    // ======================
    // HIỂN THỊ FORM
    // ======================
    $members = $userModel->getAllMembers();
    $books   = $bookModel->getBooksHaveAvailable();

    $this->view('admin/borrow', [
        'mode'    => 'create',
        'members' => $members,
        'books'   => $books,
        'copies'  => []
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