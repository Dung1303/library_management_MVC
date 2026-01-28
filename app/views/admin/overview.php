<div class="admin-overview">
    <div class="overview-header mb-4">
        <h1>System Overview</h1>
        <p>Summary statistics and reports</p>

    </div>

    <div class="stats-grid">
        <!-- Tổng đầu sách -->
        <div class="stat-card books">
            <div class="stat-icon books"><i class="bi bi-book-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total Book Titles</div>
                <div class="stat-value"><?php echo isset($totalBooks) ? $totalBooks : 0; ?></div>
                <div class="stat-description">Total Book Titles</div>
            </div>
        </div>

        <!-- Thành viên hoạt động -->
        <div class="stat-card members">
            <div class="stat-icon members"><i class="bi bi-people-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Active Members</div>
                <div class="stat-value"><?php echo isset($totalMembers) ? $totalMembers : 0; ?></div>
                <div class="stat-description">Total Members</div>
            </div>
        </div>

        <!-- Đang mượn -->
        <div class="stat-card borrowing">
            <div class="stat-icon borrowing"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-content">
                <div class="stat-label">Currently Borrowed</div>
                <div class="stat-value"><?php echo isset($newBooksThisMonth) ? $newBooksThisMonth : 0; ?></div>
                <div class="stat-description">Books Currently Borrowed</div>
            </div>
        </div>

        <!-- Quá hạn -->
        <div class="stat-card overdue">
            <div class="stat-icon overdue"><i class="bi bi-exclamation-circle-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Overdue</div>
                <div class="stat-value"><?php echo isset($overdueBooks) ? $overdueBooks : 0; ?></div>
                <div class="stat-description">Immediate Action Required</div>
            </div>
        </div>

        <!-- Bản sao có sẵn -->
        <div class="stat-card available">
            <div class="stat-icon available"><i class="bi bi-box-fill"></i></div>
            <div class="stat-content">
                <div class="stat-label">Available Copies</div>
                <div class="stat-value"><?php echo isset($availableBooks) ? $availableBooks : 0; ?></div>
                <div class="stat-description">Available for Borrowing</div>
            </div>
        </div>
    </div>
</div>