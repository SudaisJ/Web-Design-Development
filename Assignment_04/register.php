<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Secure Password Validation
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $error = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email is already registered.';
            } else {
                // Hash and insert
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashed_password])) {
                    $success = 'Registration successful! You can now sign in.';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card glass-card">
        <div class="text-center mb-4">
            <i class="fa-solid fa-user-plus fa-3x text-info mb-3"></i>
            <h3 class="fw-bold">Create Account</h3>
            <p class="text-muted">Register to manage your shops</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success) ?> <br>
                <a href="index.php" class="alert-link">Go to Sign In</a>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="name" class="form-control border-start-0" placeholder="John Doe" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="admin@rental.com" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Secure Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" id="reg-password" class="form-control border-start-0 border-end-0" placeholder="Min 8 chars, 1 uppercase, 1 num, 1 spec" required>
                    <button class="btn btn-outline-secondary border-secondary text-muted" type="button" onclick="togglePassword('reg-password', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="fa-solid fa-check-double text-muted"></i></span>
                    <input type="password" name="confirm_password" id="reg-confirm" class="form-control border-start-0 border-end-0" placeholder="Confirm your password" required>
                    <button class="btn btn-outline-secondary border-secondary text-muted" type="button" onclick="togglePassword('reg-confirm', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-info text-white w-100 py-2 mb-3">
                Sign Up <i class="fa-solid fa-user-plus ms-2"></i>
            </button>
            <div class="text-center">
                <span class="text-muted">Already have an account? </span>
                <a href="index.php" class="text-info text-decoration-none">Sign In here</a>
            </div>
        </form>
        <?php endif; ?>
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
