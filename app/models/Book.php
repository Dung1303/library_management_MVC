<?php
class Book extends Model
{
    public function getAllBooks($limit = 15, $offset = 0)
    {
        // SQL JOIN để lấy thông tin sách, tên danh mục và đếm số bản sao
        $sql = "SELECT b.*, c.category_name, 
                (SELECT COUNT(*) FROM book_copies bc 
                 WHERE bc.book_id = b.book_id) as total_copies,
                (SELECT COUNT(*) FROM book_copies bc 
                 WHERE bc.book_id = b.book_id AND bc.status = 'available') as available_copies
                FROM books b
                LEFT JOIN categories c ON b.category_id = c.category_id
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalBooksCount()
    {
        $sql = "SELECT COUNT(*) as total FROM books";
        $result = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalCopiesCount()
    {
        $sql = "SELECT COUNT(*) as total FROM book_copies";
        $result = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getAvailableCopiesCount()
    {
        $sql = "SELECT COUNT(*) as total FROM book_copies WHERE status = 'available'";
        $result = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getBookById($id)
    {
        $sql = "SELECT b.*, c.category_name, 
                (SELECT COUNT(*) FROM book_copies bc 
                 WHERE bc.book_id = b.book_id AND bc.status = 'available') as available
                FROM books b
                LEFT JOIN categories c ON b.category_id = c.category_id
                WHERE b.book_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // 1. Thêm sách mới
    public function addBook($data)
    {
        try {
            $sql = "INSERT INTO books (title, author, category_id, Description, image_url) 
                    VALUES (:title, :author, :category_id, :description, :image_url)";

            error_log("SQL Query: " . $sql);

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':title', $data['title']);
            $stmt->bindValue(':author', $data['author']);
            $stmt->bindValue(':category_id', $data['category_id']);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':image_url', $data['image_url']);

            error_log("Executing query...");

            if ($stmt->execute()) {
                $lastId = $this->db->lastInsertId();
                error_log("Query successful! Last Insert ID: " . $lastId);
                return $lastId; // Trả về ID sách vừa tạo
            }
            error_log("Query failed!");
            return false;
        } catch (PDOException $e) {
            error_log("PDOException: " . $e->getMessage());
            return false;
        }
    }

    // 2. Tạo các bản sao sách (Book Copies) dựa trên số lượng nhập vào
    public function addBookCopies($bookId, $quantity)
    {
        $sql = "INSERT INTO book_copies (book_id, status) VALUES (:book_id, 'available')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':book_id', $bookId);

        for ($i = 0; $i < $quantity; $i++) {
            $stmt->execute();
        }
    }

    // 3. Cập nhật thông tin sách
    public function updateBook($data)
    {
        try {
            $sql = "UPDATE books SET title = :title, author = :author, 
                    category_id = :category_id, Description = :description";

            // Chỉ cập nhật ảnh nếu người dùng upload ảnh mới
            if (!empty($data['image_url'])) {
                $sql .= ", image_url = :image_url";
            }

            $sql .= " WHERE book_id = :book_id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':title', $data['title']);
            $stmt->bindValue(':author', $data['author']);
            $stmt->bindValue(':category_id', $data['category_id']);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':book_id', $data['book_id']);

            if (!empty($data['image_url'])) {
                $stmt->bindValue(':image_url', $data['image_url']);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // 4. Xóa sách
    public function deleteBook($id)
    {
        $sql = "DELETE FROM books WHERE book_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}