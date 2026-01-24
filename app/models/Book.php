<?php
class Book extends Model
{
    public function getAllBooks($limit = 15, $offset = 0)
    {
        // SQL JOIN để lấy thông tin sách, tên danh mục và đếm số bản sao có sẵn
        $sql = "SELECT b.*, c.category_name, 
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
}
