<?php
// app/views/admin/borrowing.php
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-borrow.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="borrow-container">

    <div class="borrow-header">

        <!-- Back -->
        <a href="<?= BASE_URL ?>/admin/index" class="btn-back">
            ← Back to Dashboard
        </a>

        <!-- Title -->
        <div class="borrow-title">
            <h2>
                <i class="bi bi-arrow-left-right"></i>
                Borrow / Return Management
            </h2>
        </div>

        <!-- Actions row -->
        <div class="borrow-actions">

            <!-- Tabs -->
            <div class="borrow-tabs">
                <a href="<?= BASE_URL ?>/borrow/index" class="btn active">
                    Borrowing
                </a>

                <a href="<?= BASE_URL ?>/borrow/history" class="btn">
                    History
                </a>
            </div>

            <!-- Right -->
            <div class="borrow-right">

                <!-- Search -->
                <form method="GET" class="borrow-search">
                    <input type="text" name="keyword" placeholder="Search by member name..."
                        value="<?= htmlspecialchars($keyword ?? '') ?>">
                    <button type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <!-- Create -->
                <a href="<?= BASE_URL ?>/borrow/create" class="btn-create">
                    <i class="bi bi-plus-circle"></i>
                    Create Borrow Slip
                </a>

            </div>
        </div>
    </div>

    <!-- ================= CURRENT BORROWS ================= -->
    <table class="borrow-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Member</th>
                <th>Books</th>
                <th>Barcodes</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($borrows as $b): ?>
            <tr class="<?= $b['status'] === 'overdue' ? 'overdue' : '' ?>">
                <td><?= $b['borrow_id'] ?></td>
                <td><?= htmlspecialchars($b['full_name']) ?></td>
                <td><?= htmlspecialchars($b['titles']) ?></td>
                <td><?= htmlspecialchars($b['barcodes']) ?></td>
                <td><?= $b['borrow_date'] ?></td>
                <td><?= $b['due_date'] ?></td>
                <td>
                    <?php if ($b['status'] === 'overdue'): ?>
                    <span class="badge badge-danger">Overdue</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Borrowing</span>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="post" action="<?= BASE_URL ?>/borrow/returnBook">
                        <input type="hidden" name="borrow_id" value="<?= $b['borrow_id'] ?>">
                        <input type="hidden" name="book_copy_id" value="<?= $b['barcodes'] ?>">
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle"></i> Return
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- ================= PAGINATION ================= -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <!-- Previous -->
        <?php if ($page > 1): ?>
            <a href="<?= BASE_URL ?>/borrow/index?page=<?= $page - 1 ?><?= !empty($keyword) ? '&keyword=' . urlencode($keyword) : '' ?>" class="btn-page">← Previous</a>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="page-current"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/borrow/index?page=<?= $i ?><?= !empty($keyword) ? '&keyword=' . urlencode($keyword) : '' ?>" class="btn-page"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <a href="<?= BASE_URL ?>/borrow/index?page=<?= $page + 1 ?><?= !empty($keyword) ? '&keyword=' . urlencode($keyword) : '' ?>" class="btn-page">Next →</a>
        <?php endif; ?>
    </div>
    <div class="pagination-info">
        Showing page <?= $page ?> of <?= $totalPages ?> (Total: <?= $total ?> records)
    </div>
    <?php endif; ?>

</div>