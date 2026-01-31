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
        // Hiển thị danh sách và phân trang
        public function adminIndex()
        {
            $bookModel = $this->model('Book');
            $catModel = $this->model('Category');

            $limit = 10; // 10 cuốn sách trên 1 trang
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            $totalBooks = $bookModel->countBooksAdmin();
            $totalPages = ceil($totalBooks / $limit);

            $books = $bookModel->getBooksAdmin($limit, $offset);
            $categories = $catModel->getAllCategories();

            $this->view('admin/books', [
                'books' => $books,
                'categories' => $categories,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'title' => 'Book Management'
            ]);
        }
    }
