<?php
// app/models/BorrowRecord.php

class BorrowRecord extends Model
{
    // Lấy lịch sử mượn sách (tất cả - cả đang mượn, đã trả, quá hạn)
    public function getBorrowHistoryByUser($user_id)
    {
        $sql = "SELECT 
                    br.borrow_id,
                    br.borrow_date,
                    br.due_date,
                    br.return_date,
                    br.status,
                    b.title,
                    b.author,
                    bc.barcode
                FROM borrow_records br
                INNER JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
                INNER JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
                INNER JOIN books b ON bc.book_id = b.book_id
                WHERE br.user_id = ?
                ORDER BY br.borrow_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sách đang mượn (chưa trả)
    public function getCurrentBorrowsByUser($user_id)
    {
        $sql = "SELECT 
                    br.borrow_id,
                    br.borrow_date,
                    br.due_date,
                    br.status,
                    b.title,
                    b.author,
                    b.book_id,
                    bc.barcode,
                    DATEDIFF(br.due_date, CURDATE()) as days_remaining
                FROM borrow_records br
                INNER JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
                INNER JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
                INNER JOIN books b ON bc.book_id = b.book_id
                WHERE br.user_id = ? AND br.status != 'returned'
                ORDER BY br.due_date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin chi tiết lịch sử mượn (theo borrow_id)
    public function getBorrowDetails($borrow_id)
    {
        $sql = "SELECT 
                    br.borrow_id,
                    br.user_id,
                    br.borrow_date,
                    br.due_date,
                    br.return_date,
                    br.status,
                    b.title,
                    b.author,
                    bc.barcode
                FROM borrow_records br
                INNER JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
                INNER JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
                INNER JOIN books b ON bc.book_id = b.book_id
                WHERE br.borrow_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$borrow_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số sách đang mượn (chưa trả)
    public function getTotalBorrowingCount()
    {
        $sql = "SELECT COUNT(DISTINCT br.borrow_id) as total 
                FROM borrow_records br
                WHERE br.status != 'returned' AND br.return_date IS NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Lấy tổng số sách quá hạn
    public function getOverdueBooksCount()
    {
        $sql = "SELECT COUNT(DISTINCT br.borrow_id) as total 
                FROM borrow_records br
                WHERE br.status = 'overdue' AND br.return_date IS NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}