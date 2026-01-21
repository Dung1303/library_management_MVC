<?php
// Nạp thủ công Header
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="welcome-banner">
    <div class="container">
        <h1 class="welcome-title">Discover Your Next Great Read </h1>
        <p class="welcome-subtitle">Browse thousands of books from our collection</p>
    </div>
</section>

<section class="search-section">
    <div class="container">
        <div class="search-filter-wrapper">
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>

<section class="books-section">
    <div class="container">
        <?php if (empty($books)): ?>
        <div class="empty-message">
            <p>No books available in this category.</p>
        </div>
        <?php else: ?>
        <div class="books-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px;">
            <?php foreach ($books as $book): ?>
            <div class="book-card">
                <div class="book-image">
                    <img src="<?= BASE_URL ?>/assets/images/<?= $book['image_url'] ?>" alt="Cover" style="width:100%">

                    <?php if ($book['available'] <= 0): ?>
                    <div class="out-of-stock-badge">Out of Stock</div>
                    <?php endif; ?>
                </div>
                <div class="book-info">
                    <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                    <p class="book-author">By: <?= htmlspecialchars($book['author']) ?></p>
                    <span class="badge"><?= htmlspecialchars($book['category_name'] ?? 'General') ?></span>

                    <div class="stock-status">
                        Status: <?= $book['available'] ?> Available (AC 3)
                    </div>
                    <button class="btn-detail">View Details</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="page-btn">Previous</a>
            <?php endif; ?>

            <span>Page <?= $page ?> of <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="page-btn">Next</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
// Nạp thủ công Footer
require_once __DIR__ . '/../layouts/footer.php';
?>