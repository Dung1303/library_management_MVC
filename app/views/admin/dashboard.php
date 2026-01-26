<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container-fluid admin-wrapper">
    <button class="btn btn-primary d-md-none position-fixed top-0 start-0 m-2 z-3" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
        <i class="bi bi-list"></i>
    </button>

    <div class="row">
        <div class="offcanvas-md offcanvas-start bg-light sidebar col-md-3 col-lg-2" tabindex="-1" id="adminSidebar"
            aria-labelledby="adminSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="adminSidebarLabel">Admin Panel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar"
                    aria-label="Close"></button>
            </div>

            <div class="offcanvas-body pt-3 flex-column">
                <h5 class="sidebar-heading px-3 mt-2 mb-3 text-muted text-uppercase d-none d-md-block">
                    Admin Panel
                </h5>
                <ul class="nav flex-column w-100">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-speedometer2 me-2"></i>Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-book me-2"></i> Book Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-files me-2"></i> Book Copies Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-people me-2"></i> Member Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-arrow-left-right me-2"></i> Borrowing and Returning Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#">
                            <i class="bi bi-exclamation-circle me-2"></i> Overdue Books
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex align-items-center pt-3 pb-2 mb-3 border-bottom">
                <button class="btn btn-outline-secondary me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="h2">System Overview</h1>
            </div>

            <div class="alert alert-info">
                Welcome back, Admin!
            </div>

        </main>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        const mainContent = document.querySelector('main');

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('sidebar-active');
        });
    });
</script>