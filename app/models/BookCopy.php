<?php
// app/models/BookCopy.php

class BookCopy extends Model
{
    // Lấy danh sách bản sao còn available theo sách
    public function getAvailableByBook($book_id)
    {
        $stmt = $this->db->prepare("
            SELECT book_copy_id, barcode
            FROM book_copies
            WHERE book_id = ? AND status = 'available'
        ");
        $stmt->execute([$book_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái bản sao
    public function updateStatus($copy_id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE book_copies
            SET status = ?
            WHERE book_copy_id = ?
        ");
        return $stmt->execute([$status, $copy_id]);
    }
}
