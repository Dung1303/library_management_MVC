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
        $result = $stmt->execute([$username, $hashedPassword, $full_name, $email]);

        if ($result) {
            // Lấy user_id mới insert
            $user_id = $this->db->lastInsertId();

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
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Trả về row nếu tồn tại
        return $result;
    }

    // Hàm lấy user bằng user_id (cho profile)
    public function getUserById($user_id)
    {
        // Chuẩn bị query select
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Trả về row nếu tồn tại
        return $result;
    }

    // Hàm kiểm tra username tồn tại chưa (cho register validation)
    public function usernameExists($username)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        return $stmt->rowCount() > 0;
    }

    // Hàm kiểm tra email tồn tại chưa (cho register validation)
    public function emailExists($email, $exclude_user_id = null)
    {
        if ($exclude_user_id) {
            // Nếu có user_id để exclude (update profile)
            $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
            $stmt->execute([$email, $exclude_user_id]);
        } else {
            // Nếu không (register)
            $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
        }
        
        return $stmt->rowCount() > 0;
    }

    // Hàm cập nhật profile user
    public function updateProfile($user_id, $full_name, $email)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET full_name = ?, email = ? 
            WHERE user_id = ?
        ");
        
        return $stmt->execute([$full_name, $email, $user_id]);
    }

    // Hàm lấy tổng số users (thành viên hoạt động)
    public function getTotalUsersCount()
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'member'";
        $result = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Hàm cập nhật mật khẩu user
    public function updatePassword($user_id, $hashed_password)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password = ? 
            WHERE user_id = ?
        ");
        
        return $stmt->execute([$hashed_password, $user_id]);
    }

    // Hàm insert notification (private, dùng nội bộ)
    private function createNotification($user_id, $type)
    {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, sent_at, status) 
            VALUES (?, ?, CURRENT_TIMESTAMP, 'sent')
        ");
        $stmt->execute([$user_id, $type]);
    }
}