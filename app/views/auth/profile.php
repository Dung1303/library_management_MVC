<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Link CSS riêng cho trang Profile -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/profile.css">

    <div class="profile-header text-center">
        <div class="container">
            <h2 class="fw-bold mb-0">My Profile</h2>
            <p class="opacity-75">Manage your account settings and security</p>
        </div>
    </div>

    <div class="container mb-5 mt-n4rem">
        <div class="row justify-content-center">
            <!-- Sidebar / User Info -->
            <div class="col-lg-4 mb-4">
                <div class="card profile-card h-100">
                    <div class="card-body text-center p-5">
                        <div class="avatar-circle">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-1"><?= htmlspecialchars($user['full_name']) ?></h4>
                        <p class="text-muted mb-3"><?= htmlspecialchars($user['email']) ?></p>
                        <span class="badge bg-light text-primary border border-primary rounded-pill px-3 py-2">
                            <?= ucfirst(htmlspecialchars($user['role'])) ?>
                        </span>
                        <hr class="my-4">
                        <div class="d-grid gap-2 text-start">
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-person-badge me-3 fs-5"></i>
                                <div>
                                    <small class="d-block">Username</small>
                                    <span class="text-dark fw-medium"><?= htmlspecialchars($user['username']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Forms -->
            <div class="col-lg-8">
                <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Edit Profile -->
                <div class="card profile-card mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="card-title fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Basic
                            Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>/user/update" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Full Name</label>
                                    <input type="text" name="full_name" class="form-control form-control-lg fs-6"
                                        value="<?= htmlspecialchars($user['full_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control form-control-lg fs-6"
                                        value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-medium">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card profile-card">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="card-title fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Security</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= BASE_URL ?>/user/changePassword" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Current Password</label>
                                <input type="password" name="current_password" class="form-control form-control-lg fs-6"
                                    placeholder="••••••••" required>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">New Password</label>
                                    <input type="password" name="new_password" class="form-control form-control-lg fs-6"
                                        placeholder="••••••••" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Confirm New Password</label>
                                    <input type="password" name="confirm_password"
                                        class="form-control form-control-lg fs-6" placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-primary px-4 py-2 fw-medium">Update
                                    Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>