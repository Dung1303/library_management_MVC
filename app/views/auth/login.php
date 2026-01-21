<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="/library_management_system/public/assets/css/login.css">

<div class="login-container">
    <div class="header">
        <div class="logo"><i class="bi bi-book-fill"></i></div>
        <div class="title">Library Management System</div>
        <div class="subtitle">Login to continue</div>
    </div>

    <div class="form-content">
        <?php if (isset($error)): ?>
        <div
            style="background: #fee; border: 1px solid #fcc; border-radius: 8px; color: #c33; text-align: center; margin-bottom: 20px; padding: 12px; font-size: 0.95rem;">
            <?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
        <div
            style="background: #efe; border: 1px solid #cfc; border-radius: 8px; color: #3c3; text-align: center; margin-bottom: 20px; padding: 12px; font-size: 0.95rem;">
            Registration successful! Please login.</div>
        <?php endif; ?>

        <form method="POST" action="/library_management_system/public/auth/login">
            <div class="form-group">
                <label class="label" for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label class="label" for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                    <span class="eye-icon" id="toggleIcon" onclick="togglePassword('password')"><i
                            class="bi bi-eye"></i></span>
                </div>
            </div>

            <a href="/auth/forgot_password" class="forgot-link">Forgot password?</a>

            <div style="text-align: center; margin: 25px 0 30px;">
                <span style="color: #666; font-size: 0.95rem;">Don't have an account? </span>
                <a href="/library_management_system/public/auth/register"
                    style="color: #667eea; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">Register</a>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</div>

<script>
// Hàm toggle hiển thị mật khẩu
function togglePassword(id) {
    var input = document.getElementById(id);
    var icon = document.getElementById('toggleIcon');
    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
        input.type = "password";
        icon.innerHTML = '<i class="bi bi-eye"></i>';
    }
}
</script>