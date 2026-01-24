<?php
// app/controllers/AuthController.php

class AuthController extends Controller
{

    // Method cho register (LMS-141)
    public function register()
    {
        // Nếu đã login, redirect về home dựa trên role
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                header('Location: /library_management_system/public/admin/dashboard');
            } else {
                header('Location: /library_management_system/public/member/home');
            }
            exit;
        }

        // Nếu form submit (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Load model User
            $userModel = $this->model('User');

            // Validation cơ bản
            if (empty($username) || empty($password) || empty($full_name) || empty($email) || empty($confirm_password)) {
                $error = "All fields are required.";
            } elseif ($password !== $confirm_password) {
                $error = "Password and confirm password do not match.";
            } elseif ($userModel->usernameExists($username)) {
                $error = "Username already exists.";
            } elseif ($userModel->emailExists($email)) {
                $error = "Email already exists.";
            } else {
                // Tạo user mới
                if ($userModel->createUser($username, $password, $full_name, $email)) {
                    // Redirect về login sau register thành công
                    header('Location: /library_management_system/public/auth/login?success=registered');
                    exit;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }

            // Truyền error về view nếu có
            $this->view('auth/register', ['error' => $error]);
        } else {
            // Hiển thị form register (GET)
            $this->view('auth/register');
        }
    }

    // Method cho login (LMS-139)
    public function login()
    {
        // Nếu đã login, redirect về home dựa trên role
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                header('Location: /library_management_system/public/admin/dashboard');
            } else {
                header('Location: /library_management_system/public/home');
            }
            exit;
        }

        // Nếu form submit (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Load model User
            $userModel = $this->model('User');

            // Validation cơ bản
            if (empty($username) || empty($password)) {
                $error = "Username and password are required.";
            } else {
                // Lấy user
                $user = $userModel->getUserByUsername($username);

                // Kiểm tra password
                if ($user && password_verify($password, $user['password']) && $user['status'] === 'active') {
                // Set session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                    // Redirect dựa trên role
                    if ($user['role'] === 'admin') {
                        header("Location: " . BASE_URL . "/admin/dashboard");
                    } else {
                        header("Location: " . BASE_URL . "/home");
                    }
                    exit;
                } else {
                    $error = "Invalid username or password, or account is locked.";
                }
            }

            // Truyền error về view
            $this->view('auth/login', ['error' => $error]);
        } else {
            // Hiển thị form login (GET)
            $this->view('auth/login');
        }
    }

    // Method cho logout (LMS-140)
    public function logout()
    {
        // Destroy session
        session_destroy();

        // Redirect về login
        header("Location: " . BASE_URL . "/auth/login");
        exit;
    }
}