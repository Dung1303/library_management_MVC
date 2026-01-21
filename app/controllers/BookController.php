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
                'title' => 'Thư viện Sách'
            ]);
        }
    }