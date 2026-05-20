<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $new_password = $_POST['new_password'] ?? '';
    
    if ($username) {
        if ($new_password) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            if ($stmt->execute([$username, $hashed, $user_id])) {
                $_SESSION['username'] = $username;
                $success = 'Profile and password updated successfully!';
            } else {
                $error = 'Failed to update profile.';
            }
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            if ($stmt->execute([$username, $user_id])) {
                $_SESSION['username'] = $username;
                $success = 'Profile updated successfully!';
            } else {
                $error = 'Failed to update profile.';
            }
        }
    } else {
        $error = 'Username cannot be empty.';
    }
}

$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_email = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | UETM Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { colors: { primary: '#0f766e', secondary: '#0369a1' } } } }</script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="assets/js/theme.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .dark .glass { background: rgba(31, 41, 55, 0.7); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 min-h-screen text-gray-800 dark:text-gray-200">
    
    <nav class="glass sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-3">
                    <img src="assets/images/uetm_logo.png" alt="UETM Logo" class="w-12 h-12 drop-shadow-md">
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">UETM Library</span>
                </div>
                
                <div class="hidden sm:flex space-x-8">
                    <a href="dashboard.php" class="text-gray-500 hover:text-primary dark:hover:text-white transition px-1 py-2 font-medium">Dashboard</a>
                    <a href="books.php" class="text-gray-500 hover:text-primary dark:hover:text-white transition px-1 py-2 font-medium">Local Catalog</a>
                    <a href="global_library.php" class="text-gray-500 hover:text-primary dark:hover:text-white transition px-1 py-2 font-medium">Global Library</a>
                </div>

                <div class="flex items-center space-x-6">
                    <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-moon text-gray-600 dark:hidden"></i>
                        <i class="fas fa-sun text-yellow-400 hidden dark:inline"></i>
                    </button>
                    <a href="profile.php" class="flex items-center space-x-3 border-l pl-6 dark:border-gray-700 hover:opacity-80 transition group">
                        <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center text-primary dark:text-teal-300 font-bold uppercase group-hover:scale-110 transition transform">
                            <?php echo substr($_SESSION['username'], 0, 1); ?>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-sm leading-tight text-primary"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 capitalize"><?php echo $role; ?></span>
                        </div>
                    </a>
                    <a href="logout.php" class="text-red-500 hover:text-red-600 ml-4"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="glass rounded-3xl p-10 shadow-2xl relative overflow-hidden">
            <div class="text-center mb-10">
                <div class="w-24 h-24 bg-gradient-to-tr from-primary to-secondary rounded-full mx-auto flex items-center justify-center shadow-lg mb-4 text-white text-4xl font-bold uppercase">
                    <?php echo substr($_SESSION['username'], 0, 1); ?>
                </div>
                <h1 class="text-3xl font-bold">Profile Settings</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Update your personal information and security credentials.</p>
            </div>

            <?php if ($success): ?>
                <div class="bg-green-500 bg-opacity-10 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-lg mb-8 shadow-sm flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-500 bg-opacity-10 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-lg mb-8 shadow-sm flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-lg"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="profile.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email Address (Read-only)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" value="<?php echo htmlspecialchars($user_email); ?>" disabled class="block w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 shadow-sm text-sm cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required class="block w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 transition shadow-sm text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">New Password <span class="text-xs font-normal text-gray-500">(Leave blank to keep current)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="new_password" placeholder="Enter new password..." class="block w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-700 transition shadow-sm text-sm">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
                        Save Changes <i class="fas fa-save ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
