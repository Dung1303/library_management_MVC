<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="admin-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="admin-overview">
            <div class="overview-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-danger fw-bold">Book Management</h1>
                    <p class="text-muted">Inventory tracking and management</p>
                </div>
                <button class="btn btn-dark px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    <i class="bi bi-plus-lg me-2"></i>Add New Book
                </button>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Book Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Available</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['books'] as $book): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($book['title']) ?></td>
                                    <td><?= htmlspecialchars($book['author']) ?></td>
                                    <td><span class="badge bg-light text-secondary border"><?= htmlspecialchars($book['category_name']) ?></span></td>
                                    <td class="text-center"><?= $book['total_copies'] ?></td>
                                    <td class="text-center fw-bold text-primary"><?= $book['available_copies'] ?></td>
                                    <td>
                                        <?php if ($book['available_copies'] > 0): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">In Stock</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Out of Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-sm btn-outline-primary border-0 edit-btn"
                                            data-id="<?= $book['book_id'] ?>" data-title="<?= $book['title'] ?>"
                                            data-author="<?= $book['author'] ?>" data-cat="<?= $book['category_id'] ?>"
                                            data-desc="<?= $book['Description'] ?>" data-bs-toggle="modal" data-bs-target="#editBookModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= BASE_URL ?>/book/delete/<?= $book['book_id'] ?>"
                                            class="btn btn-sm btn-outline-danger border-0"
                                            onclick="return confirm('Are you sure? This will delete all copies of this book.')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white py-3 border-top-0">
                    <nav>
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?= ($data['currentPage'] <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link border-0" href="?page=<?= $data['currentPage'] - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                <li class="page-item <?= ($i == $data['currentPage']) ? 'active' : '' ?>">
                                    <a class="page-link rounded-circle mx-1 border-0" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($data['currentPage'] >= $data['totalPages']) ? 'disabled' : '' ?>">
                                <a class="page-link border-0" href="?page=<?= $data['currentPage'] + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="<?= BASE_URL ?>/book/store" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Add New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small fw-bold">Title</label><input type="text" name="title" class="form-control bg-light border-0" required></div>
                    <div class="col-md-6"><label class="form-label small fw-bold">Author</label><input type="text" name="author" class="form-control bg-light border-0" required></div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category</label>
                        <select name="category_id" class="form-select bg-light border-0">
                            <?php foreach ($data['categories'] as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= $cat['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label small fw-bold">Stock Quantity</label><input type="number" name="quantity" class="form-control bg-light border-0" value="1" min="1"></div>
                    <div class="col-12"><label class="form-label small fw-bold">Book Cover</label><input type="file" name="image" class="form-control bg-light border-0"></div>
                    <div class="col-12"><label class="form-label small fw-bold">Description</label><textarea name="description" class="form-control bg-light border-0" rows="3"></textarea></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark">Confirm & Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">Edit Book Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small fw-bold">Title</label><input type="text" name="title" id="edit_title" class="form-control bg-light border-0" required></div>
                    <div class="col-md-6"><label class="form-label small fw-bold">Author</label><input type="text" name="author" id="edit_author" class="form-control bg-light border-0" required></div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Category</label>
                        <select name="category_id" id="edit_cat" class="form-select bg-light border-0">
                            <?php foreach ($data['categories'] as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= $cat['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label small fw-bold">Change Cover Image (Optional)</label><input type="file" name="image" class="form-control bg-light border-0"></div>
                    <div class="col-12"><label class="form-label small fw-bold">Description</label><textarea name="description" id="edit_desc" class="form-control bg-light border-0" rows="3"></textarea></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Book</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            // Cập nhật URL action của Form
            document.getElementById('editForm').action = `<?= BASE_URL ?>/book/update/${id}`;
            // Điền dữ liệu cũ vào các ô Input
            document.getElementById('edit_title').value = this.dataset.title;
            document.getElementById('edit_author').value = this.dataset.author;
            document.getElementById('edit_cat').value = this.dataset.cat;
            document.getElementById('edit_desc').value = this.dataset.desc;
        });
    });
</script>