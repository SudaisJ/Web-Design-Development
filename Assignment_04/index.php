<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please enter email and password.';
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card glass-card">
        <div class="text-center mb-4">
            <i class="fa-solid fa-shop fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold">Rental Manager</h3>
            <p class="text-muted">Sign in to manage your shops</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="admin@rental.com" required value="admin@rental.com">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" id="login-password" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary border-secondary text-muted" type="button" onclick="togglePassword('login-password', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                Sign In <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
            <div class="text-center">
                <span class="text-muted">Don't have an account? </span>
                <a href="register.php" class="text-primary text-decoration-none">Sign Up here</a>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
