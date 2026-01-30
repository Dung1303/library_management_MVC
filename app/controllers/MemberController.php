<?php
// app/controllers/MemberController.php

class MemberController extends Controller
{
    public function borrowedBooks()
    {
        // Kiểm tra xem user đã login hay chưa
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Gọi BorrowRecord model
        $borrowRecordModel = $this->model('BorrowRecord');

        // Lấy sách đang mượn
        $currentBorrows = $borrowRecordModel->getCurrentBorrowsByUser($user_id);

        // Lấy lịch sử mượn (toàn bộ)
        $borrowHistory = $borrowRecordModel->getBorrowHistoryByUser($user_id);
        // Truyền data sang view
        $this->view('member/borrowed_books', [
            'currentBorrows' => $currentBorrows,
            'borrowHistory' => $borrowHistory,
            'title' => 'Sách Đang Mượn & Lịch Sử Mượn'
        ]);
    }
}