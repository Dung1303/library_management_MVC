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
                <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    <i class="bi bi-plus-circle me-2"></i>Add New Book
                </button>
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
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($book['category_name']) ?></span></td>
                                        <td class="text-center"><?= $book['total_copies'] ?></td>
                                        <td class="text-center fw-bold"><?= $book['available_copies'] ?></td>
                                        <td>
                                            <?php if ($book['available_copies'] > 0): ?>
                                                <span class="text-success small"><i class="bi bi-dot fs-4"></i>In Stock</span>
                                            <?php else: ?>
                                                <span class="text-danger small"><i class="bi bi-dot fs-4"></i>Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-secondary edit-btn"
                                                    data-id="<?= $book['book_id'] ?>" data-title="<?= $book['title'] ?>"
                                                    data-author="<?= $book['author'] ?>" data-cat="<?= $book['category_id'] ?>"
                                                    data-desc="<?= $book['Description'] ?>" data-bs-toggle="modal" data-bs-target="#editBookModal">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a href="<?= BASE_URL ?>/book/delete/<?= $book['book_id'] ?>"
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

                            <li class="page-item <?= ($data['currentPage'] >= $data['totalPages']) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $data['currentPage'] + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>