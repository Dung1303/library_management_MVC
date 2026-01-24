<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/register.css">
</head>

<body>
    <div class="register-container">
        <div class="header">
            <div class="logo"><i class="bi bi-book-fill"></i></div>
            <div class="title">Library Management System</div>
            <div class="subtitle">Register to explore the library</div>
        </div>

        <div class="form-content">
            <?php if (isset($error)): ?>
                <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/auth/register">
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
                        <span class="eye-icon" id="toggleIcon1" onclick="togglePassword('password', 'toggleIcon1')"><i
                                class="bi bi-eye"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label" for="confirm_password">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Enter password again" required>
                        <span class="eye-icon" id="toggleIcon2"
                            onclick="togglePassword('confirm_password', 'toggleIcon2')"><i class="bi bi-eye"></i></span>
                    </div>
                </div>

                <div class="login-link">
                    If you already have an account, please
                    <a href="<?= BASE_URL ?>/auth/login">Login</a>
                </div>
                <button type="submit" class="btn-register">Register</button>
            </form>
        </div>
    </div>

    <script>
        // Hàm toggle hiển thị mật khẩu
        function togglePassword(id, iconId) {
            var input = document.getElementById(id);
            var icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = "password";
                icon.innerHTML = '<i class="bi bi-eye"></i>';
            }
        }
    </script>
</body>

</html>