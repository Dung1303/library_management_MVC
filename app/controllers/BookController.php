    <?php
    class BookController extends Controller
    {
        public function index()
        {
            $bookModel = $this->model('Book');
            $categoryModel = $this->model('Category');

            $limit = 15;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            $books = $bookModel->getAllBooks($limit, $offset);
            $categories = $categoryModel->getAllCategories();
            $totalBooks = $bookModel->getTotalBooksCount();
            $totalPages = ceil($totalBooks / $limit);

            $this->view('member/home', [
                'books' => $books,
                'categories' => $categories,
                'page' => $page,
                'totalPages' => $totalPages,
                'title' => 'Library Books'
            ]);
        }

        public function detail($id)
        {
            $bookModel = $this->model('Book');
            $book = $bookModel->getBookById($id);

            if (!$book) {
                header('Location: ' . BASE_URL . '/home');
                exit;
            }

            $this->view('home/detail', [
                'book' => $book,
                'title' => $book['title'] . ' - Details'
            ]);
        }
        // Hiển thị giao diện chính (Read)
        public function adminIndex()
        {
            $limit = 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            $bookModel = $this->model('Book');
            $this->view('admin/books', [
                'books' => $bookModel->getBooksAdmin($limit, $offset),
                'categories' => $this->model('Category')->getAllCategories(),
                'currentPage' => $page,
                'totalPages' => ceil($bookModel->countBooksAdmin() / $limit)
            ]);
        }

        // Xử lý lưu (Create)
        public function store()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $image = ""; // Xử lý upload ảnh nếu có
                if (!empty($_FILES['image']['name'])) {
                    $image = time() . '_' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], 'public/uploads/' . $image);
                }
                $bookId = $this->model('Book')->createBook([...$_POST, 'image_url' => $image]);
                if ($bookId && $_POST['quantity'] > 0) $this->model('Book')->addBookCopies($bookId, $_POST['quantity']);
                header('Location: ' . BASE_URL . '/book/adminIndex');
            }
        }

        // Xử lý xóa (Delete)
        public function delete($id)
        {
            $this->model('Book')->deleteBook($id);
            header('Location: ' . BASE_URL . '/book/adminIndex');
        }
    }
