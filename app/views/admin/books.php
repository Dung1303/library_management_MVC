<?php require_once __DIR__ . '/../layouts/header.php'; ?>



<div class="admin-container">
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="admin-overview">
            <div class="overview-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>Book Management</h1>
                    <p>Track and manage your library's inventory</p>
                </div>
                <div class="d-flex gap-2">
                    <form action="<?= BASE_URL ?>/admin/import" method="POST" enctype="multipart/form-data">
                        <input type="file" name="excel_file" id="importExcelInput" class="d-none" accept=".xlsx, .xls"
                            onchange="this.form.submit()">
                        <button type="button" class="btn btn-success shadow-sm"
                            onclick="document.getElementById('importExcelInput').click()">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Import Excel
                        </button>
                    </form>
                    <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
                        <i class="bi bi-plus-circle me-2"></i>Add New Book
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Book Title</th>
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
                                        <td class="ps-4 fw-bold"><?= htmlspecialchars($book['title']) ?></td>
                                        <td><?= htmlspecialchars($book['author']) ?></td>
                                        <td><span
                                                class="badge bg-light text-dark border"><?= htmlspecialchars($book['category_name']) ?></span>
                                        </td>
                                        <td class="text-center"><?= $book['total_copies'] ?></td>
                                        <td class="text-center fw-bold"><?= $book['available'] ?></td>
                                        <td>
                                            <?php if ($book['available'] > 0): ?>
                                                <span class="text-success small"><i class="bi bi-dot fs-4"></i>In Stock</span>
                                            <?php else: ?>
                                                <span class="text-danger small"><i class="bi bi-dot fs-4"></i>Out of
                                                    Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary edit-btn"
                                                    data-id="<?= $book['book_id'] ?>"
                                                    data-title="<?= htmlspecialchars($book['title']) ?>"
                                                    data-author="<?= htmlspecialchars($book['author']) ?>"
                                                    data-cat="<?= $book['category_id'] ?>"
                                                    data-desc="<?= htmlspecialchars($book['Description'] ?? '') ?>"
                                                    data-bs-toggle="modal" data-bs-target="#editBookModal">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a href="<?= BASE_URL ?>/admin/deleteBook/<?= $book['book_id'] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this book and all its copies?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

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

                            <li
                                class="page-item <?= ($data['currentPage'] >= $data['totalPages']) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $data['currentPage'] + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD BOOK MODAL -->
<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="<?= BASE_URL ?>/admin/storeBook" method="POST" enctype="multipart/form-data"
            class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Add New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Author</label>
                        <input type="text" name="author" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($data['categories'] as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Book Cover Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Book</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT BOOK MODAL -->
<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="editBookForm" action="" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">Edit Book Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="book_id" id="edit_book_id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Author</label>
                        <input type="text" name="author" id="edit_author" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" id="edit_cat" class="form-select" required>
                            <?php foreach ($data['categories'] as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Change Cover Image (Optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" id="edit_desc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Book</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('editBookForm').action = `<?= BASE_URL ?>/admin/updateBook`;
            document.getElementById('edit_book_id').value = id;
            document.getElementById('edit_title').value = this.dataset.title;
            document.getElementById('edit_author').value = this.dataset.author;
            document.getElementById('edit_cat').value = this.dataset.cat;
            document.getElementById('edit_desc').value = this.dataset.desc;
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>