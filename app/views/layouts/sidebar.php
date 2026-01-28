<?php
// File này giờ chỉ chứa sidebar tĩnh cho trang admin
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/admin/dashboard" class="sidebar-brand">
            <i class="bi bi-speedometer2"></i>
            <span>Admin Panel</span>
        </a>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-link active">
                <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo BASE_URL; ?>/admin/books" class="sidebar-link active>
                <i class=" bi bi-book"></i><span>Quản lý Sách</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo BASE_URL; ?>/admin/members" class="sidebar-link active>
                <i class=" bi bi-people"></i><span>Quản lý Thành viên</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo BASE_URL; ?>/admin/borrow" class="sidebar-link active>
                <i class=" bi bi-arrow-left-right"></i><span>Quản lý Mượn/Trả</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo BASE_URL; ?>/admin/overdue" class="sidebar-link active>
                <i class=" bi bi-exclamation-circle"></i><span>Sách Quá hạn</span>
            </a>
        </li>
    </ul>
</aside>