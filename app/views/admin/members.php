<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-borrow.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-members.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script>window.BASE_URL = '<?= BASE_URL ?>';</script>
<script src="<?= BASE_URL ?>/assets/js/admin-members.js" defer></script>

<div class="borrow-container">

    <!-- Success Notification -->
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success" id="successAlert" style="margin-bottom: 20px; padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <i class="bi bi-check-circle-fill"></i>
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <button type="button" onclick="closeAlert()" style="background: none; border: none; color: #155724; font-size: 20px; cursor: pointer; padding: 0; margin-left: 15px;">
            ×
        </button>
    </div>
    <script>
        // Auto-hide alert after 4 seconds
        setTimeout(function() {
            closeAlert();
        }, 4000);
        
        function closeAlert() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.3s ease-out';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            }
        }
    </script>
    <?php unset($_SESSION['success_message']); endif; ?>

    <div class="borrow-header">
        <a href="<?= BASE_URL ?>/admin/index" class="btn-back">
            ← Back to Dashboard
        </a>

        <div class="borrow-title">
            <h2>
                <i class="bi bi-people-fill"></i>
                Member Management
            </h2>
        </div>

        <div class="borrow-actions">
            <div class="borrow-search">
                <input type="text" id="searchInput" placeholder="Search by name, email...">
                <button type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            <a href="#" onclick="openAddForm()" class="btn-create">
                <i class="bi bi-plus-circle"></i>
                Add Member
            </a>
        </div>
    </div>

    <table class="borrow-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="membersTableBody">
            <?php foreach ($data['members'] as $index => $m): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($m['full_name']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= htmlspecialchars($m['username']) ?></td>
                <td>
                    <?php if ($m['status'] === 'active'): ?>
                    <span class="badge badge-success">Active</span>
                    <?php else: ?>
                    <span class="badge badge-danger">Locked</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-edit" onclick="openEditForm(<?= htmlspecialchars(json_encode($m)) ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="<?= BASE_URL ?>/admin/member/toggle/<?= $m['user_id'] ?>" class="<?= $m['status'] === 'active' ? 'btn-lock' : 'btn-unlock' ?>">
                            <i class="bi <?= $m['status'] === 'active' ? 'bi-lock-fill' : 'bi-unlock-fill' ?>"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($data['totalPages'] > 1): ?>
    <div class="pagination" style="display: flex; justify-content: center; gap: 8px; margin-top: 30px; flex-wrap: wrap;">
        <!-- Previous Button -->
        <?php if ($data['currentPage'] > 1): ?>
            <a href="<?= BASE_URL ?>/admin/members?page=<?= $data['currentPage'] - 1 ?>" class="pagination-btn" style="padding: 8px 12px; border: 1px solid #3b82f6; color: #3b82f6; border-radius: 5px; text-decoration: none; cursor: pointer;">
                ← Previous
            </a>
        <?php else: ?>
            <span class="pagination-btn" style="padding: 8px 12px; border: 1px solid #ccc; color: #999; border-radius: 5px; cursor: not-allowed;">
                ← Previous
            </span>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php 
        $startPage = max(1, $data['currentPage'] - 2);
        $endPage = min($data['totalPages'], $data['currentPage'] + 2);
        
        if ($startPage > 1): ?>
            <a href="<?= BASE_URL ?>/admin/members?page=1" class="pagination-btn" style="padding: 8px 12px; border: 1px solid #3b82f6; color: #3b82f6; border-radius: 5px; text-decoration: none;">1</a>
            <?php if ($startPage > 2): ?>
                <span style="padding: 8px 12px; color: #666;">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <?php if ($i == $data['currentPage']): ?>
                <span class="pagination-btn active" style="padding: 8px 12px; border: 1px solid #3b82f6; background-color: #3b82f6; color: white; border-radius: 5px;">
                    <?= $i ?>
                </span>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/admin/members?page=<?= $i ?>" class="pagination-btn" style="padding: 8px 12px; border: 1px solid #3b82f6; color: #3b82f6; border-radius: 5px; text-decoration: none;">
                    <?= $i ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($endPage < $data['totalPages']): ?>
            <?php if ($endPage < $data['totalPages'] - 1): ?>
                <span style="padding: 8px 12px; color: #666;">...</span>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/admin/members?page=<?= $data['totalPages'] ?>" class="pagination-btn" style="padding: 8px 12px; border: 1px solid #3b82f6; color: #3b82f6; border-radius: 5px; text-decoration: none;">
                <?= $data['totalPages'] ?>
            </a>
        <?php endif; ?>

        <!-- Next Button -->
        <?php if ($data['currentPage'] < $data['totalPages']): ?>
            <a href="<?= BASE_URL ?>/admin/members?page=<?= $data['currentPage'] + 1 ?>" class="pagination-btn" style="padding: 8px 12px; border: 1px solid #3b82f6; color: #3b82f6; border-radius: 5px; text-decoration: none; cursor: pointer;">
                Next →
            </a>
        <?php else: ?>
            <span class="pagination-btn" style="padding: 8px 12px; border: 1px solid #ccc; color: #999; border-radius: 5px; cursor: not-allowed;">
                Next →
            </span>
        <?php endif; ?>
    </div>

    <!-- Pagination Info -->
    <div style="text-align: center; margin-top: 15px; color: #666; font-size: 14px;">
        Showing page <?= $data['currentPage'] ?> of <?= $data['totalPages'] ?> | Total members: <?= $data['totalMembers'] ?>
    </div>
    <?php endif; ?>

</div>

<!-- ADD/EDIT FORM (POPUP) -->
<div id="memberFormPopup" class="borrow-form" style="display: none;">
    <h3 id="formTitle">Add New Member</h3>
    <p class="subtitle" id="formSubtitle">Create a new member account</p>

    <form id="memberForm" method="POST" action="">
        <input type="hidden" id="formAction" name="action" value="add">
        <input type="hidden" id="userId" name="user_id" value="">
        <input type="hidden" id="formMethod" name="form_method" value="add">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="fullName" name="fullname" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group" id="passwordGroup">
            <label>Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn-block">
            <i class="bi bi-check-circle"></i> Save
        </button>
    </form>

    <button onclick="closeForm()" style="position: absolute; top: 16px; right: 16px; background: transparent; border: none; font-size: 24px; cursor: pointer;">×</button>
</div>

<!-- Background overlay -->
<div id="formOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9998;" onclick="closeForm()"></div>