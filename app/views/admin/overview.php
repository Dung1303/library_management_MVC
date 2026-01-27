<div class="admin-overview">
    <div class="overview-header mb-4">
        <h1>Tổng quan hệ thống</h1>
        <p>Thống kê và báo cáo tổng hợp</p>
    </div>

    <div class="stats-grid">
        <!-- Tổng đầu sách -->
        <div class="stat-card books">
            <div class="stat-icon books"><i class="bi bi-book-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Tổng đầu sách</div>
                <div class="stat-value"><?php echo isset($totalBooks) ? $totalBooks : 0; ?></div>
                <div class="stat-description">Trong hệ thống</div>
            </div>
        </div>

        <!-- Thành viên hoạt động -->
        <div class="stat-card members">
            <div class="stat-icon members"><i class="bi bi-people-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Thành viên hoạt động</div>
                <div class="stat-value"><?php echo isset($totalMembers) ? $totalMembers : 0; ?></div>
                <div class="stat-description">Tổng thành viên</div>
            </div>
        </div>

        <!-- Đang mượn -->
        <div class="stat-card borrowing">
            <div class="stat-icon borrowing"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-content">
                <div class="stat-label">Đang mượn</div>
                <div class="stat-value"><?php echo isset($newBooksThisMonth) ? $newBooksThisMonth : 0; ?></div>
                <div class="stat-description">Cuốn sách đang được mượn</div>
            </div>
        </div>

        <!-- Quá hạn -->
        <div class="stat-card overdue">
            <div class="stat-icon overdue"><i class="bi bi-exclamation-circle-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Quá hạn</div>
                <div class="stat-value"><?php echo isset($overdueBooks) ? $overdueBooks : 0; ?></div>
                <div class="stat-description">Cần xử lý ngay</div>
            </div>
        </div>

        <!-- Bản sao có sẵn -->
        <div class="stat-card available">
            <div class="stat-icon available"><i class="bi bi-box-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Bản sao có sẵn</div>
                <div class="stat-value"><?php echo isset($availableBooks) ? $availableBooks : 0; ?></div>
                <div class="stat-description">Sẵn sàng cho mượn</div>
            </div>
        </div>
    </div>
</div>