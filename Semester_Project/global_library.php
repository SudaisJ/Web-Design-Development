<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('index.php');
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Library API | UETM Library</title>
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
        /* Custom Loader */
        .loader { border: 4px solid rgba(15, 118, 110, 0.2); border-left-color: #0f766e; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300 min-h-screen text-gray-800 dark:text-gray-200">
    
    <nav class="glass sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-3">
                    <img src="assets/images/uetm_logo.png" alt="UETM Logo" class="w-12 h-12 drop-shadow-md">
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">Global Library</span>
                </div>
                
                <div class="hidden sm:flex space-x-8">
                    <a href="dashboard.php" class="text-gray-500 hover:text-primary dark:hover:text-white transition px-1 py-2 font-medium">Dashboard</a>
                    <a href="books.php" class="text-gray-500 hover:text-primary dark:hover:text-white transition px-1 py-2 font-medium">Local Catalog</a>
                    <a href="global_library.php" class="text-primary font-semibold border-b-2 border-primary px-1 py-2">Global Library</a>
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
                            <span class="font-bold text-sm leading-tight group-hover:text-primary transition"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 capitalize"><?php echo $role ?? 'student'; ?></span>
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
                <h1 class="text-3xl font-bold">OpenLibrary API Catalog</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Fetching live data of 200+ books from the actual OpenLibrary.org database.</p>
            </div>
        </div>

        <div class="glass rounded-2xl p-4 shadow-md mb-10 flex flex-wrap gap-4 items-center justify-center">
            <button onclick="fetchBooks('islam')" class="category-btn bg-primary text-white font-bold py-3 px-6 rounded-xl shadow transition transform hover:-translate-y-1">Islamic Literature</button>
            <button onclick="fetchBooks('philosophy')" class="category-btn bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 font-bold py-3 px-6 rounded-xl shadow transition transform hover:-translate-y-1 hover:bg-gray-300 dark:hover:bg-gray-600">Global Philosophy</button>
            <button onclick="fetchBooks('philosophy&language=urd')" class="category-btn bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 font-bold py-3 px-6 rounded-xl shadow transition transform hover:-translate-y-1 hover:bg-gray-300 dark:hover:bg-gray-600">Urdu Philosophy</button>
            <button onclick="fetchBooks('education')" class="category-btn bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 font-bold py-3 px-6 rounded-xl shadow transition transform hover:-translate-y-1 hover:bg-gray-300 dark:hover:bg-gray-600">Educational</button>
        </div>

        <!-- Loader -->
        <div id="loader" class="hidden flex-col items-center justify-center py-20">
            <div class="loader mb-4"></div>
            <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Fetching ~200 books from API...</p>
        </div>

        <!-- Book Grid -->
        <div id="books-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Populated via JS -->
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Fetch default category on load
            fetchBooks('islam');
        });

        async function fetchBooks(subjectQuery) {
            // Update UI Buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.className = 'category-btn bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 font-bold py-3 px-6 rounded-xl shadow transition transform hover:-translate-y-1 hover:bg-gray-300 dark:hover:bg-gray-600';
            });
            event?.target?.classList?.remove('bg-gray-200', 'text-gray-800', 'dark:bg-gray-700', 'dark:text-gray-200');
            event?.target?.classList?.add('bg-primary', 'text-white');

            const container = document.getElementById('books-container');
            const loader = document.getElementById('loader');
            
            container.innerHTML = '';
            loader.classList.remove('hidden');
            loader.classList.add('flex');

            try {
                // Fetch up to 200 books from the OpenLibrary API based on subject
                const response = await fetch(`https://openlibrary.org/search.json?subject=${subjectQuery}&limit=200`);
                const data = await response.json();
                
                loader.classList.add('hidden');
                loader.classList.remove('flex');

                if (data.docs && data.docs.length > 0) {
                    data.docs.forEach(book => {
                        const title = book.title || 'Unknown Title';
                        const author = book.author_name ? book.author_name[0] : 'Unknown Author';
                        const publishYear = book.first_publish_year || 'Unknown';
                        const isbn = book.isbn ? book.isbn[0] : 'N/A';
                        
                        // Use OpenLibrary Covers API
                        const coverImg = book.cover_i ? `https://covers.openlibrary.org/b/id/${book.cover_i}-M.jpg` : null;

                        const card = document.createElement('div');
                        card.className = 'glass rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 book-card group flex flex-col';
                        
                        let imgHTML = `<i class="fas fa-image text-gray-400 text-6xl book-cover transition duration-500"></i>`;
                        if(coverImg) {
                            imgHTML = `<img src="${coverImg}" class="book-cover w-full h-full object-cover transition duration-500" alt="Cover" onerror="this.onerror=null; this.outerHTML='<i class=\\'fas fa-image text-gray-400 text-6xl book-cover transition duration-500\\'></i>'">`;
                        }

                        card.innerHTML = `
                            <div class="h-64 overflow-hidden relative bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                                ${imgHTML}
                                <div class="absolute top-4 right-4 z-10">
                                    <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Global API</span>
                                </div>
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="text-xs font-bold text-primary mb-2 tracking-wider uppercase">ISBN: ${isbn}</div>
                                <h3 class="text-xl font-bold mb-1 line-clamp-1" title="${title}">${title}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-1"><i class="fas fa-user-edit mr-1"></i> ${author}</p>
                                
                                <div class="mt-auto flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center text-sm font-medium">
                                        <i class="fas fa-calendar-alt text-gray-400 mr-2"></i> ${publishYear}
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs font-bold px-3 py-1 rounded-full border border-gray-200 dark:border-gray-700">
                                        OpenLibrary
                                    </div>
                                </div>
                            </div>
                        `;
                        container.appendChild(card);
                    });
                } else {
                    container.innerHTML = `
                        <div class="col-span-full glass rounded-3xl p-16 text-center shadow-lg">
                            <h2 class="text-2xl font-bold mb-2">No books found in this API category.</h2>
                        </div>
                    `;
                }

            } catch (error) {
                loader.classList.add('hidden');
                loader.classList.remove('flex');
                container.innerHTML = `
                    <div class="col-span-full bg-red-100 text-red-700 p-8 rounded-2xl text-center font-bold">
                        Error connecting to the global library API. Please check your internet connection.
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
