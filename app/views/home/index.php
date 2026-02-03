<?php
// Nạp thủ công Header
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="welcome-banner welcome-banner-with-bg">
    <div class="container">
        <h1 class="welcome-title">Discover Your Next Great Read </h1>
        <p class="welcome-subtitle">Browse thousands of books from our collection</p>
    </div>
</section>

<section class="search-section">
    <div class="container">
        <form action="<?= BASE_URL ?>/home/index" method="GET" class="search-form-container"
            style="display: flex; gap: 1rem; align-items: center; background: #f8f9fa; padding: 1rem; border-radius: 8px;">
            <input type="text" name="keyword" placeholder="Search by title, author..."
                value="<?= htmlspecialchars($keyword ?? '') ?>"
                style="flex-grow: 1; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 4px;">

            <select name="category_id" id="categoryFilter"
                style="padding: 0.75rem; border: 1px solid #ced4da; border-radius: 4px;">
                <option value="">All Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>"
                        <?= (isset($category_id) && $category_id == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">Search</button>
        </form>
    </div>
</section>

<section class="books-section">
    <div class="container">
        <?php if (empty($books)): ?>
            <div class="empty-message">
                <p>Không tìm thấy sách nào phù hợp.</p>
            </div>
        <?php else: ?>
            <div class="books-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px;">
                <?php foreach ($books as $book): ?>
                    <div class="book-card">
                        <div class="book-image">
                            <?php
                            $imgName = $book['image_url'] ?? 'default-book.png';
                            // Logic kiểm tra ảnh: Ưu tiên Uploads -> Assets -> Mặc định
                            if (!empty($imgName) && file_exists(__DIR__ . '/../../../public/uploads/books/' . $imgName)) {
                                $imgPath = 'uploads/books/' . $imgName;
                            } elseif (!empty($imgName) && file_exists(__DIR__ . '/../../../public/assets/images/' . $imgName)) {
                                $imgPath = 'assets/images/' . $imgName;
                            } else {
                                $imgPath = 'assets/images/default-book.png';
                            }
                            ?>
                            <img src="<?= BASE_URL ?>/<?= $imgPath ?>" alt="<?= htmlspecialchars($book['title']) ?>"
                                style="width:100%">
                            <?php if ($book['available'] <= 0): ?>
                                <div class="out-of-stock-badge">Out of Stock</div>
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                            <p class="book-author">By: <?= htmlspecialchars($book['author']) ?></p>
                            <span class="badge"><?= htmlspecialchars($book['category_name'] ?? 'General') ?></span>

                            <div class="stock-status">
                                Status: <?= $book['available'] ?> Available
                            </div>
                            <a href="<?= BASE_URL ?>/book/detail/<?= $book['book_id'] ?>" class="btn-detail"
                                style="text-align:center; text-decoration:none; display:block;">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pagination">
                <?php
                // Giữ lại các tham số filter khi chuyển trang
                $queryParams = [];
                if (!empty($keyword)) $queryParams['keyword'] = $keyword;
                if (!empty($category_id)) $queryParams['category_id'] = $category_id;
                ?>
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($queryParams, ['page' => $page - 1])) ?>"
                        class="page-btn">Previous</a>
                <?php endif; ?>

                <span>Page <?= $page ?> of <?= $totalPages ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($queryParams, ['page' => $page + 1])) ?>"
                        class="page-btn">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
// Nạp thủ công Footer
require_once __DIR__ . '/../layouts/footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                // Khi người dùng chọn một danh mục, tự động submit form để lọc
                this.form.submit();
            });
        }
    });
</script>