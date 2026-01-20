<link rel="stylesheet" href="/public/assets/css/login.css">

<div class="login-container">
    <div class="header">
        <div class="logo">📖</div> <!-- Icon sách -->
        <div class="title">Library Management System</div>
        <div class="subtitle">Login to continue</div>
    </div>

    <div class="form-content">
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
            <p style="color: green; text-align: center; margin-bottom: 15px;">Registration successful! Please login.</p>
        <?php endif; ?>

        <form method="POST" action="/auth/login">
            <div class="form-group">
                <label class="label" for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required>
            </div>

            <div class="form-group">
                <label class="label" for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                    <span class="eye-icon" onclick="togglePassword('password')">👁️</span>
                </div>
            </div>

            <a href="/auth/forgot_password" class="forgot-link">Forgot password?</a>

            <div class="register-link" style="display: inline-block; margin-right: 10px;">Don't have an account?</div>
            <a href="/app/views/auth/register.php" class="register-link" style="display: inline;">Register</a>

            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</div>

<script>
    // Hàm toggle hiển thị mật khẩu
    function togglePassword(id) {
        var input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>