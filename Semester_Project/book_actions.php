<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$role = $_SESSION['role'] ?? 'student';
if ($role === 'admin') {
    $role = 'staff';
    $_SESSION['role'] = 'staff';
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // File upload logic
    $cover_image = 'default_cover.png';
    $upload_dir = 'uploads/';
    $has_new_image = false;

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['cover_image']['tmp_name'];
        $file_name = basename($_FILES['cover_image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = uniqid('cover_') . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $cover_image = $new_file_name;
                $has_new_image = true;
            } else {
                $_SESSION['error'] = 'Failed to move uploaded file.';
                redirect('books.php');
            }
        } else {
            $_SESSION['error'] = 'Invalid file type for cover image.';
            redirect('books.php');
        }
    }
    
    try {
        if ($action === 'add' && $role === 'staff') {
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
            $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_STRING);
            $published_year = filter_input(INPUT_POST, 'published_year', FILTER_VALIDATE_INT);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING) ?: 'General';
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING) ?: 'Available';

            if ($title && $author && $isbn && $published_year && $quantity) {
                $stmt = $pdo->prepare('SELECT id FROM books WHERE isbn = ?');
                $stmt->execute([$isbn]);
                if ($stmt->fetch()) {
                    $_SESSION['error'] = 'A book with this ISBN already exists.';
                } else {
                    $stmt = $pdo->prepare('INSERT INTO books (title, author, isbn, published_year, quantity, cover_image, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                    if ($stmt->execute([$title, $author, $isbn, $published_year, $quantity, $cover_image, $category, $status])) {
                        $_SESSION['message'] = 'Book asset added successfully.';
                    } else {
                        $_SESSION['error'] = 'Failed to add book.';
                    }
                }
            } else {
                $_SESSION['error'] = 'Invalid input data.';
            }
            redirect('books.php');

        } elseif ($action === 'edit' && $role === 'staff') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
            $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_STRING);
            $published_year = filter_input(INPUT_POST, 'published_year', FILTER_VALIDATE_INT);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING) ?: 'General';
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING) ?: 'Available';

            if ($id && $title && $author && $isbn && $published_year && $quantity) {
                $stmt = $pdo->prepare('SELECT id FROM books WHERE isbn = ? AND id != ?');
                $stmt->execute([$isbn, $id]);
                if ($stmt->fetch()) {
                    $_SESSION['error'] = 'Another book with this ISBN already exists.';
                } else {
                    if ($has_new_image) {
                        $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, isbn = ?, published_year = ?, quantity = ?, cover_image = ?, category = ?, status = ? WHERE id = ?');
                        $success = $stmt->execute([$title, $author, $isbn, $published_year, $quantity, $cover_image, $category, $status, $id]);
                    } else {
                        $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, isbn = ?, published_year = ?, quantity = ?, category = ?, status = ? WHERE id = ?');
                        $success = $stmt->execute([$title, $author, $isbn, $published_year, $quantity, $category, $status, $id]);
                    }
                    if ($success) $_SESSION['message'] = 'Book updated successfully.';
                    else $_SESSION['error'] = 'Failed to update book.';
                }
            } else {
                $_SESSION['error'] = 'Invalid input data.';
            }
            redirect('books.php');

        } elseif ($action === 'delete' && $role === 'staff') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $stmt = $pdo->prepare('SELECT cover_image FROM books WHERE id = ?');
                $stmt->execute([$id]);
                $book = $stmt->fetch();
                if ($book && $book['cover_image'] !== 'default_cover.png') {
                    @unlink($upload_dir . $book['cover_image']);
                }
                $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
                if ($stmt->execute([$id])) $_SESSION['message'] = 'Book asset removed.';
                else $_SESSION['error'] = 'Failed to delete book.';
            }
            redirect('books.php');

        } elseif ($action === 'borrow_book' && in_array($role, ['student', 'faculty'])) {
            $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
            if ($book_id) {
                // Check if already borrowed this book
                $stmt = $pdo->prepare("SELECT id FROM borrowings WHERE user_id = ? AND book_id = ? AND status = 'borrowed'");
                $stmt->execute([$user_id, $book_id]);
                if ($stmt->fetch()) {
                    $_SESSION['error'] = 'You have already borrowed this book.';
                    redirect('books.php');
                    exit;
                }

                // Check student limits
                if ($role === 'student') {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrowings WHERE user_id = ? AND status = 'borrowed'");
                    $stmt->execute([$user_id]);
                    $borrowed_count = $stmt->fetchColumn();
                    if ($borrowed_count >= 2) {
                        $_SESSION['error'] = 'Limit reached. You have already borrowed 2 books. Please return a book first.';
                        redirect('books.php');
                        exit;
                    }
                }

                // Check book availability
                $stmt = $pdo->prepare("SELECT quantity, status FROM books WHERE id = ?");
                $stmt->execute([$book_id]);
                $book = $stmt->fetch();

                if ($book && $book['quantity'] > 0 && $book['status'] === 'Available') {
                    $pdo->beginTransaction();
                    try {
                        // Insert borrowing record with 17-week due date
                        $stmt = $pdo->prepare("INSERT INTO borrowings (user_id, book_id, due_date) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 17 WEEK))");
                        $stmt->execute([$user_id, $book_id]);

                        // Update book quantity and status
                        $new_qty = $book['quantity'] - 1;
                        $new_status = ($new_qty == 0) ? 'Borrowed' : 'Available';
                        $stmt = $pdo->prepare("UPDATE books SET quantity = ?, status = ? WHERE id = ?");
                        $stmt->execute([$new_qty, $new_status, $book_id]);
                        
                        $pdo->commit();
                        if ($role === 'student') {
                            $_SESSION['message'] = 'Book borrowed successfully! You have 17 weeks (a full semester) to return it. Note: Overdue books incur a fine of Rs. 50 per day.';
                        } else {
                            $_SESSION['message'] = 'Book borrowed successfully! You have 17 weeks (a full semester) to return it.';
                        }
                    } catch (Exception $e) {
                        $pdo->rollBack();
                        $_SESSION['error'] = 'Error processing borrow request.';
                    }
                } else {
                    $_SESSION['error'] = 'This book is out of stock. Please join the waitlist instead.';
                }
            }
            redirect('books.php');

        } elseif ($action === 'join_waitlist' && in_array($role, ['student', 'faculty'])) {
            $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
            if ($book_id) {
                // Check if already on waitlist
                $stmt = $pdo->prepare("SELECT id FROM waitlist WHERE user_id = ? AND book_id = ?");
                $stmt->execute([$user_id, $book_id]);
                if ($stmt->fetch()) {
                    $_SESSION['error'] = 'You are already on the waitlist for this book.';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO waitlist (user_id, book_id) VALUES (?, ?)");
                    if ($stmt->execute([$user_id, $book_id])) {
                        $_SESSION['message'] = 'You have successfully joined the waitlist.';
                    } else {
                        $_SESSION['error'] = 'Error joining waitlist.';
                    }
                }
            }
            redirect('books.php');

        } elseif ($action === 'approve_user' && $role === 'staff') {
            $target_user_id = filter_input(INPUT_POST, 'target_user_id', FILTER_VALIDATE_INT);
            if ($target_user_id) {
                $stmt = $pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
                if ($stmt->execute([$target_user_id])) {
                    $_SESSION['message'] = 'Account approved successfully.';
                } else {
                    $_SESSION['error'] = 'Error approving account.';
                }
            }
            redirect('dashboard.php');

        } elseif ($action === 'return_book' && in_array($role, ['student', 'faculty'])) {
            $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
            if ($book_id) {
                $pdo->beginTransaction();
                try {
                    // Update borrowing record
                    $stmt = $pdo->prepare("UPDATE borrowings SET status = 'returned', return_date = CURRENT_TIMESTAMP WHERE user_id = ? AND book_id = ? AND status = 'borrowed'");
                    $stmt->execute([$user_id, $book_id]);

                    if ($stmt->rowCount() > 0) {
                        // Increment book quantity
                        $stmt = $pdo->prepare("UPDATE books SET quantity = quantity + 1, status = 'Available' WHERE id = ?");
                        $stmt->execute([$book_id]);
                        $pdo->commit();
                        $_SESSION['message'] = 'Book returned successfully!';
                    } else {
                        $pdo->rollBack();
                        $_SESSION['error'] = 'Error returning book. Record not found.';
                    }
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $_SESSION['error'] = 'Error processing return request.';
                }
            }
            redirect('dashboard.php');
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database Error: ' . $e->getMessage();
        redirect('books.php');
    }
} else {
    redirect('books.php');
}
?>
