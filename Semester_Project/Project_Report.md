# Project Report: UETM Library

## 1. Project Overview
The **UETM Library Portal** is a fully responsive, dynamic web application designed to manage a university library's book inventory and user authentication securely. It demonstrates a massive upgrade from a basic CRUD application to a fully-featured, API-driven platform with premium UI/UX.

## 2. Technologies Used
- **Frontend:** HTML5, Tailwind CSS (Glassmorphism UI), Vanilla JavaScript, Chart.js.
- **Backend:** PHP (Plain PHP), REST API architecture.
- **Database:** MySQL (relational database).
- **Other:** PDO for secure database access, OpenLibrary API (External JSON fetching), Password Hashing API.

## 3. Core Features
1. **Premium User Interface:** A highly customized glassmorphism design with animated gradients, modern typography, and a dark mode toggle.
2. **User Authentication:** Secure login and registration system with hashed passwords.
3. **Advanced CRUD Operations:** Manage books with extended attributes (Category, Status, Cover Images).
4. **File Uploads:** Upload image files for book covers safely to an `uploads/` directory.

## 4. Advanced API Features
1. **External API Integration (OpenLibrary API):** The "Add Asset" modal features an auto-fetch button. Users can enter an ISBN, click the fetch icon, and JavaScript will query the OpenLibrary external API to automatically populate the Book Title, Author, and Published Year!
2. **Internal REST API Endpoint:** The project serves its own JSON API at `api.php`. Navigating to `api.php?endpoint=books&api_key=uetm_secret_key_2026` returns a JSON payload of the entire library catalog, mimicking real-world backend services.

## 5. Additional Features
1. **Dashboard Analytics (Chart.js):** Interactive JavaScript charts rendering statistics on books added.
2. **Search Functionality:** Real-time search by Title, Author, ISBN, or Category.
3. **Status Tracking:** Books are visibly tracked as "Available" or "Borrowed".

## 6. Database Schema
The database `library_portal` consists of two main tables:
- `users`: Stores user credentials (`id`, `username`, `email`, `password`, `role`).
- `books`: Stores book information (`id`, `title`, `author`, `category`, `isbn`, `cover_image`, `published_year`, `quantity`, `status`).

*(The complete SQL schema is provided in the `database.sql` file).*

## 7. Installation & Setup
1. Import the `database.sql` file into your MySQL server.
2. Ensure the `uploads/` directory has write permissions (`chmod 777 uploads`).
3. Start an Apache server (e.g., XAMPP) and access the project via `http://localhost/Semester_Project/`.
