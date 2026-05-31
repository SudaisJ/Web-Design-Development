<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');

$role = $_SESSION['role'] ?? 'student';
if ($role === 'admin') {
    $role = 'staff';
    $_SESSION['role'] = 'staff';
}
$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';
$available_only = isset($_GET['available_only']) ? true : false;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;
$books = [];
$borrowed_book_ids = [];
$total_pages = 1;

if (isset($pdo)) {
    try {
        $params = [];
        $where_clauses = [];
        
        if ($search) {
            $searchTerm = "%$search%";
            $where_clauses[] = "(title LIKE ? OR author LIKE ? OR isbn LIKE ? OR category LIKE ?)";
            array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        }
        if ($available_only) {
            $where_clauses[] = "quantity > 0 AND status = 'Available'";
        }
        
        $where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";
        
        $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM books $where_sql");
        $count_stmt->execute($params);
        $total_books = $count_stmt->fetchColumn();
        $total_pages = ceil($total_books / $limit);
        
        $stmt = $pdo->prepare("SELECT * FROM books $where_sql ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
        $stmt->execute($params);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($role !== 'staff') {
            $stmt = $pdo->prepare("SELECT book_id FROM borrowings WHERE user_id = ? AND status = 'borrowed'");
            $stmt->execute([$user_id]);
            $borrowed_book_ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
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
    <title>Book Catalog | UETM Library</title>
    <link rel="icon" type="image/png" href="assets/images/uetm_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { colors: { primary: '#0f766e', secondary: '#0369a1' } } } }</script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="assets/js/theme.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .dark .glass { background: rgba(31, 41, 55, 0.7); border: 1px solid rgba(255, 255, 255, 0.05); }
        .book-card:hover .book-cover { transform: scale(1.05); }
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
                    <a href="books.php" class="text-primary font-semibold border-b-2 border-primary px-1 py-2">Local Catalog</a>
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
                    <a href="logout.php" class="text-red-500 hover:text-red-600 ml-4"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-bold">UETM Book Catalog</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Manage, search, and borrow university library assets.</p>
            </div>
            <?php if ($role === 'staff'): ?>
                <button onclick="openModal('add')" class="bg-gradient-to-r from-primary to-secondary hover:from-teal-600 hover:to-blue-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform transition hover:-translate-y-1 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add Asset
                </button>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-500 bg-opacity-10 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-lg mb-8 shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-3 text-lg"></i> <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 bg-opacity-10 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-lg mb-8 shadow-sm flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-lg"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="glass rounded-2xl p-4 shadow-md mb-10">
            <form action="books.php" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by title, author, ISBN, or category..." 
                        class="block w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800 transition shadow-sm text-sm">
                </div>
                <div class="flex items-center gap-2 px-2 bg-gray-100 dark:bg-gray-800 py-3 px-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <input type="checkbox" id="available_only" name="available_only" value="1" <?php if($available_only) echo 'checked'; ?> class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary bg-white dark:bg-gray-700">
                    <label for="available_only" class="text-sm font-bold text-gray-700 dark:text-gray-300 cursor-pointer">Available Only</label>
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none bg-gray-800 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl shadow transition">Search</button>
                    <?php if ($search || $available_only): ?>
                        <a href="books.php" class="flex-1 md:flex-none text-center bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 py-3 px-6 rounded-xl font-bold hover:bg-gray-300 dark:hover:bg-gray-500 transition">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if (count($books) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($books as $book): ?>
                    <div class="glass rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 book-card group relative flex flex-col">
                        <div class="h-64 overflow-hidden relative bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                            <?php if (!empty($book['cover_image']) && $book['cover_image'] !== 'default_cover.png'): ?>
                                <img src="uploads/<?php echo htmlspecialchars($book['cover_image']); ?>" class="book-cover w-full h-full object-cover transition duration-500" alt="Cover">
                            <?php else: ?>
                                <i class="fas fa-image text-gray-400 text-6xl book-cover transition duration-500"></i>
                            <?php endif; ?>
                            
                            <div class="absolute top-4 right-4 z-10">
                                <?php if($book['status'] === 'Available' && $book['quantity'] > 0): ?>
                                    <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Available</span>
                                <?php else: ?>
                                    <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Borrowed</span>
                                <?php endif; ?>
                            </div>

                            <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-4 z-20">
                                <?php if ($role === 'staff'): ?>
                                    <button onclick="openModal('edit', <?php echo htmlspecialchars(json_encode($book)); ?>)" class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center hover:bg-blue-600 transform hover:scale-110 transition shadow-lg" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="book_actions.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                                        <button type="submit" class="w-12 h-12 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transform hover:scale-110 transition shadow-lg" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <?php if (in_array($book['id'], $borrowed_book_ids)): ?>
                                        <span class="bg-yellow-500 text-white font-bold py-2 px-4 rounded-xl shadow-lg">Already Borrowed</span>
                                    <?php elseif ($book['quantity'] > 0 && $book['status'] === 'Available'): ?>
                                        <form action="book_actions.php" method="POST">
                                            <input type="hidden" name="action" value="borrow_book">
                                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                            <button type="submit" class="bg-gradient-to-r from-primary to-secondary text-white font-bold py-2 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition">
                                                Borrow Book
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="bg-red-500 text-white font-bold py-2 px-4 rounded-xl shadow-lg">Out of Stock</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <div class="text-xs font-bold text-primary tracking-wider uppercase">ISBN: <?php echo htmlspecialchars($book['isbn']); ?></div>
                                <div class="text-xs font-semibold bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded">
                                    <?php echo htmlspecialchars($book['category']); ?>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold mb-1 line-clamp-1" title="<?php echo htmlspecialchars($book['title']); ?>"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-1"><i class="fas fa-user-edit mr-1"></i> <?php echo htmlspecialchars($book['author']); ?></p>
                            
                            <div class="mt-auto flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center text-sm font-medium">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-2"></i> <?php echo htmlspecialchars($book['published_year']); ?>
                                </div>
                                <div class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs font-bold px-3 py-1 rounded-full border border-gray-200 dark:border-gray-700">
                                    Qty: <?php echo htmlspecialchars($book['quantity']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="flex justify-center mt-12 gap-2">
                    <?php 
                    $query_string = '';
                    if ($search) $query_string .= '&search=' . urlencode($search);
                    if ($available_only) $query_string .= '&available_only=1';
                    
                    if ($page > 1): 
                    ?>
                        <a href="?page=<?php echo $page - 1; ?><?php echo $query_string; ?>" class="glass px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition font-bold"><i class="fas fa-chevron-left"></i> Prev</a>
                    <?php endif; ?>
                    
                    <?php 
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    for($i = $start_page; $i <= $end_page; $i++): 
                    ?>
                        <a href="?page=<?php echo $i; ?><?php echo $query_string; ?>" class="glass px-4 py-2 rounded-lg font-bold transition <?php echo ($i === $page) ? 'bg-primary text-white border-primary' : 'hover:bg-gray-200 dark:hover:bg-gray-700'; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?><?php echo $query_string; ?>" class="glass px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition font-bold">Next <i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="glass rounded-3xl p-16 text-center shadow-lg">
                <div class="w-24 h-24 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl text-gray-400">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h2 class="text-2xl font-bold mb-2">No assets found</h2>
                <p class="text-gray-500 dark:text-gray-400">Try adjusting your search.</p>
            </div>
        <?php endif; ?>

    </main>

    <?php if ($role === 'staff'): ?>
    <!-- Modal for Add/Edit -->
    <div id="bookModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom glass rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white border-opacity-20">
                <div class="bg-gradient-to-r from-primary to-secondary p-6 text-white flex justify-between items-center">
                    <h3 class="text-xl font-bold flex items-center" id="modal-title"><i class="fas fa-book mr-3"></i> Add Asset</h3>
                    <button onclick="closeModal()" class="text-white opacity-70 hover:opacity-100 transition"><i class="fas fa-times text-xl"></i></button>
                </div>
                
                <form action="book_actions.php" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800">
                    <div class="p-6 space-y-4">
                        <div id="api-notification" class="hidden p-3 mb-2 rounded-lg text-sm font-bold shadow-sm"></div>

                        <input type="hidden" name="action" id="modal-action" value="add">
                        <input type="hidden" name="id" id="book-id">
                        
                        <div class="relative">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">ISBN Number</label>
                            <div class="flex">
                                <input type="text" name="isbn" id="book-isbn" required class="block w-full border border-gray-300 dark:border-gray-600 rounded-l-xl px-4 py-3 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                                <button type="button" onclick="fetchBookFromAPI()" class="bg-gray-800 text-white px-4 rounded-r-xl hover:bg-gray-900 transition flex items-center">
                                    <i class="fas fa-cloud-download-alt"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Book Title</label>
                            <input type="text" name="title" id="book-title" required class="block w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Author Name</label>
                            <input type="text" name="author" id="book-author" required class="block w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                <select name="category" id="book-category" required class="block w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                                    <option value="General">General</option>
                                    <option value="Computer Science">Computer Science</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="Physics">Physics</option>
                                    <option value="Literature">Literature</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select name="status" id="book-status" required class="block w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                                    <option value="Available">Available</option>
                                    <option value="Borrowed">Borrowed</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-5">
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Published Year</label>
                                <input type="number" name="published_year" id="book-year" required class="block w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Stock Quantity</label>
                                <input type="number" name="quantity" id="book-qty" min="1" value="1" required class="block w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Cover Image (Optional)</label>
                            <input type="file" name="cover_image" accept="image/*" class="block w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 bg-gray-50 dark:bg-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-primary file:text-white hover:file:bg-teal-600 transition text-sm">
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">Save Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(mode, book = null) {
            const modal = document.getElementById('bookModal');
            const title = document.getElementById('modal-title');
            const action = document.getElementById('modal-action');
            document.getElementById('api-notification').classList.add('hidden');
            
            if (mode === 'edit' && book) {
                title.innerHTML = '<i class="fas fa-edit mr-3"></i> Edit Asset';
                action.value = 'edit';
                document.getElementById('book-id').value = book.id;
                document.getElementById('book-title').value = book.title;
                document.getElementById('book-author').value = book.author;
                document.getElementById('book-isbn').value = book.isbn;
                document.getElementById('book-year').value = book.published_year;
                document.getElementById('book-qty').value = book.quantity;
                document.getElementById('book-category').value = book.category || 'General';
                document.getElementById('book-status').value = book.status || 'Available';
            } else {
                title.innerHTML = '<i class="fas fa-book mr-3"></i> Add Asset';
                action.value = 'add';
                document.getElementById('book-id').value = '';
                document.getElementById('book-title').value = '';
                document.getElementById('book-author').value = '';
                document.getElementById('book-isbn').value = '';
                document.getElementById('book-year').value = new Date().getFullYear();
                document.getElementById('book-qty').value = '1';
                document.getElementById('book-category').value = 'General';
                document.getElementById('book-status').value = 'Available';
            }
            
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('bookModal').classList.add('hidden');
        }

        async function fetchBookFromAPI() {
            const isbn = document.getElementById('book-isbn').value.trim();
            const notification = document.getElementById('api-notification');
            if(!isbn) { showNotification('Please enter an ISBN first.', 'error'); return; }
            showNotification('Fetching data...', 'info');
            try {
                const response = await fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&jscmd=data&format=json`);
                const data = await response.json();
                const bookKey = `ISBN:${isbn}`;
                if (data[bookKey]) {
                    const book = data[bookKey];
                    document.getElementById('book-title').value = book.title || '';
                    if (book.authors && book.authors.length > 0) document.getElementById('book-author').value = book.authors[0].name || '';
                    if (book.publish_date) {
                        const yearMatch = book.publish_date.match(/\d{4}/);
                        if(yearMatch) document.getElementById('book-year').value = yearMatch[0];
                    }
                    showNotification('Fetched!', 'success');
                } else { showNotification('Not found.', 'error'); }
            } catch (error) { showNotification('Error connecting to API.', 'error'); }
        }

        function showNotification(msg, type) {
            const notif = document.getElementById('api-notification');
            notif.classList.remove('hidden', 'bg-blue-100', 'text-blue-700', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
            if(type === 'info') notif.classList.add('bg-blue-100', 'text-blue-700');
            if(type === 'success') notif.classList.add('bg-green-100', 'text-green-700');
            if(type === 'error') notif.classList.add('bg-red-100', 'text-red-700');
            notif.textContent = msg;
        }
    </script>
    <?php endif; ?>
</body>
</html>
