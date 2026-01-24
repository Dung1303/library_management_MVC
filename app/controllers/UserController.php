<?php
// app/controllers/UserController.php

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        // Sử dụng hàm model() từ core/Controller.php để load model User
        $this->userModel = $this->model('User');
    }

    // Method mặc định khi truy cập /user hoặc /user/index
    public function index() {
        $this->profile();
    }

    // Xử lý hiển thị Profile: /user/profile
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        // Load view từ app/views/auth/profile.php
        $this->view('auth/profile', [
            'user' => $user
        ]);
    }

    // Xử lý cập nhật thông tin: /user/update
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_SESSION['user_id'];
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);

            if ($this->userModel->emailExists($email, $id)) {
                $_SESSION['error'] = "Email đã tồn tại!";
            } else {
                if ($this->userModel->updateProfile($id, $full_name, $email)) {
                    $_SESSION['success'] = "Cập nhật thông tin thành công!";
                }
            }
            header("Location: " . BASE_URL . "/user/profile");
            exit;
            header("Location: " . $baseUrl . "/user/profile");
            exit;
        }
    }

    // Xử lý đổi mật khẩu: /user/changePassword
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_SESSION['user_id'];
            $current_pass = $_POST['current_password'];
            $new_pass = $_POST['new_password'];
            $confirm_pass = $_POST['confirm_password'];

            $user = $this->userModel->getUserById($id);

            if (!password_verify($current_pass, $user['password'])) {
                $_SESSION['error'] = "Mật khẩu hiện tại không chính xác";
            } elseif ($new_pass !== $confirm_pass) {
                $_SESSION['error'] = "Mật khẩu mới không khớp";
            } else {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $this->userModel->updatePassword($id, $hashed);
                $_SESSION['success'] = "Đổi mật khẩu thành công!";
            }
            header("Location: " . BASE_URL . "/user/profile");
            exit;
        }
    }
}