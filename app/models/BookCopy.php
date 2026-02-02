<?php
class BookCopy extends Model
{
    // Lấy toàn bộ bản sao kèm tên sách để hiển thị danh sách
    public function getAllCopies($limit = 15, $offset = 0)
    {
        $sql = "SELECT bc.*, b.title 
                FROM book_copies bc
                JOIN books b ON bc.book_id = b.book_id
                ORDER BY bc.book_copy_id DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số bản sao
    public function countAllCopies()
    {
        $sql = "SELECT COUNT(*) FROM book_copies";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Thêm bản sao mới (Mặc định là 'available')
    public function addCopy($book_id, $status = 'available')
    {
        try {
            $this->db->beginTransaction();

            // Đếm số lượng bản sao hiện có của sách này để xác định số thứ tự tiếp theo.
            $countSql = "SELECT COUNT(*) FROM book_copies WHERE book_id = :book_id";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute([':book_id' => $book_id]);
            $currentCopyCount = (int)$countStmt->fetchColumn();

            $sql = "INSERT INTO book_copies (book_id, status) VALUES (:book_id, :status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':book_id', $book_id);
            $stmt->bindValue(':status', $status);
            $stmt->execute();

            $copyId = $this->db->lastInsertId();
            $copyNumber = $currentCopyCount + 1;
            $barcode = "BC-{$book_id}-{$copyNumber}";

            $sqlUpdate = "UPDATE book_copies SET barcode = :barcode WHERE book_copy_id = :copy_id";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->execute([':barcode' => $barcode, ':copy_id' => $copyId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in BookCopy::addCopy: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật trạng thái của bản sao (Available, Damaged, Lost)
    public function updateStatus($copy_id, $status)
    {
        $sql = "UPDATE book_copies SET status = :status WHERE book_copy_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $copy_id);
        return $stmt->execute();
    }

    // Xóa một bản sao
    public function deleteCopy($id)
    {
        $sql = "DELETE FROM book_copies WHERE book_copy_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // Lấy thông tin một bản sao bằng ID
    public function getCopyById($id)
    {
        $sql = "SELECT bc.*, b.title 
                FROM book_copies bc
                JOIN books b ON bc.book_id = b.book_id
                WHERE bc.book_copy_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
