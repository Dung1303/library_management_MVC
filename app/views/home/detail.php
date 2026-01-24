<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <div class="book-detail-card"
        style="display: flex; gap: 40px; background: var(--white); padding: 30px; border-radius: 10px; box-shadow: var(--shadow); flex-wrap: wrap;">
        <!-- Cột ảnh sách -->
        <div class="detail-image" style="flex: 0 0 300px; max-width: 100%;">
            <img src="<?= BASE_URL ?>/assets/images/<?= $book['image_url'] ?>"
                alt="<?= htmlspecialchars($book['title']) ?>"
                style="width: 100%; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); object-fit: cover;">
        </div>

        <!-- Cột thông tin -->
        <div class="detail-info" style="flex: 1; min-width: 300px;">
            <h1 style="color: var(--text-dark); margin-bottom: 10px; font-size: 2rem;">
                <?= htmlspecialchars($book['title']) ?></h1>

            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 20px;">
                Tác giả: <strong style="color: var(--text-dark);"><?= htmlspecialchars($book['author']) ?></strong>
            </p>

            <div style="margin-bottom: 20px;">
                <span class="badge"
                    style="font-size: 0.9rem; padding: 8px 12px;"><?= htmlspecialchars($book['category_name'] ?? 'General') ?></span>
            </div>

            <div style="margin-bottom: 20px;">
                <h5 style="color: var(--text-dark); font-size: 1.1rem; font-weight: bold; margin-bottom: 8px;">Mô tả
                </h5>
                <p style="color: var(--text-muted); line-height: 1.6; font-size: 1rem;">
                    <?= nl2br(htmlspecialchars($book['Description'] ?? 'Chưa có mô tả cho cuốn sách này.')) ?></p>
            </div>

            <div class="stock-status"
                style="font-size: 1.1rem; margin-bottom: 30px; padding: 15px; background: var(--bg-light); border-radius: 8px; display: inline-block;">
                Trạng thái:
                <?php if ($book['available'] > 0): ?>
                    <span style="color: var(--success); font-weight: bold;"><?= $book['available'] ?> bản có sẵn</span>
                <?php else: ?>
                    <span style="color: var(--danger); font-weight: bold;">Hết hàng</span>
                <?php endif; ?>
            </div>

            <div class="actions" style="margin-top: 20px;">
                <a href="<?= BASE_URL ?>/home"
                    style="text-decoration: none; color: var(--text-muted); font-weight: 500; font-size: 1rem;">
                    &larr; Quay lại trang chủ
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>