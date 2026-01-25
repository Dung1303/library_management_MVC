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
        <span>Borrowed Books & Borrowing History</span>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5 gy-4">
        <div class="col-md-4 col-sm-6">
            <div class="card text-center shadow-sm h-100 stat-card stat-card-primary">
                <div class="card-body py-4">
                    <div class="stat-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="card-title">Total Borrows</h5>
                    <p class="card-text stat-number stat-number-primary"><?php echo $totalBorrows; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center shadow-sm h-100 stat-card stat-card-purple">
                <div class="card-body py-4">
                    <div class="stat-icon">
                        <i class="bi bi-bookmark-check"></i>
                    </div>
                    <h5 class="card-title">Currently Borrowed</h5>
                    <p class="card-text stat-number stat-number-purple"><?php echo count($currentBorrows); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card text-center shadow-sm h-100 stat-card stat-card-danger">
                <div class="card-body py-4">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <h5 class="card-title">Overdue</h5>
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
                    <i class="bi bi-bookmark-star section-card-icon"></i>
                    <h5 class="card-title section-card-title">Currently Borrowed Books</h5>
                    <p class="text-muted mb-2"><?php echo count($currentBorrows); ?> books</p>
                    <p class="text-muted mb-0"><small>Click to view details</small></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 cursor-pointer section-card section-transition" data-section="history">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clock-history section-card-icon"></i>
                    <h5 class="card-title section-card-title-history">Borrowing History</h5>
                    <p class="text-muted mb-2"><?php echo count($borrowHistory); ?> records</p>
                    <p class="text-muted mb-0"><small>Click to view details</small></p>
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
                    Currently Borrowed Books
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($currentBorrows)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i> You currently have no borrowed books.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Barcode</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Days Remaining</th>
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
                                        $statusText = 'Overdue';
                                    } else {
                                        $statusClass = 'badge bg-success';
                                        $statusText = 'Borrowed';
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
                                                echo $daysRemaining . ' days';
                                            } elseif ($daysRemaining == 0) {
                                                echo 'Today';
                                            } else {
                                                echo abs($daysRemaining) . ' days overdue';
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
                    Borrowing History
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($borrowHistory)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i> You have no borrowing history yet.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Barcode</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
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
                                            $statusText = 'Returned';
                                            break;
                                        case 'overdue':
                                            $statusClass = 'badge bg-danger';
                                            $statusText = 'Overdue';
                                            break;
                                        default:
                                            $statusClass = 'badge bg-success';
                                            $statusText = 'Borrowed';
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
                                                echo '<span class="text-muted">Not returned</span>';
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