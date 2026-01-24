<?php
// app/controllers/MemberController.php

class MemberController extends Controller
{
    public function borrowedBooks()
    {
        // Kiểm tra xem user đã login hay chưa
        if (!isset($_SESSION['user_id'])) {
            header('Location: /library_management_system/auth/login');
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Gọi BorrowRecord model
        $borrowRecordModel = $this->model('BorrowRecord');

        // Lấy sách đang mượn
        $currentBorrows = $borrowRecordModel->getCurrentBorrowsByUser($user_id);

        // Lấy lịch sử mượn (toàn bộ)
        $borrowHistory = $borrowRecordModel->getBorrowHistoryByUser($user_id);

        // Dữ liệu test để xem giao diện (xóa khi có dữ liệu thực)
        if (empty($currentBorrows)) {
            $currentBorrows = [
                [
                    'title' => 'Dã Nguyên - Kiến Thức Khoa Học',
                    'author' => 'Nguyễn Văn A',
                    'barcode' => 'BK001',
                    'borrow_date' => date('Y-m-d', strtotime('-5 days')),
                    'due_date' => date('Y-m-d', strtotime('+10 days')),
                    'status' => 'borrowed',
                    'days_remaining' => 10
                ],
                [
                    'title' => 'Lập Trình PHP Cơ Bản',
                    'author' => 'Trần Thị B',
                    'barcode' => 'BK002',
                    'borrow_date' => date('Y-m-d', strtotime('-2 days')),
                    'due_date' => date('Y-m-d', strtotime('+3 days')),
                    'status' => 'borrowed',
                    'days_remaining' => 3
                ]
            ];
        }

        if (empty($borrowHistory)) {
            $borrowHistory = [
                [
                    'title' => 'Lập Trình JavaScript Hiện Đại',
                    'author' => 'Lê Văn C',
                    'barcode' => 'BK003',
                    'borrow_date' => '2025-12-01',
                    'due_date' => '2025-12-15',
                    'return_date' => '2025-12-14',
                    'status' => 'returned'
                ],
                [
                    'title' => 'Cơ Sở Dữ Liệu MySQL',
                    'author' => 'Phạm Hữu D',
                    'barcode' => 'BK004',
                    'borrow_date' => '2025-11-10',
                    'due_date' => '2025-11-24',
                    'return_date' => '2025-11-25',
                    'status' => 'overdue'
                ]
            ];
        }

        // Truyền data sang view
        $this->view('member/borrowed_books', [
            'currentBorrows' => $currentBorrows,
            'borrowHistory' => $borrowHistory,
            'title' => 'Sách Đang Mượn & Lịch Sử Mượn'
        ]);
    }
}