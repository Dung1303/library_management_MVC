<?php
// app/views/member/borrowed_books.php
require_once __DIR__ . '/../layouts/header.php';

// Tính toán thống kê
$totalBorrows = count($currentBorrows) + count($borrowHistory);
$overdueCount = 0;
foreach ($currentBorrows as $borrow) {
    if ($borrow['status'] == 'overdue') {
        $overdueCount++;
    }
}
?>

<div class="container my-5">
    <div class="page-title">
        <i class="bi bi-book-half"></i>
        <span>Sách Đang Mượn & Lịch Sử Mượn</span>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5 gy-4">
        <div class="col-md-4 col-sm-6">
            <div class="card text-center shadow-sm h-100 stat-card-primary">
                <div class="card-body py-4">
                    <div class="stat-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="card-title">Tổng Mượn</h5>
                    <p class="card-text stat-number stat-number-primary"><?php echo $totalBorrows; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center shadow-sm h-100 stat-card-purple">
                <div class="card-body py-4">
                    <div class="stat-icon">
                        <i class="bi bi-bookmark-check"></i>
                    </div>
                    <h5 class="card-title">Đang Mượn</h5>
                    <p class="card-text stat-number stat-number-purple"><?php echo count($currentBorrows); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center shadow-sm h-100 stat-card-danger">
                <div class="card-body py-4">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <h5 class="card-title">Quá Hạn</h5>
                    <p class="card-text stat-number stat-number-danger"><?php echo $overdueCount; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Cards -->
    <div class="row mb-5 gy-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 cursor-pointer section-card section-transition" data-section="current">
                <div class="card-body text-center py-5">
                    <i class="bi bi-bookmark-star"
                        style="font-size: 28px; color: #4f46e5; margin-bottom: 12px; display: block;"></i>
                    <h5 class="card-title section-card-title">Sách Đang Mượn</h5>
                    <p class="text-muted mb-2"><?php echo count($currentBorrows); ?> cuốn sách</p>
                    <p class="text-muted mb-0"><small>Click để xem chi tiết</small></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 cursor-pointer section-card section-transition" data-section="history">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clock-history"
                        style="font-size: 28px; color: #7c3aed; margin-bottom: 12px; display: block;"></i>
                    <h5 class="card-title section-card-title-history">Lịch Sử Mượn</h5>
                    <p class="text-muted mb-2"><?php echo count($borrowHistory); ?> bản ghi</p>
                    <p class="text-muted mb-0"><small>Click để xem chi tiết</small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    <div id="current-section" class="section-content">
        <div class="card shadow-sm border-0">
            <div class="card-header card-header-section">
                <h5>
                    <i class="bi bi-bookmark-check"></i>
                    Sách Đang Mượn
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($currentBorrows)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i> Bạn hiện không có sách nào đang mượn.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên Sách</th>
                                <th>Tác Giả</th>
                                <th>Mã Barcode</th>
                                <th>Ngày Mượn</th>
                                <th>Ngày Trả Hạn</th>
                                <th>Tình Trạng</th>
                                <th>Còn Lại</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($currentBorrows as $index => $borrow): ?>
                            <?php
                                    // Xác định màu status
                                    $statusClass = '';
                                    $statusText = '';
                                    $daysRemaining = $borrow['days_remaining'] ?? 0;

                                    if ($borrow['status'] == 'overdue') {
                                        $statusClass = 'badge bg-danger';
                                        $statusText = 'Quá Hạn';
                                    } else {
                                        $statusClass = 'badge bg-success';
                                        $statusText = 'Đang Mượn';
                                    }

                                    // Màu cảnh báo ngày còn lại
                                    $daysClass = '';
                                    if ($daysRemaining <= 3 && $daysRemaining > 0) {
                                        $daysClass = 'text-warning fw-bold';
                                    } elseif ($daysRemaining <= 0) {
                                        $daysClass = 'text-danger fw-bold';
                                    } else {
                                        $daysClass = 'text-success';
                                    }
                                    ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><strong><?php echo htmlspecialchars($borrow['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($borrow['author']); ?></td>
                                <td><code><?php echo htmlspecialchars($borrow['barcode']); ?></code></td>
                                <td><?php echo date('d/m/Y', strtotime($borrow['borrow_date'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($borrow['due_date'])); ?></td>
                                <td><span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                <td><span class="<?php echo $daysClass; ?>">
                                        <?php 
                                            if ($daysRemaining > 0) {
                                                echo $daysRemaining . ' ngày';
                                            } elseif ($daysRemaining == 0) {
                                                echo 'Hôm nay';
                                            } else {
                                                echo abs($daysRemaining) . ' ngày quá hạn';
                                            }
                                            ?>
                                    </span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="history-section" class="section-content" style="display: none;">
        <div class="card shadow-sm border-0">
            <div class="card-header card-header-section">
                <h5>
                    <i class="bi bi-clock-history"></i>
                    Lịch Sử Mượn
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($borrowHistory)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i> Bạn chưa có lịch sử mượn sách nào.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên Sách</th>
                                <th>Tác Giả</th>
                                <th>Mã Barcode</th>
                                <th>Ngày Mượn</th>
                                <th>Ngày Trả Hạn</th>
                                <th>Ngày Trả Thực Tế</th>
                                <th>Tình Trạng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($borrowHistory as $index => $history): ?>
                            <?php
                                    // Xác định màu status
                                    $statusClass = '';
                                    $statusText = '';

                                    switch ($history['status']) {
                                        case 'returned':
                                            $statusClass = 'badge bg-secondary';
                                            $statusText = 'Đã Trả';
                                            break;
                                        case 'overdue':
                                            $statusClass = 'badge bg-danger';
                                            $statusText = 'Quá Hạn';
                                            break;
                                        default:
                                            $statusClass = 'badge bg-success';
                                            $statusText = 'Đang Mượn';
                                    }
                                    ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><strong><?php echo htmlspecialchars($history['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($history['author']); ?></td>
                                <td><code><?php echo htmlspecialchars($history['barcode']); ?></code></td>
                                <td><?php echo date('d/m/Y', strtotime($history['borrow_date'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($history['due_date'])); ?></td>
                                <td>
                                    <?php 
                                            if ($history['return_date']) {
                                                echo date('d/m/Y', strtotime($history['return_date']));
                                            } else {
                                                echo '<span class="text-muted">Chưa trả</span>';
                                            }
                                            ?>
                                </td>
                                <td><span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.section-card').forEach(card => {
    card.addEventListener('click', function() {
        const section = this.getAttribute('data-section');

        // Remove active class from all cards
        document.querySelectorAll('.section-card').forEach(c => c.classList.remove('active'));

        // Add active class to clicked card
        this.classList.add('active');

        // Hide all sections
        document.querySelectorAll('.section-content').forEach(s => s.style.display = 'none');

        // Show selected section
        document.getElementById(section + '-section').style.display = 'block';
    });
});

// Set the first card as active on load
window.addEventListener('load', function() {
    document.querySelector('.section-card').click();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>