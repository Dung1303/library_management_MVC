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
                 WHERE bc.book_id = b.book_id AND bc.status = 'available') as available
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
    public function getBooksHaveAvailable()
    {
        $sql = "
        SELECT DISTINCT b.book_id, b.title
        FROM books b
        INNER JOIN book_copies bc ON b.book_id = bc.book_id
        WHERE bc.status = 'available'
        ORDER BY b.title ASC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        try {
            $this->db->beginTransaction();

            // 1. Xóa tất cả các bản copy của sách này trước
            $sqlCopies = "DELETE FROM book_copies WHERE book_id = :id";
            $stmtCopies = $this->db->prepare($sqlCopies);
            $stmtCopies->bindValue(':id', $id);
            $stmtCopies->execute();

            // 2. Sau đó mới xóa sách trong bảng books
            $sqlBook = "DELETE FROM books WHERE book_id = :id";
            $stmtBook = $this->db->prepare($sqlBook);
            $stmtBook->bindValue(':id', $id);
            $result = $stmtBook->execute();

            $this->db->commit();
            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // 5. Import sách từ Excel (Xử lý Transaction)
    public function importBook($title, $author, $categoryName, $quantity, $description)
    {
        try {
            $this->db->beginTransaction();

            // A. Xử lý Category: Tìm ID theo tên, nếu chưa có thì tạo mới
            $stmt = $this->db->prepare("SELECT category_id FROM categories WHERE category_name = :name");
            $stmt->execute([':name' => $categoryName]);
            $cat = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cat) {
                $categoryId = $cat['category_id'];
            } else {
                $stmt = $this->db->prepare("INSERT INTO categories (category_name) VALUES (:name)");
                $stmt->execute([':name' => $categoryName]);
                $categoryId = $this->db->lastInsertId();
            }

            // B. Kiểm tra sách đã tồn tại chưa (Dựa trên Title và Author)
            $sqlCheck = "SELECT book_id FROM books WHERE title = :title AND author = :author LIMIT 1";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([
                ':title' => $title,
                ':author' => $author
            ]);
            $existingBook = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existingBook) {
                // Sách đã tồn tại -> Lấy ID cũ
                $bookId = $existingBook['book_id'];
            } else {
                // Sách chưa tồn tại -> Thêm mới vào bảng books
                $sql = "INSERT INTO books (title, author, category_id, Description, image_url) 
                        VALUES (:title, :author, :category_id, :description, '')";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':title' => $title,
                    ':author' => $author,
                    ':category_id' => $categoryId,
                    ':description' => $description
                ]);
                $bookId = $this->db->lastInsertId();
            }

            // C. Thêm các bản sao (Copies) dựa trên quantity
            $sqlCopy = "INSERT INTO book_copies (book_id, status) VALUES (:book_id, 'available')";
            $stmtCopy = $this->db->prepare($sqlCopy);
            $stmtCopy->bindValue(':book_id', $bookId);

            for ($i = 0; $i < $quantity; $i++) {
                $stmtCopy->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Import Error: " . $e->getMessage());
            return false;
        }
    }
}
