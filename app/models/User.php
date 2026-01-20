<?php
// app/models/User.php

class User extends Model
{

    // Hàm tạo user mới (register)
    public function createUser($username, $password, $full_name, $email)
    {
        // Hash password bằng bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Chuẩn bị query insert vào table users
        $stmt = $this->db->prepare("
            INSERT INTO users (username, password, full_name, email, role, status) 
            VALUES (?, ?, ?, ?, 'member', 'active')
        ");

        // Bind params và execute
        $stmt->bind_param("ssss", $username, $hashedPassword, $full_name, $email);
        $result = $stmt->execute();

        if ($result) {
            // Lấy user_id mới insert
            $user_id = $this->db->insert_id;

            // Insert notification cho register (như dữ liệu mẫu)
            $this->createNotification($user_id, 'register');

            return true;
        }

        return false;
    }

    // Hàm lấy user bằng username (cho login)
    public function getUserByUsername($username)
    {
        // Chuẩn bị query select
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Trả về row nếu tồn tại
        return $result->fetch_assoc();
    }

    // Hàm kiểm tra username tồn tại chưa (cho register validation)
    public function usernameExists($username)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Hàm kiểm tra email tồn tại chưa (cho register validation)
    public function emailExists($email)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Hàm insert notification (private, dùng nội bộ)
    private function createNotification($user_id, $type)
    {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, sent_at, status) 
            VALUES (?, ?, CURRENT_TIMESTAMP, 'sent')
        ");
        $stmt->bind_param("is", $user_id, $type);
        $stmt->execute();
    }
}
