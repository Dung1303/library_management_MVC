<?php
class Category extends Model
{
    /**
     * Lấy danh sách tất cả danh mục để hiển thị ở Dropdown lọc
     */
    public function getAllCategories()
    {
        try {
            $sql = "SELECT category_id, category_name FROM categories ORDER BY category_name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log lỗi nếu cần
            return [];
        }
    }
}