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
    // Lấy toàn bộ lịch sử mượn (Admin) - có phân trang
    public function getAllBorrowHistory($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT 
                br.borrow_id,
                u.full_name,
                GROUP_CONCAT(DISTINCT b.title SEPARATOR ', ') as titles,
                GROUP_CONCAT(DISTINCT bc.barcode SEPARATOR ', ') as barcodes,
                br.borrow_date,
                br.due_date,
                br.return_date,
                br.status
            FROM borrow_records br
            JOIN users u ON br.user_id = u.user_id
            LEFT JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
            LEFT JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
            LEFT JOIN books b ON bc.book_id = b.book_id
            GROUP BY br.borrow_id, u.full_name, br.borrow_date, br.due_date, br.return_date, br.status
            ORDER BY br.borrow_date DESC
            LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số lịch sử mượn
    public function countAllBorrowHistory()
    {
        $sql = "SELECT COUNT(DISTINCT br.borrow_id) as total FROM borrow_records br";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
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
    // Lấy tất cả phiếu đang mượn (Admin) - có phân trang
    public function getAllActiveBorrows($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT 
                br.borrow_id,
                u.full_name,
                GROUP_CONCAT(DISTINCT b.title SEPARATOR ', ') as titles,
                GROUP_CONCAT(DISTINCT bc.barcode SEPARATOR ', ') as barcodes,
                br.borrow_date,
                br.due_date,
                br.status
            FROM borrow_records br
            JOIN users u ON br.user_id = u.user_id
            LEFT JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
            LEFT JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
            LEFT JOIN books b ON bc.book_id = b.book_id
            WHERE br.status != 'returned'
            GROUP BY br.borrow_id, u.full_name, br.borrow_date, br.due_date, br.status
            ORDER BY br.due_date ASC
            LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số phiếu đang mượn
    public function countAllActiveBorrows()
    {
        $sql = "SELECT COUNT(DISTINCT br.borrow_id) as total FROM borrow_records br WHERE br.status != 'returned'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Admin tạo phiếu mượn (Có Transaction an toàn)
    public function createBorrowWithCopies($user_id, $borrow_date, $due_date, $copyIds)
    {
        try {
            $this->db->beginTransaction();

            // 1. Tạo phiếu mượn
            $stmt = $this->db->prepare(
                "INSERT INTO borrow_records (user_id, borrow_date, due_date, status)
                 VALUES (?, ?, ?, 'borrowed')"
            );
            $stmt->execute([$user_id, $borrow_date, $due_date]);
            $borrowId = $this->db->lastInsertId();

            // 2. Thêm chi tiết mượn và Cập nhật trạng thái sách
            $sqlInsert = "INSERT INTO borrow_records_book_copies (borrow_id, book_copy_id, is_returned) VALUES (?, ?, 0)";
            $stmtInsert = $this->db->prepare($sqlInsert);

            $sqlUpdate = "UPDATE book_copies SET status = 'borrowed' WHERE book_copy_id = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);

            foreach ($copyIds as $copyId) {
                if (!empty($copyId) && is_numeric($copyId)) {
                    $stmtInsert->execute([$borrowId, $copyId]);
                    $stmtUpdate->execute([$copyId]);
                }
            }

            $this->db->commit();
            return $borrowId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Trả sách
    public function returnBorrow($borrow_id)
    {
        try {
            $this->db->beginTransaction();

            // 1. Lấy danh sách các bản sao sách trong phiếu này mà chưa trả
            $stmt = $this->db->prepare("
                SELECT book_copy_id 
                FROM borrow_records_book_copies 
                WHERE borrow_id = ? AND is_returned = 0
            ");
            $stmt->execute([$borrow_id]);
            $copies = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($copies)) {
                // 2. Cập nhật trạng thái sách -> 'available' (Cộng lại số lượng vật lý)
                // Tạo chuỗi placeholder (?,?,?)
                $placeholders = implode(',', array_fill(0, count($copies), '?'));
                $sqlBookCopies = "UPDATE book_copies SET status = 'available' WHERE book_copy_id IN ($placeholders)";
                $this->db->prepare($sqlBookCopies)->execute($copies);

                // 3. Đánh dấu trong bảng chi tiết là đã trả
                // Chỉ cập nhật những cuốn vừa tìm thấy (để an toàn hơn)
                $sqlMarkReturned = "UPDATE borrow_records_book_copies SET is_returned = 1 WHERE borrow_id = ? AND book_copy_id IN ($placeholders)";
                // Merge mảng tham số: [borrow_id, copy1, copy2...]
                $params = array_merge([$borrow_id], $copies);
                $this->db->prepare($sqlMarkReturned)->execute($params);
            }

            // 4. Cập nhật trạng thái phiếu mượn -> 'returned'
            $this->db->prepare(
                "UPDATE borrow_records SET status = 'returned', return_date = NOW() WHERE borrow_id = ?"
            )->execute([$borrow_id]);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    public function searchByMemberName($keyword, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $sql = "
        SELECT 
            br.borrow_id,
            u.full_name,
            GROUP_CONCAT(DISTINCT b.title SEPARATOR ', ') as titles,
            GROUP_CONCAT(DISTINCT bc.barcode SEPARATOR ', ') as barcodes,
            br.borrow_date,
            br.due_date,
            br.status
        FROM borrow_records br
        JOIN users u ON br.user_id = u.user_id
        LEFT JOIN borrow_records_book_copies brbc ON br.borrow_id = brbc.borrow_id
        LEFT JOIN book_copies bc ON brbc.book_copy_id = bc.book_copy_id
        LEFT JOIN books b ON bc.book_id = b.book_id
        WHERE u.full_name LIKE :keyword AND br.status != 'returned'
        GROUP BY br.borrow_id, u.full_name, br.borrow_date, br.due_date, br.status
        ORDER BY br.borrow_date DESC
        LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm số lượng phiếu mượn theo tìm kiếm
    public function countSearchByMemberName($keyword)
    {
        $sql = "
        SELECT COUNT(DISTINCT br.borrow_id) as total 
        FROM borrow_records br
        JOIN users u ON br.user_id = u.user_id
        WHERE u.full_name LIKE :keyword AND br.status != 'returned'
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
