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
        // Thêm sách mới & Bản sao
        public function store()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $image = "";
                if (!empty($_FILES['image']['name'])) {
                    $image = time() . '_' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], 'public/uploads/' . $image);
                }

                $bookId = $this->model('Book')->createBook([
                    'title' => $_POST['title'],
                    'author' => $_POST['author'],
                    'category_id' => $_POST['category_id'],
                    'description' => $_POST['description'],
                    'image_url' => $image
                ]);

                // Nếu tạo sách thành công, tạo luôn số lượng bản sao tương ứng
                if ($bookId && $_POST['quantity'] > 0) {
                    $this->model('Book')->addBookCopies($bookId, (int)$_POST['quantity']);
                }
                header('Location: ' . BASE_URL . '/book/adminIndex');
            }
        }
        // Cập nhật sách
        public function update($id)
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = [
                    'title' => $_POST['title'],
                    'author' => $_POST['author'],
                    'category_id' => $_POST['category_id'],
                    'description' => $_POST['description']
                ];
                if (!empty($_FILES['image']['name'])) {
                    $data['image_url'] = time() . '_' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], 'public/uploads/' . $data['image_url']);
                }
                $this->model('Book')->updateBook($id, $data);
                header('Location: ' . BASE_URL . '/book/adminIndex');
            }
        }

        // Xóa sách
        public function delete($id)
        {
            $this->model('Book')->deleteBook($id);
            header('Location: ' . BASE_URL . '/book/adminIndex');
        }
    }
