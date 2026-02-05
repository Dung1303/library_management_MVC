# Library Management System (LMS)

Hệ thống quản lý thư viện được xây dựng bằng **PHP thuần** theo kiến trúc **MVC (Model-View-Controller)**. Dự án tập trung vào hiệu suất, mã nguồn sạch và khả năng mở rộng, hỗ trợ quản lý chi tiết đến từng bản sao sách (Book Copies) thông qua mã vạch.

##  Giới thiệu

Dự án giải quyết bài toán quản lý thư viện hiện đại:
- **Quản lý bản sao (Book Copies):** Không chỉ quản lý đầu sách, hệ thống theo dõi từng cuốn sách vật lý bằng mã vạch (Barcode: `BC-{BookID}-{CopyNumber}`).
- **Phân quyền chặt chẽ:** Hệ thống phân quyền rõ ràng giữa Admin (Thủ thư) và Member (Độc giả).
- **Tự động hóa:** Hỗ trợ nhập liệu sách hàng loạt từ Excel.

##  Cấu trúc dự án

Dựa trên kiến trúc MVC, dự án được tổ chức như sau:

```plaintext
library_management_MVC/
├── app/                    # Core Logic
│   ├── controllers/        # Xử lý điều hướng & logic nghiệp vụ
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── BookController.php
│   │   ├── MemberController.php
│   │   └── TransactionController.php
│   ├── models/             # Tương tác Database (PDO)
│   │   ├── Book.php        # Quản lý đầu sách & Import Excel
│   │   ├── BookCopy.php    # Quản lý bản sao & Barcode
│   │   ├── BorrowRecord.php# Quản lý mượn trả
│   │   ├── Category.php    # Quản lý danh mục
│   │   └── User.php        # Quản lý thành viên & xác thực
│   └── views/              # Giao diện (HTML/PHP)
│       ├── admin/          # Dashboard, Books, Members...
│       ├── auth/           # Login, Register
│       ├── layouts/        # Header, Footer, Sidebar
│       └── members/        # Home, Search, History
├── config/                 # Cấu hình (Database, Constants)
│   └── database.php
│   └── config.php
├── public/                 # Web Root (CSS, JS, Images, index.php)
│   ├── css/
│   ├── images/
│   ├── js/
│   ├── uploads/            # Ảnh bìa sách
│   └── index.php
├── vendor/                 # Thư viện Composer (PhpSpreadsheet,...)
├── .htaccess               # Cấu hình Pretty URL
├── composer.json           # Quản lý dependencies
└── database.sql            # Script khởi tạo CSDL
```

##  Tính năng chính

### 1. Quản trị viên (Admin)
- **Quản lý Sách (Advanced):**
  - Thêm, sửa, xóa đầu sách.
  - **Import Excel:** Nhập hàng loạt sách và tự động tạo bản sao kèm mã vạch.
  - Quản lý trạng thái bản sao (Available, Damaged, Lost).
- **Quản lý Thành viên:**
  - Xem danh sách, tìm kiếm thành viên.
  - Khóa/Mở khóa tài khoản thành viên vi phạm.
- **Quản lý Mượn/Trả:** Xử lý giao dịch mượn trả dựa trên mã vạch.

### 2. Thành viên (Member)
- **Tài khoản:** Đăng ký, Đăng nhập, Cập nhật hồ sơ cá nhân.
- **Tra cứu:** Tìm kiếm sách theo tên, tác giả, danh mục.
- **Lịch sử:** Xem trạng thái mượn sách cá nhân.

##  Công nghệ sử dụng

- **Backend:** PHP 8.x (Pure MVC).
- **Database:** MySQL (PDO Driver).
- **Frontend:** HTML5, CSS3, JavaScript.
- **Libraries:**
  - `phpoffice/phpspreadsheet`: Xử lý nhập liệu từ file Excel.
- **Tools:** Composer, Git, XAMPP/Laragon.

##  Cài đặt & Triển khai

1.  **Clone dự án:**
    ```bash
    git clone https://github.com/tungominh/library_management_MVC.git
    cd library_management_MVC
    ```

2.  **Cài đặt thư viện:**
    ```bash
    composer install
    ```

3.  **Cấu hình Database:**
    - Tạo database `library_db` trong MySQL.
    - Import file `database.sql`.
    - Cấu hình thông tin kết nối trong `config/database.php`.

4.  **Chạy ứng dụng:**
    - Cấu hình Virtual Host trỏ về thư mục `public/`.
    - Truy cập trình duyệt: `http://localhost/library_management_MVC/public` (hoặc domain ảo đã cấu hình).

##  Thành viên thực hiện

- **Ngô Minh Tú** (Backend & Architecture)
- **Nguyễn Thị Dung**
- **Nguyễn Phúc Khuê**
- **Nguyễn Thu Trang**

---
*Dự án được phát triển cho mục đích học tập và thực hành kiến trúc MVC trong PHP.*