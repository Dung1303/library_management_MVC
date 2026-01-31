<?php
// app/views/admin/borrow.php
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
                <a href="<?= BASE_URL ?>/borrow/index" class="btn <?= $mode === 'list' ? 'active' : '' ?>">
                    Borrowing
                </a>

                <a href="<?= BASE_URL ?>/borrow/history" class="btn <?= $mode === 'history' ? 'active' : '' ?>">
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
    <?php if ($mode === 'list'): ?>

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

    <!-- ================= BORROW HISTORY ================= -->
    <?php elseif ($mode === 'history'): ?>

    <table class="borrow-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Member</th>
                <th>Books</th>
                <th>Barcodes</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($borrows as $b): ?>
            <tr>
                <td><?= $b['borrow_id'] ?></td>
                <td><?= htmlspecialchars($b['full_name']) ?></td>
                <td><?= htmlspecialchars($b['titles']) ?></td>
                <td><?= htmlspecialchars($b['barcodes']) ?></td>
                <td><?= $b['borrow_date'] ?></td>
                <td><?= $b['due_date'] ?></td>
                <td><?= $b['return_date'] ?? '-' ?></td>
                <td>
                    <?php if ($b['status'] === 'returned'): ?>
                    <span class="badge badge-success">Returned</span>
                    <?php elseif ($b['status'] === 'overdue'): ?>
                    <span class="badge badge-danger">Overdue</span>
                    <?php else: ?>
                    <span class="badge badge-warning">Borrowing</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- ================= CREATE BORROW ================= -->
    <?php elseif ($mode === 'create'): ?>
    <form style="max-height: 90vh; overflow: auto;" class="borrow-form" method="post" onsubmit="return validateForm()">
        <h3>Create New Borrow Slip</h3>
        <p class="subtitle">Assign multiple books to a member</p>

        <!-- Member -->
        <div class="form-group">
            <label>Select Member</label>
            <select name="user_id" id="memberSelect" required>
                <option value="">-- Select Member --</option>
                <?php foreach ($members as $m): ?>
                <option value="<?= $m['user_id'] ?>">
                    <?= $m['full_name'] ?> (<?= $m['email'] ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Book Selection -->
        <div class="form-group">
            <label>Select Books</label>
            <select name="book_id" id="bookSelect">
                <option value="">-- Select Book --</option>
                <?php foreach ($books as $b): ?>
                <option value="<?= $b['book_id'] ?>">
                    <?= $b['title'] ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Copy Selection -->
        <div class="form-group">
            <label>Select Copy</label>
            <select id="copySelect">
                <option value="">-- Select Copy --</option>
            </select>
            <button type="button" id="addCopyBtn" class="btn btn-secondary btn-sm">
                <i class="bi bi-plus"></i> Add Book
            </button>
        </div>

        <!-- Selected Books List -->
        <div class="form-group">
            <label>Books in this slip:</label>
            <div id="selectedBooks" class="selected-books-list">
                <p class="empty-message">No books selected yet</p>
            </div>
            <input type="hidden" id="bookCopiesHidden" name="book_copy_ids" value="">
        </div>

        <!-- Due date -->
        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="bi bi-plus-circle"></i> Create
        </button>
    </form>

    <script>
    let selectedBooks = [];

    // Load copies khi chọn book
    document.getElementById('bookSelect')?.addEventListener('change', function() {
        const bookId = this.value;
        const copySelect = document.getElementById('copySelect');

        if (!bookId) {
            copySelect.innerHTML = '<option value="">-- Select Copy --</option>';
            return;
        }

        copySelect.innerHTML = '<option value="">Loading...</option>';

        fetch('<?= BASE_URL ?>/borrow/getCopies/' + bookId)
            .then(res => res.json())
            .then(data => {
                copySelect.innerHTML = '<option value="">-- Select Copy --</option>';
                data.forEach(c => {
                    copySelect.innerHTML +=
                        `<option value="${c.book_copy_id}" data-barcode="${c.barcode}" data-title="${c.title || 'Unknown'}">${c.barcode}</option>`;
                });
            });
    });

    // Thêm sách vào danh sách
    document.getElementById('addCopyBtn')?.addEventListener('click', function() {
        const copySelect = document.getElementById('copySelect');
        const bookSelect = document.getElementById('bookSelect');
        const copyId = copySelect.value;
        const barcode = copySelect.options[copySelect.selectedIndex]?.dataset.barcode || '';
        const title = bookSelect.options[bookSelect.selectedIndex]?.text || 'Unknown';

        if (!copyId) {
            alert('Vui lòng chọn một quyển sách');
            return;
        }

        // Kiểm tra trùng lặp
        if (selectedBooks.some(b => b.id === copyId)) {
            alert('Quyển sách này đã được thêm');
            return;
        }

        selectedBooks.push({
            id: copyId,
            barcode: barcode,
            title: title
        });

        updateSelectedBooksDisplay();
        copySelect.value = '';
    });

    // Hiển thị danh sách sách đã chọn
    function updateSelectedBooksDisplay() {
        const container = document.getElementById('selectedBooks');
        const hidden = document.getElementById('bookCopiesHidden');

        if (selectedBooks.length === 0) {
            container.innerHTML = '<p class="empty-message">No books selected yet</p>';
            hidden.value = '';
            return;
        }

        container.innerHTML = selectedBooks.map((book, idx) => `
            <div class="selected-book-item">
                <span>${book.title} (${book.barcode})</span>
                <button type="button" class="btn-remove" onclick="removeBook(${idx})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `).join('');

        hidden.value = selectedBooks.map(b => b.id).join(',');
    }

    // Xóa sách khỏi danh sách
    function removeBook(idx) {
        selectedBooks.splice(idx, 1);
        updateSelectedBooksDisplay();
    }

    // Validate form trước submit
    function validateForm() {
        if (selectedBooks.length === 0) {
            alert('Vui lòng chọn ít nhất 1 quyển sách');
            return false;
        }
        return true;
    }
    </script>

    <?php endif; ?>

</div>