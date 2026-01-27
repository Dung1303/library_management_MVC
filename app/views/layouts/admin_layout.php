<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản Lý Thư Viện</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <!-- Overview CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/overview.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin_layout.css">

</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h3><i class="bi bi-speedometer2"></i> Admin Panel</h3>
            <ul>
                <li>
                    <a href="<?php echo BASE_URL; ?>/admin/overview" class="active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>/admin/books">
                        <i class="bi bi-book"></i> Quản lý Sách
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>/admin/members">
                        <i class="bi bi-people"></i> Quản lý Thành viên
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>/admin/borrow">
                        <i class="bi bi-arrow-left-right"></i> Quản lý Mượn/Trả
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>/admin/overdue">
                        <i class="bi bi-exclamation-circle"></i> Sách Quá hạn
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <?php 
            // Include view file được truyền từ controller

if (isset($contentView) && file_exists($contentView)) {
    include $contentView;
} else {
    echo '<p class="text-danger">Không tìm thấy file view</p>';
}
?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>