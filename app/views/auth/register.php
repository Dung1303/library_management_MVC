<link rel="stylesheet" href="/public/assets/css/register.css">

<div class="register-container">
    <div class="header">
        <div class="logo">📖</div> <!-- Icon sách -->
        <div class="title">Library Management System</div>
        <div class="subtitle">Register to explore the library</div>
    </div>

    <div class="form-content">
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="/auth/register">
            <div class="form-group">
                <label class="label" for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="E.g: John Doe" required>
            </div>

            <div class="form-group">
                <label class="label" for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="John Doe" required>
            </div>

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

            <div class="form-group">
                <label class="label" for="confirm_password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter password again" required>
                    <span class="eye-icon" onclick="togglePassword('confirm_password')">👁️</span>
                </div>
            </div>

            <div class="login-link">
                If you already have an account, please <a href="/app/views/auth/login.php">Login</a>
            </div>

            <button type="submit" class="btn-register">Register</button>
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