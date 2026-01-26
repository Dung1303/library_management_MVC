<?php
class AdminController extends Controller
{
    public function dashboard()
    {
        // Kiểm tra bảo mật: Phải đăng nhập và là admin mới được vào
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            // Nếu không phải admin thì đá về trang chủ hoặc login
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        // Gọi view dashboard
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - LibraSys'
        ]);
    }
}
