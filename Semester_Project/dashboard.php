<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$role = $_SESSION['role'] ?? 'student';
if ($role === 'admin') {
    $role = 'staff';
    $_SESSION['role'] = 'staff';
}
$user_id = $_SESSION['user_id'];

// Staff Stats
$total_books = 0;
$total_users = 0;
$books_added_recent = 0;
$borrowed_books_count = 0;
$pending_users = [];
$waitlist_count = 0;

// Student/Faculty Stats
$my_borrowed_count = 0;
$my_borrowed_books = [];
$total_fines = 0;

if (isset($pdo)) {
    try {
        if ($role === 'staff') {
            $total_books = $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();
            $total_users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
            $books_added_recent = $pdo->query('SELECT COUNT(*) FROM books WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')->fetchColumn();
            $borrowed_books_count = $pdo->query("SELECT COUNT(*) FROM borrowings WHERE status = 'borrowed'")->fetchColumn();
            
            $stmt = $pdo->query("SELECT id, username, email, role FROM users WHERE is_approved = 0");
            $pending_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $waitlist_count = $pdo->query("SELECT COUNT(*) FROM waitlist")->fetchColumn();
            
            // Fetch active borrowings for admin
            $stmt = $pdo->query("
                SELECT u.username, b.title, br.borrow_date, br.due_date 
                FROM borrowings br 
                JOIN users u ON br.user_id = u.id 
                JOIN books b ON br.book_id = b.id 
                WHERE br.status = 'borrowed' 
                ORDER BY br.borrow_date DESC LIMIT 5
            ");
            $admin_active_borrowings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Student or Faculty
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrowings WHERE user_id = ? AND status = 'borrowed'");
            $stmt->execute([$user_id]);
            $my_borrowed_count = $stmt->fetchColumn();

            $stmt = $pdo->prepare("
                SELECT b.id, b.title, b.author, b.cover_image, br.borrow_date, br.due_date,
                       IF(br.due_date IS NOT NULL AND NOW() > br.due_date, DATEDIFF(NOW(), br.due_date) * 50, 0) as current_fine,
                       IF(br.due_date IS NOT NULL AND br.due_date > NOW(), DATEDIFF(br.due_date, NOW()), 0) as days_left
                FROM borrowings br 
                JOIN books b ON br.book_id = b.id 
                WHERE br.user_id = ? AND br.status = 'borrowed'
                ORDER BY br.borrow_date DESC
            ");
            $stmt->execute([$user_id]);
            $my_borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($my_borrowed_books as $book) {
                $total_fines += $book['current_fine'];
            }

            $stmt = $pdo->prepare("
                SELECT b.title, b.author, br.borrow_date, br.return_date, br.fine_amount 
                FROM borrowings br 
                JOIN books b ON br.book_id = b.id 
                WHERE br.user_id = ? AND br.status = 'returned'
                ORDER BY br.return_date DESC
            ");
            $stmt->execute([$user_id]);
            $my_borrow_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $db_error = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | UETM Library</title>
    <link rel="icon" type="image/png" href="assets/images/uetm_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { colors: { primary: '#0f766e', secondary: '#0369a1' } } } }</script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php if ($role === 'staff'): ?><script src="https://cdn.jsdelivr.net/npm/chart.js"></script><?php endif; ?>
    <script src="assets/js/theme.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .dark .glass { background: rgba(31, 41, 55, 0.7); border: 1px solid rgba(255, 255, 255, 0.05); }
        .bg-animated { background: linear-gradient(-45deg, #0f766e, #0369a1, #0ea5e9, #14b8a6); background-size: 400% 400%; animation: gradient 15s ease infinite; }
        @keyframes gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-300 min-h-screen text-gray-800 dark:text-gray-200">
    
    <nav class="glass sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-3">
                    <img src="assets/images/uetm_logo.png" alt="UETM Logo" class="w-12 h-12 drop-shadow-md">
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">UETM Library</span>
                </div>
                
                <div class="hidden sm:flex space-x-8">
                    <a href="dashboard.php" class="text-primary font-semibold border-b-2 border-primary px-1 py-2">Dashboard</a>
                    <a href="books.php" class="text-gray-500 hover:text-primary dark:hover:text-white transition px-1 py-2 font-medium">Local Catalog</a>
                    <a href="global_library.php" class="text-gray-500 hover:text-primary dark:hover:text-white transition px-1 py-2 font-medium">Global Library</a>
                </div>

                <div class="flex items-center space-x-6">
                    <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-moon text-gray-600 dark:hidden"></i>
                        <i class="fas fa-sun text-yellow-400 hidden dark:inline"></i>
                    </button>
                    <a href="profile.php" class="flex items-center space-x-3 border-l pl-6 dark:border-gray-700 hover:opacity-80 transition group">
                        <?php if(!empty($_SESSION['profile_image']) && $_SESSION['profile_image'] !== 'default_avatar.png'): ?>
                            <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" class="w-8 h-8 rounded-full object-cover shadow group-hover:scale-110 transition transform" alt="Profile">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center text-primary dark:text-teal-300 font-bold uppercase group-hover:scale-110 transition transform">
                                <?php echo substr($_SESSION['username'], 0, 1); ?>
                            </div>
                        <?php endif; ?>
                        <div class="flex flex-col">
                            <span class="font-bold text-sm leading-tight group-hover:text-primary transition"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 capitalize"><?php echo $role; ?></span>
                        </div>
                    </a>
                    <a href="logout.php" class="text-red-500 hover:text-red-600 ml-4" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-animated rounded-3xl p-10 text-white shadow-2xl mb-10 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-2">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h1>
                
                <?php if ($role === 'staff'): ?>
                    <p class="text-lg opacity-90">Manage UETM University's digital and physical catalog efficiently.</p>
                    <div class="mt-6 flex gap-4">
                        <a href="books.php" class="bg-white text-primary font-bold px-6 py-3 rounded-xl shadow-lg hover:bg-gray-50 transition transform hover:-translate-y-1 inline-block">
                            <i class="fas fa-plus-circle mr-2"></i> Add New Book
                        </a>
                        <a href="global_library.php" class="bg-black bg-opacity-30 text-white border border-white font-bold px-6 py-3 rounded-xl hover:bg-opacity-40 transition inline-block">
                            <i class="fas fa-globe mr-2"></i> Explore Globally
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-lg opacity-90">
                        <?php if ($role === 'faculty') echo "As Faculty, you have extended borrowing privileges and no late fines."; ?>
                        <?php if ($role === 'student') echo "As a Student, you can borrow up to 2 books for a full semester (17 weeks)."; ?>
                    </p>
                    <div class="mt-6 flex gap-4">
                        <a href="books.php" class="bg-white text-primary font-bold px-6 py-3 rounded-xl shadow-lg hover:bg-gray-50 transition transform hover:-translate-y-1 inline-block">
                            <i class="fas fa-search mr-2"></i> Browse & Borrow Books
                        </a>
                    </div>
                <?php endif; ?>

            </div>
            <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-1/12 translate-y-1/12 pointer-events-none">
                <img src="assets/images/uetm_logo.png" alt="UETM Logo Background" class="w-80 h-80 object-contain drop-shadow-2xl">
            </div>
        </div>

        <?php if (isset($db_error)): ?>
            <div class="bg-red-500 bg-opacity-10 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded mb-6">
                <i class="fas fa-exclamation-triangle mr-2"></i> <?php echo htmlspecialchars($db_error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 bg-opacity-10 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-500 bg-opacity-10 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i> <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if ($role === 'staff'): ?>
            <!-- Staff Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="glass rounded-2xl p-6 shadow-lg border-t-4 border-primary transform transition hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 dark:text-gray-400 font-medium text-lg">Total Books</h3>
                        <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900 rounded-full flex items-center justify-center text-primary text-xl shadow-inner"><i class="fas fa-book"></i></div>
                    </div>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white"><?php echo $total_books; ?></p>
                    <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> <?php echo $books_added_recent; ?> New Stock</p>
                </div>

                <div class="glass rounded-2xl p-6 shadow-lg border-t-4 border-secondary transform transition hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 dark:text-gray-400 font-medium text-lg">Registered Users</h3>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-secondary text-xl shadow-inner"><i class="fas fa-users"></i></div>
                    </div>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white"><?php echo $total_users; ?></p>
                    <p class="text-sm text-gray-400 mt-2">Active university accounts</p>
                </div>

                <div class="glass rounded-2xl p-6 shadow-lg border-t-4 border-yellow-500 transform transition hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-gray-500 dark:text-gray-400 font-medium text-lg">Borrowed Books</h3>
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center text-yellow-500 text-xl shadow-inner"><i class="fas fa-book-reader"></i></div>
                    </div>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white"><?php echo $borrowed_books_count; ?></p>
                    <p class="text-sm text-red-500 mt-2"><i class="fas fa-exclamation-circle mr-1"></i> Currently out of library</p>
                </div>
            </div>

            <div class="glass rounded-3xl p-8 shadow-xl mb-10">
                <h2 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-user-check text-primary mr-3"></i> Pending Approvals (<?php echo count($pending_users); ?>)</h2>
                <?php if (count($pending_users) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    <th class="py-3 px-4 font-bold text-sm">Username</th>
                                    <th class="py-3 px-4 font-bold text-sm">Email</th>
                                    <th class="py-3 px-4 font-bold text-sm">Requested Role</th>
                                    <th class="py-3 px-4 font-bold text-sm">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pending_users as $p_user): ?>
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4 font-medium"><?php echo htmlspecialchars($p_user['username']); ?></td>
                                        <td class="py-3 px-4 text-gray-500"><?php echo htmlspecialchars($p_user['email']); ?></td>
                                        <td class="py-3 px-4"><span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-2 py-1 rounded-full capitalize"><?php echo htmlspecialchars($p_user['role']); ?></span></td>
                                        <td class="py-3 px-4">
                                            <form action="book_actions.php" method="POST">
                                                <input type="hidden" name="action" value="approve_user">
                                                <input type="hidden" name="target_user_id" value="<?php echo $p_user['id']; ?>">
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded shadow text-sm transition">Approve</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No pending account approvals.</p>
                <?php endif; ?>
            </div>

            <div class="glass rounded-3xl p-8 shadow-xl">
                <h2 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-chart-area text-primary mr-3"></i> Inventory Analytics</h2>
                <div class="h-80 w-full relative"><canvas id="inventoryChart"></canvas></div>
            </div>
            
            <div class="glass rounded-3xl p-8 shadow-xl mt-10">
                <h2 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-list-alt text-primary mr-3"></i> Recent Borrowings</h2>
                <?php if (count($admin_active_borrowings) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    <th class="py-3 px-4 font-bold text-sm">Student/Faculty</th>
                                    <th class="py-3 px-4 font-bold text-sm">Book Title</th>
                                    <th class="py-3 px-4 font-bold text-sm">Borrowed On</th>
                                    <th class="py-3 px-4 font-bold text-sm">Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($admin_active_borrowings as $ab): ?>
                                    <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="py-3 px-4 font-medium"><?php echo htmlspecialchars($ab['username']); ?></td>
                                        <td class="py-3 px-4 text-gray-500"><?php echo htmlspecialchars($ab['title']); ?></td>
                                        <td class="py-3 px-4 text-sm"><?php echo date('M d, Y', strtotime($ab['borrow_date'])); ?></td>
                                        <td class="py-3 px-4 text-sm text-red-500 font-medium"><?php echo date('M d, Y', strtotime($ab['due_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No books are currently borrowed.</p>
                <?php endif; ?>
            </div>
            
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const ctx = document.getElementById('inventoryChart').getContext('2d');
                    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(15, 118, 110, 0.5)'); 
                    gradient.addColorStop(1, 'rgba(15, 118, 110, 0.0)');
                    Chart.defaults.color = document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563';
                    Chart.defaults.font.family = "'Outfit', sans-serif";
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                            datasets: [{
                                label: 'Books Added',
                                data: [12, 19, 15, 25, 22, 30, Math.max(10, <?php echo $books_added_recent * 4; ?>)],
                                borderColor: '#0f766e',
                                backgroundColor: gradient,
                                borderWidth: 3,
                                pointBackgroundColor: '#0369a1',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5, fill: true, tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: 'rgba(156, 163, 175, 0.2)' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                });
            </script>

        <?php else: ?>
            <!-- Student/Faculty Dashboard -->
            <div class="flex flex-wrap gap-6 mb-10">
                <div class="glass rounded-2xl p-6 shadow-lg border-t-4 border-primary flex-1 min-w-[200px]">
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-2">My Borrowed Books</h3>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">
                        <?php echo $my_borrowed_count; ?> 
                        <?php if($role === 'student'): ?>
                            <span class="text-lg text-gray-500">/ 2 allowed</span>
                        <?php endif; ?>
                    </p>
                </div>
                <?php if ($role === 'student'): ?>
                <div class="glass rounded-2xl p-6 shadow-lg border-t-4 border-red-500 flex-1 min-w-[200px]">
                    <h3 class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-2">Outstanding Fines</h3>
                    <p class="text-4xl font-bold text-red-500">
                        Rs. <?php echo $total_fines; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <h2 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-book-open text-primary mr-3"></i> Currently Reading</h2>
            
            <?php if (count($my_borrowed_books) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach($my_borrowed_books as $bbook): ?>
                        <div class="glass rounded-2xl p-4 shadow-md flex items-center gap-4">
                            <div class="w-20 h-28 bg-gray-200 dark:bg-gray-800 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center">
                                <?php if (!empty($bbook['cover_image']) && $bbook['cover_image'] !== 'default_cover.png'): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($bbook['cover_image']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-lg leading-tight mb-1"><?php echo htmlspecialchars($bbook['title']); ?></h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2"><?php echo htmlspecialchars($bbook['author']); ?></p>
                                <p class="text-xs text-primary font-medium mb-1">Borrowed: <?php echo date('M d, Y', strtotime($bbook['borrow_date'])); ?></p>
                                <?php if ($bbook['due_date']): ?>
                                    <p class="text-xs <?php echo ($bbook['current_fine'] > 0 && $role === 'student') ? 'text-red-500 font-bold' : 'text-gray-500'; ?> mb-3">
                                        Due: <?php echo date('M d, Y', strtotime($bbook['due_date'])); ?> 
                                        <?php if ($role === 'student' && $bbook['current_fine'] > 0): ?>
                                            (Late: Rs. <?php echo $bbook['current_fine']; ?>)
                                        <?php else: ?>
                                            (<span class="text-green-600 dark:text-green-400 font-bold"><?php echo $bbook['days_left']; ?> days left</span>)
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                                
                                <form action="book_actions.php" method="POST">
                                    <input type="hidden" name="action" value="return_book">
                                    <input type="hidden" name="book_id" value="<?php echo $bbook['id']; ?>">
                                    <button type="submit" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold py-2 px-4 rounded-lg w-full transition">
                                        Return Book
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="glass rounded-3xl p-10 text-center shadow-sm border border-dashed border-gray-300 dark:border-gray-700">
                    <i class="fas fa-book text-gray-300 dark:text-gray-600 text-5xl mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">You haven't borrowed any books yet.</p>
                    <a href="books.php" class="text-primary hover:underline mt-2 inline-block">Go to Catalog</a>
                </div>
            <?php endif; ?>

            <?php if (count($my_borrow_history) > 0): ?>
                <div class="mt-12">
                    <h2 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-history text-primary mr-3"></i> Borrow History</h2>
                    <div class="glass rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-800">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    <th class="py-4 px-6 font-bold text-sm">Book Title</th>
                                    <th class="py-4 px-6 font-bold text-sm hidden md:table-cell">Author</th>
                                    <th class="py-4 px-6 font-bold text-sm">Borrowed On</th>
                                    <th class="py-4 px-6 font-bold text-sm">Returned On</th>
                                    <th class="py-4 px-6 font-bold text-sm">Fine Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($my_borrow_history as $history): ?>
                                    <tr class="border-t border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="py-4 px-6 font-medium"><?php echo htmlspecialchars($history['title']); ?></td>
                                        <td class="py-4 px-6 text-gray-500 hidden md:table-cell"><?php echo htmlspecialchars($history['author']); ?></td>
                                        <td class="py-4 px-6 text-sm"><?php echo date('M d, Y', strtotime($history['borrow_date'])); ?></td>
                                        <td class="py-4 px-6 text-sm text-green-500 font-medium"><?php echo date('M d, Y', strtotime($history['return_date'])); ?></td>
                                        <td class="py-4 px-6 text-sm <?php echo $history['fine_amount'] > 0 ? 'text-red-500 font-bold' : 'text-gray-500'; ?>">
                                            Rs. <?php echo htmlspecialchars($history['fine_amount']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </main>
</body>
</html>
