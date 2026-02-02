<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="admin-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <?php
        // Hiển thị thông báo thành công hoặc lỗi
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['success']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['error']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['error']);
        }
        ?>

        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Book Copy Management</h2>
                <p class="text-muted">Manage each individual copy of the books</p>
            </div>
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addCopyModal">
                <i class="bi bi-plus-lg me-2"></i>Add Copy
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Barcode</th>
                            <th>Book Title</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['copies'])): ?>
                            <?php foreach ($data['copies'] as $copy): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= htmlspecialchars($copy['barcode'] ?? 'N/A') ?></td>

                                    <td><?= htmlspecialchars($copy['title']) ?></td>

                                    <td>
                                        <?php
                                        $status = strtolower($copy['status']);
                                        $badgeClass = 'bg-secondary';
                                        if ($status == 'available') $badgeClass = 'bg-success';
                                        if ($status == 'borrowed') $badgeClass = 'bg-warning text-dark';
                                        if ($status == 'damaged') $badgeClass = 'bg-danger';
                                        if ($status == 'lost') $badgeClass = 'bg-dark';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> px-3 py-2">
                                            <?= ucfirst($copy['status']) ?>
                                        </span>
                                    </td>

                                    <td><?= date('d/m/Y H:i', strtotime($copy['created_at'] ?? 'now')) ?></td>

                                    <td class="text-center pe-4">
                                        <button class="btn btn-sm btn-outline-secondary edit-copy-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCopyModal"
                                            data-copy-id="<?= $copy['book_copy_id'] ?>"
                                            data-status="<?= $copy['status'] ?>"
                                            data-barcode="<?= htmlspecialchars($copy['barcode'] ?? 'N/A') ?>"
                                            data-title="<?= htmlspecialchars($copy['title']) ?>"
                                            title="Edit Status">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= BASE_URL ?>/admin/deleteCopy/<?= $copy['book_copy_id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this copy? This action cannot be undone.')" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">No copies found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <?php if ($data['totalPages'] > 1): ?>
                <div class="card-footer bg-white py-3 border-0">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?= ($data['currentPage'] <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $data['currentPage'] - 1 ?>">Previous</a>
                            </li>

                            <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                <li class="page-item <?= ($i == $data['currentPage']) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= ($data['currentPage'] >= $data['totalPages']) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $data['currentPage'] + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ADD COPY MODAL -->
<div class="modal fade" id="addCopyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= BASE_URL ?>/admin/storeCopy" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Book Copy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="book_id" class="form-label">Select Book</label>
                    <select name="book_id" id="book_id" class="form-select" required>
                        <option value="">-- Choose a book --</option>
                        <?php foreach ($data['books'] as $book): ?>
                            <option value="<?= $book['book_id'] ?>"><?= htmlspecialchars($book['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="form-text">A new copy will be created with 'available' status and a unique barcode.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Copy</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT COPY MODAL -->
<div class="modal fade" id="editCopyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editCopyForm" action="<?= BASE_URL ?>/admin/updateCopy" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Copy Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="book_copy_id" id="edit_copy_id">
                <div class="mb-2">
                    <label class="form-label text-muted">Book Title</label>
                    <p class="form-control-plaintext pt-0" id="edit_book_title"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Barcode</label>
                    <p class="fw-bold" id="edit_barcode"></p>
                </div>
                <div class="mb-3">
                    <label for="edit_status" class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-select" required>
                        <option value="available">Available</option>
                        <option value="damaged">Damaged</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <p class="form-text">The 'borrowed' status is managed automatically by the borrowing system.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editCopyModal = document.getElementById('editCopyModal');
        if (editCopyModal) {
            editCopyModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const copyId = button.getAttribute('data-copy-id');
                const status = button.getAttribute('data-status');
                const barcode = button.getAttribute('data-barcode');
                const title = button.getAttribute('data-title');

                const modalForm = editCopyModal.querySelector('form');
                modalForm.querySelector('#edit_copy_id').value = copyId;
                modalForm.querySelector('#edit_status').value = status;

                // Cập nhật thông tin sách và barcode vào modal
                editCopyModal.querySelector('#edit_book_title').textContent = title;
                editCopyModal.querySelector('#edit_barcode').textContent = barcode;
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>