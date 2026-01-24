<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h2 class="mb-4 text-primary"><i class="bi bi-person-circle"></i> Trang cá nhân</h2>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Thông tin cơ bản</h5>
                            <form action="<?= BASE_URL ?>/user/update" method="POST">
                                <div class="mb-3">
                                    <label class="small text-muted">Họ và tên</label>
                                    <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small text-muted">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm text-white bg-dark">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Bảo mật tài khoản</h5>
                            <form action="<?= BASE_URL ?>/user/changePassword" method="POST">
                                <div class="mb-3">
                                    <input type="password" name="current_password" class="form-control bg-secondary text-white border-0" placeholder="Mật khẩu hiện tại" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="new_password" class="form-control bg-secondary text-white border-0" placeholder="Mật khẩu mới" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="confirm_password" class="form-control bg-secondary text-white border-0" placeholder="Xác nhận mật khẩu mới" required>
                                </div>
                                <button type="submit" class="btn btn-light w-100">Cập nhật mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>