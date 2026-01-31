<?php
class AdminController extends Controller
{
    public function __construct()
    {
        // Bảo mật: Yêu cầu đăng nhập với vai trò admin cho tất cả các chức năng trong controller này
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        // Lấy dữ liệu từ các model để hiển thị trên dashboard
        $bookModel = $this->model('Book');
        $userModel = $this->model('User');
        $borrowModel = $this->model('BorrowRecord');

        // Lấy dữ liệu thống kê
        $totalBooks = $bookModel->getTotalBooksCount();
        $totalMembers = $userModel->getTotalUsersCount();
        $availableBooks = $bookModel->getAvailableCopiesCount();
        $overdueBooks = $borrowModel->getOverdueBooksCount();
        $currentlyBorrowed = $borrowModel->getTotalBorrowingCount();

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - LibraSys',
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'availableBooks' => $availableBooks,
            'overdueBooks' => $overdueBooks,
            'currentlyBorrowed' => $currentlyBorrowed,
        ]);
    }

    public function members()
    {
        $data = [
            'members' => []
        ];
        $this->view('admin/members', $data);
    }
    // --- QUẢN LÝ SÁCH ---

    public function books()
    {
        $bookModel = $this->model('Book');
        $categoryModel = $this->model('Category');

        // Phân trang
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Lấy dữ liệu
        $books = $bookModel->getAllBooks($limit, $offset);
        $categories = $categoryModel->getAllCategories(); // Để hiển thị trong Modal Add/Edit
        $totalBooks = $bookModel->getTotalBooksCount();
        $totalPages = ceil($totalBooks / $limit);

        $this->view('admin/books', [
            'books' => $books,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    // Xử lý thêm mới sách
    public function storeBook()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("=== DEBUG: storeBook called ===");
            error_log("POST data: " . json_encode($_POST));

            $bookModel = $this->model('Book');

            // Xử lý upload ảnh
            $imageUrl = 'default-book.png'; // Ảnh mặc định
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = __DIR__ . "/../../public/uploads/books/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $imageUrl = $fileName;
                    error_log("Image uploaded: " . $imageUrl);
                }
            }

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'author' => trim($_POST['author'] ?? ''),
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'description' => trim($_POST['description'] ?? ''),
                'image_url' => $imageUrl
            ];

            error_log("Data to insert: " . json_encode($data));
            error_log("Data types - category_id: " . gettype($data['category_id']) . ", value: " . $data['category_id']);

            $bookId = $bookModel->addBook($data);

            error_log("BookId returned: " . var_export($bookId, true));

            if ($bookId) {
                // Thêm các bản sao (Quantity)
                $quantity = (int)($_POST['quantity'] ?? 0);
                if ($quantity > 0) {
                    $bookModel->addBookCopies($bookId, $quantity);
                    error_log("Book copies added: " . $quantity);
                }
                $_SESSION['success'] = "Book added successfully!";
                error_log("SUCCESS: Book added");
            } else {
                $_SESSION['error'] = "Failed to add book.";
                error_log("ERROR: addBook returned false/null");
            }
            header('Location: ' . BASE_URL . '/admin/books');
        }
    }

    // Xử lý cập nhật sách
    public function updateBook()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bookModel = $this->model('Book');

            $imageUrl = '';
            // Nếu có upload ảnh mới thì xử lý
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = __DIR__ . "/../../public/uploads/books/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $fileName)) {
                    $imageUrl = $fileName;
                }
            }

            $data = [
                'book_id' => $_POST['book_id'],
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'category_id' => $_POST['category_id'],
                'description' => $_POST['description'],
                'image_url' => $imageUrl
            ];

            if ($bookModel->updateBook($data)) {
                $_SESSION['success'] = "Book updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update book.";
            }
            header('Location: ' . BASE_URL . '/admin/books');
        }
    }

    // Xử lý xóa sách
    public function deleteBook($id)
    {
        $bookModel = $this->model('Book');
        if ($bookModel->deleteBook($id)) {
            $_SESSION['success'] = "Book deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete book.";
        }
        header('Location: ' . BASE_URL . '/admin/books');
    }
}
