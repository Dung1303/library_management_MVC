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
// Lấy toàn bộ lịch sử mượn (Admin)
public function getAllBorrowHistory()
{
    $sql = "SELECT 
                br.borrow_id,
                u.full_name,
                b.title,
                bc.book_copy_id,
                bc.barcode,
                br.borrow_date,
                br.due_date,
                br.return_date,
                br.status
            FROM borrow_records br
            JOIN users u ON br.user_id = u.user_id
            JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
            JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
            JOIN books b ON bc.book_id = b.book_id
            ORDER BY br.borrow_date DESC";

    return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
    // Lấy tất cả phiếu đang mượn (Admin)
public function getAllActiveBorrows()
{
    $sql = "SELECT 
                br.borrow_id,
                u.full_name,
                b.title,
                bc.book_copy_id,
                bc.barcode,
                br.borrow_date,
                br.due_date,
                br.status
            FROM borrow_records br
            JOIN users u ON br.user_id = u.user_id
            JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
            JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
            JOIN books b ON bc.book_id = b.book_id
            WHERE br.status != 'returned'
            ORDER BY br.due_date ASC";

    return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Admin tạo phiếu mượn
public function createBorrow($user_id, $borrow_date, $due_date)
{
    $stmt = $this->db->prepare(
        "INSERT INTO borrow_records (user_id, borrow_date, due_date, status)
         VALUES (?, ?, ?, 'borrowed')"
    );
    $stmt->execute([$user_id, $borrow_date, $due_date]);
    return $this->db->lastInsertId();
}

public function addBorrowCopy($borrow_id, $book_copy_id)
{
    $stmt = $this->db->prepare(
        "INSERT INTO borrow_records_book_copies (borrow_id, book_copy_id, is_returned)
         VALUES (?, ?, 0)"
    );
    return $stmt->execute([$borrow_id, $book_copy_id]);
}

// Trả sách
public function returnBook($borrow_id, $book_copy_id)
{
    try {
        $this->db->beginTransaction();

        $this->db->prepare(
            "UPDATE borrow_records_book_copies 
             SET is_returned = 1 
             WHERE borrow_id = ? AND book_copy_id = ?"
        )->execute([$borrow_id, $book_copy_id]);

        $this->db->prepare(
            "UPDATE book_copies 
             SET status = 'available' 
             WHERE book_copy_id = ?"
        )->execute([$book_copy_id]);

        $this->db->prepare(
            "UPDATE borrow_records 
             SET status = 'returned', return_date = NOW()
             WHERE borrow_id = ?"
        )->execute([$borrow_id]);

        $this->db->commit();
    } catch (Exception $e) {
        $this->db->rollBack();
        throw $e;
    }
}
public function searchByMemberName($keyword)
{
    $sql = "
        SELECT 
            br.borrow_id,
            u.full_name,
            b.title,
            bc.book_copy_id,
            bc.barcode,
            br.borrow_date,
            br.due_date,
            br.status
        FROM borrow_records br
        JOIN users u ON br.user_id = u.user_id
        JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
        JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
        JOIN books b ON bc.book_id = b.book_id
        WHERE u.full_name LIKE :keyword
        ORDER BY br.borrow_date DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':keyword' => '%' . $keyword . '%'
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}