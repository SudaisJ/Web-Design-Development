<?php
require_once 'config.php';
if (isLoggedIn()) redirect('dashboard.php');

// Generate CAPTCHA
if (!isset($_SESSION['captcha_ans']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $_SESSION['captcha_ans'] = $num1 + $num2;
    $_SESSION['captcha_str'] = "$num1 + $num2";
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $captcha = $_POST['captcha'] ?? '';
    
    if (empty($email) || empty($password) || empty($captcha)) {
        $error = 'Please fill in all fields.';
    } elseif ((int)$captcha !== $_SESSION['captcha_ans']) {
        $error = 'Security check failed. Incorrect math answer.';
        // Regenerate captcha on failure
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $_SESSION['captcha_ans'] = $num1 + $num2;
        $_SESSION['captcha_str'] = "$num1 + $num2";
    } else {
        if (isset($pdo)) {
            $stmt = $pdo->prepare('SELECT id, username, password, role, is_approved FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                if ($user['is_approved'] == 0) {
                    $error = 'Your account is currently pending approval by an administrator.';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    redirect('dashboard.php');
                }
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Database connection failed.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UETM Library | Academic Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { colors: { primary: '#0f766e', secondary: '#0369a1' } } } }</script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="assets/js/theme.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .dark .glass { background: rgba(17, 24, 39, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); }
        .bg-animated { background: linear-gradient(-45deg, #0f766e, #0369a1, #0ea5e9, #14b8a6); background-size: 400% 400%; animation: gradient 15s ease infinite; }
        @keyframes gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 min-h-screen flex text-gray-800 dark:text-gray-200">
    
    <div class="hidden lg:flex w-1/2 bg-animated items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="relative z-10 text-center text-white px-12">
            <div class="mb-6 flex justify-center">
                <i class="fas fa-university text-7xl drop-shadow-lg"></i>
            </div>
            <h1 class="text-5xl font-bold mb-4 tracking-tight">Welcome to UETM Library</h1>
            <p class="text-xl font-light opacity-90">The official academic portal for managing university library resources, equipped with internal REST APIs.</p>
            <div class="mt-8 flex justify-center space-x-4">
                <span class="glass px-4 py-2 rounded-full text-sm font-medium"><i class="fas fa-code mr-2"></i>API Ready</span>
                <span class="glass px-4 py-2 rounded-full text-sm font-medium"><i class="fas fa-chart-line mr-2"></i>Analytics</span>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 relative">
        <div class="absolute top-6 right-6 z-20">
            <button id="theme-toggle" class="p-3 rounded-full glass hover:bg-gray-200 dark:hover:bg-gray-700 transition shadow-lg focus:outline-none focus:ring-2 focus:ring-primary">
                <i class="fas fa-moon text-gray-800 dark:hidden text-lg"></i>
                <i class="fas fa-sun text-yellow-300 hidden dark:inline text-lg"></i>
            </button>
        </div>

        <div class="w-full max-w-md glass p-10 rounded-2xl shadow-2xl dark:shadow-[0_10px_40px_rgba(0,0,0,0.5)] transform transition hover:scale-[1.01] duration-300">
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-gradient-to-tr from-primary to-secondary rounded-2xl mx-auto flex items-center justify-center shadow-lg mb-4 transform rotate-3">
                    <i class="fas fa-book-reader text-white text-3xl -rotate-3"></i>
                </div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">Sign In</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Access the UETM administrative dashboard</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500 bg-opacity-10 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded mb-6 text-sm flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm bg-white dark:bg-gray-800 transition shadow-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm bg-white dark:bg-gray-800 transition shadow-sm">
                    </div>
                </div>

                <div>
                    <label for="captcha" class="block text-sm font-bold mb-1 text-primary">Security Check: What is <?php echo $_SESSION['captcha_str']; ?>?</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-shield-alt text-gray-400"></i>
                        </div>
                        <input type="number" id="captcha" name="captcha" required placeholder="Enter the sum"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm bg-gray-50 dark:bg-gray-700 transition shadow-sm font-bold">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-primary to-secondary hover:from-teal-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transform transition hover:-translate-y-0.5">
                        Sign In <i class="fas fa-arrow-right ml-2 mt-1"></i>
                    </button>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                Don't have an account? 
                <a href="register.php" class="font-bold text-primary hover:text-teal-500 transition border-b-2 border-transparent hover:border-primary">Create one</a>
            </p>
        </div>
    </div>
</body>
</html>
