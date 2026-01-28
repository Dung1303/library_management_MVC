<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid admin-wrapper">
    <button class="btn btn-primary d-md-none position-fixed top-0 start-0 m-2 z-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
        <i class="bi bi-list"></i>
    </button>

    <div class="row">
        <div class="offcanvas-md offcanvas-start bg-light sidebar col-md-3 col-lg-2" tabindex="-1" id="adminSidebar">
            <div class="offcanvas-body pt-3 flex-column">
                <h5 class="sidebar-heading px-3 mt-2 mb-3 text-muted text-uppercase d-none d-md-block">Admin Panel</h5>
                <ul class="nav flex-column w-100">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/overview"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= BASE_URL ?>/book/adminIndex"><i class="bi bi-book me-2"></i> Book Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-people me-2"></i> Members</a></li>
                </ul>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-danger fw-bold">Book Management</h1>
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    <i class="bi bi-plus-lg me-2"></i>Add New Book
                </button>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search by title, author or category...">
                </div>
            </div>

            <div class="table-responsive bg-white shadow-sm rounded">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Available</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['books'] as $book): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($book['category_name']) ?></span></td>
                                <td class="text-center"><?= $book['total_copies'] ?></td>
                                <td class="text-center"><?= $book['available_copies'] ?></td>
                                <td>
                                    <?php if ($book['available_copies'] > 0): ?>
                                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>In Stock</span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Out of Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary me-1 edit-btn"
                                        data-id="<?= $book['book_id'] ?>" data-title="<?= $book['title'] ?>"
                                        data-author="<?= $book['author'] ?>" data-cat="<?= $book['category_id'] ?>"
                                        data-desc="<?= $book['Description'] ?>" data-bs-toggle="modal" data-bs-target="#editBookModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="<?= BASE_URL ?>/book/delete/<?= $book['book_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this book and all its copies?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= BASE_URL ?>/book/store" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Title</label><input type="text" name="title" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Author</label><input type="text" name="author" class="form-control" required></div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <?php foreach ($data['categories'] as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= $cat['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label">Quantity</label><input type="number" name="quantity" class="form-control" value="1" min="1"></div>
                    <div class="col-12"><label class="form-label">Cover Image</label><input type="file" name="image" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Book</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Book Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Title</label><input type="text" name="title" id="edit_title" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Author</label><input type="text" name="author" id="edit_author" class="form-control" required></div>
                    <div class="col-12">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="edit_cat" class="form-select">
                            <?php foreach ($data['categories'] as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>"><?= $cat['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Update Cover (Optional)</label><input type="file" name="image" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Description</label><textarea name="description" id="edit_desc" class="form-control" rows="3"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<script>
    // Script đổ dữ liệu vào Modal Edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('editForm').action = `<?= BASE_URL ?>/book/update/${id}`;
            document.getElementById('edit_title').value = this.dataset.title;
            document.getElementById('edit_author').value = this.dataset.author;
            document.getElementById('edit_cat').value = this.dataset.cat;
            document.getElementById('edit_desc').value = this.dataset.desc;
        });
    });
</script>