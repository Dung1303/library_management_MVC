<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LibraSys' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/borrowed_books.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/assets/images/logo.jpg" alt="LibraSys Logo" height="40" class="me-2">
                <span class="fw-bold">LibraSys</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= BASE_URL ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/member/borrowedBooks">Borrowed Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/user/profile">Profile</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="text-end">
                        <div class="fw-semibold">
                            <?= htmlspecialchars($_SESSION['username']); ?>
                        </div>
                        <small class="text-muted"><?= ucfirst($_SESSION['role'] ?? 'Member'); ?></small>
                    </div>

                    <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-danger btn-sm">
                        Logout
                    </a>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>/auth/login" class="btn btn-primary btn-sm">
                        Login
                    </a>
                    <a href="<?= BASE_URL ?>/auth/register" class="btn btn-outline-primary btn-sm">
                        Register
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>