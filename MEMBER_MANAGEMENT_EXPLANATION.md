# 📋 GIẢI THÍCH CHI TIẾT: QUẢN LÝ THÀNH VIÊN (MEMBER MANAGEMENT)

## 🎯 Chức năng chính:
1. **Xem danh sách members** - Hiển thị tất cả thành viên trong hệ thống
2. **Thêm thành viên mới** - Tạo account mới cho member
3. **Chỉnh sửa thông tin** - Sửa full_name và email của member
4. **Khóa/Mở khóa tài khoản** - Thay đổi trạng thái (active/locked)

---

## 📂 CẤU TRÚC DATABASE

### Bảng `users` - Chứa thông tin người dùng:
```sql
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL PRIMARY KEY,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `status` enum('active','locked') DEFAULT 'active'
)
```

**Giải thích các cột:**
- **user_id**: ID duy nhất của user (tự tăng)
- **username**: Tên đăng nhập (dùng để login)
- **password**: Mật khẩu (được hash bằng BCRYPT để bảo mật)
- **full_name**: Tên đầy đủ của thành viên
- **email**: Email của thành viên
- **role**: Vai trò ('admin' hoặc 'member') - quyết định quyền hạn
- **status**: Trạng thái tài khoản:
  - `'active'` = Tài khoản hoạt động, có thể login
  - `'locked'` = Tài khoản bị khóa, không thể login

---

## 🔄 LUỒNG DỮ LIỆU (DATA FLOW)

### **1. XEM DANH SÁCH MEMBERS**

#### Step 1: User click vào "Member Management" → Router nhận request
```
URL: /admin/members
HTTP Method: GET
```

#### Step 2: Router gọi AdminController → members() method
**File:** `app/controllers/AdminController.php` (Line 50-56)
```php
public function members()
{
    // 1. Tạo instance của User Model
    $userModel = $this->model('User');
    
    // 2. Gọi hàm getAllMembers() từ User model
    //    Hàm này sẽ lấy dữ liệu từ database
    $data = [
        'members' => $userModel->getAllMembers()
    ];
    
    // 3. Gửi dữ liệu tới view (members.php)
    $this->view('admin/members', $data);
}
```

**Giải thích:**
- `$this->model('User')` → Tải User model (file: app/models/User.php)
- `getAllMembers()` → Gọi hàm lấy danh sách members từ database
- `$this->view()` → Hiển thị view và truyền dữ liệu $data

#### Step 3: User Model truy vấn DATABASE
**File:** `app/models/User.php` (Line 126-135)
```php
public function getAllMembers()
{
    // 1. Chuẩn bị câu lệnh SQL
    //    SELECT * = Lấy tất cả cột
    //    FROM users = Từ bảng users
    //    WHERE role = 'member' = Chỉ lấy những user có role là member
    //    ORDER BY full_name ASC = Sắp xếp theo tên từ A→Z
    $stmt = $this->db->prepare("
        SELECT *
        FROM users
        WHERE role = 'member'
        ORDER BY full_name ASC
    ");
    
    // 2. Thực thi câu lệnh SQL
    $stmt->execute();
    
    // 3. Lấy kết quả và trả về dưới dạng array
    //    PDO::FETCH_ASSOC = Trả về dạng associative array
    //    ['user_id'=>1, 'username'=>'john', 'full_name'=>'John Doe', ...]
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

**Kết quả từ Database (Ví dụ):**
```php
Array (
    [0] => Array (
        'user_id' => 2
        'username' => 'member01'
        'password' => '$2y$10$...' // Hash password
        'full_name' => 'Lê Thị Hoa'
        'email' => 'hoa@gmail.com'
        'role' => 'member'
        'status' => 'active'
    ),
    [1] => Array (
        'user_id' => 3
        'username' => 'member02'
        ...
    )
)
```

#### Step 4: View hiển thị dữ liệu
**File:** `app/views/admin/members.php` (Line 45-73)
```php
<!-- Hiển thị tiêu đề bảng -->
<table class="borrow-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="membersTableBody">
        <!-- Vòng lặp foreach để hiển thị từng member -->
        <?php foreach ($data['members'] as $index => $m): ?>
        <tr>
            <!-- Hiển thị STT (index + 1) -->
            <td><?= $index + 1 ?></td>
            
            <!-- Hiển thị full_name từ database -->
            <!-- htmlspecialchars() bảo vệ chống XSS attack -->
            <td><?= htmlspecialchars($m['full_name']) ?></td>
            
            <!-- Hiển thị email -->
            <td><?= htmlspecialchars($m['email']) ?></td>
            
            <!-- Hiển thị username -->
            <td><?= htmlspecialchars($m['username']) ?></td>
            
            <!-- Hiển thị status (active hay locked) -->
            <td>
                <?php if ($m['status'] === 'active'): ?>
                    <span class="badge badge-success">Active</span>
                <?php else: ?>
                    <span class="badge badge-danger">Locked</span>
                <?php endif; ?>
            </td>
            
            <!-- Nút chỉnh sửa và khóa/mở khóa -->
            <td>
                <div class="action-buttons">
                    <!-- Nút Edit: onclick gọi JS function openEditForm() -->
                    <button class="btn-edit" onclick="openEditForm(<?= htmlspecialchars(json_encode($m)) ?>)">
                        <i class="bi bi-pencil"></i>
                    </button>
                    
                    <!-- Nút Lock/Unlock: là link tới /admin/member/toggle/{user_id} -->
                    <a href="<?= BASE_URL ?>/admin/member/toggle/<?= $m['user_id'] ?>" 
                       class="<?= $m['status'] === 'active' ? 'btn-lock' : 'btn-unlock' ?>">
                        <i class="bi <?= $m['status'] === 'active' ? 'bi-lock-fill' : 'bi-unlock-fill' ?>"></i>
                    </a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

---

### **2. THÊM THÀNH VIÊN MỚI (ADD MEMBER)**

#### Step 1: User bấm "Add Member" → Hiển thị form popup
**File:** `app/views/admin/members.php` (Line 76-116)
```html
<!-- Form nằm trong một popup (ẩn ban đầu) -->
<div id="memberFormPopup" class="borrow-form" style="display: none;">
    <h3 id="formTitle">Add New Member</h3>
    <p class="subtitle" id="formSubtitle">Create a new member account</p>

    <!-- Form với method=POST để gửi dữ liệu -->
    <form id="memberForm" method="POST" action="">
        <!-- Hidden input để phân biệt add hay edit -->
        <input type="hidden" id="formMethod" name="form_method" value="add">
        <input type="hidden" id="userId" name="user_id" value="">

        <!-- Input fullname -->
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="fullName" name="fullname" required>
        </div>

        <!-- Input email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <!-- Input username -->
        <div class="form-group">
            <label>Username</label>
            <input type="text" id="username" name="username" required>
        </div>

        <!-- Input password (chỉ khi ADD, không khi EDIT) -->
        <div class="form-group" id="passwordGroup">
            <label>Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <!-- Nút Save -->
        <button type="submit" class="btn-block">
            <i class="bi bi-check-circle"></i> Save
        </button>
    </form>
</div>
```

#### Step 2: User điền form → Bấm Save → JavaScript xử lý
**File:** `public/assets/js/admin-members.js` (Line 45-68)
```javascript
// Form submit handler
document.getElementById('memberForm').addEventListener('submit', function(e) {
    // Ngăn form reload trang
    e.preventDefault();
    
    // Lấy giá trị từ form để xác định là ADD hay EDIT
    const action = document.getElementById('formMethod').value;
    
    // Lấy BASE_URL từ window variable (được set từ view)
    const baseUrl = window.BASE_URL || '/';
    
    // Đảm bảo baseUrl kết thúc bằng dấu /
    const base = baseUrl.endsWith('/') ? baseUrl : baseUrl + '/';
    
    // Xây dựng URL dựa trên action
    const url = action === 'add' 
        ? base + 'admin/member/add'
        : base + 'admin/member/edit';
    
    // Set action của form tới URL vừa tạo
    this.action = url;
    
    // Submit form
    this.submit();
});
```

#### Step 3: Form gửi POST request tới `/admin/member/add`
**File:** `app/controllers/AdminController.php` (Line 60-74)
```php
public function member($action = '', $id = '')
{
    $userModel = $this->model('User');

    // Kiểm tra nếu action='add' và request method là POST
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Lấy dữ liệu từ $_POST (dữ liệu người dùng gửi từ form)
        $result = $userModel->createMember([
            'fullname' => $_POST['fullname'] ?? '',  // Lấy fullname từ form
            'email' => $_POST['email'] ?? '',        // Lấy email từ form
            'username' => $_POST['username'] ?? '',  // Lấy username từ form
            'password' => $_POST['password'] ?? ''   // Lấy password từ form
        ]);

        // Nếu createMember() trả về true (thành công)
        if ($result) {
            // Redirect về trang danh sách members
            header('Location: ' . BASE_URL . '/admin/members');
            exit;
        }
    }
}
```

**Giải thích:**
- `$_SERVER['REQUEST_METHOD']` → Kiểm tra xem request là GET hay POST
- `$_POST['fullname']` → Lấy dữ liệu từ form field name="fullname"
- `?? ''` → Nếu không có dữ liệu thì gán giá trị rỗng
- `if ($result)` → Kiểm tra xem insert thành công không

#### Step 4: Model xử lý - lưu vào DATABASE
**File:** `app/models/User.php` (Line 137-154)
```php
public function createMember($data)
{
    // 1. Hash password để bảo mật
    //    PASSWORD_BCRYPT = Thuật toán hash mạnh
    //    Hash là một chiều: password -> hash, không thể ngược lại
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    
    // 2. Chuẩn bị câu lệnh INSERT vào bảng users
    $stmt = $this->db->prepare("
        INSERT INTO users (username, password, full_name, email, role, status) 
        VALUES (?, ?, ?, ?, 'member', 'active')
    ");
    
    // 3. Thực thi câu lệnh với dữ liệu
    //    ? là placeholder, được thay thế bằng dữ liệu trong array
    return $stmt->execute([
        $data['username'] ?? $data['fullname'],  // username = username hoặc fullname nếu không có
        $hashedPassword,                         // password = hash password
        $data['fullname'],                       // full_name = fullname
        $data['email']                           // email = email
    ]);
    // role = 'member' (hardcoded)
    // status = 'active' (mặc định active khi tạo mới)
}
```

**SQL thực tế sau khi execute:**
```sql
INSERT INTO users (username, password, full_name, email, role, status) 
VALUES ('john_doe', '$2y$10$abcd...', 'John Doe', 'john@gmail.com', 'member', 'active');
```

**Kết quả:** Một record mới được thêm vào bảng users, tự động có user_id mới.

---

### **3. CHỈNH SỬA THÔNG TIN MEMBER (EDIT)**

#### Step 1: User bấm nút Edit → Form được populate
**File:** `public/assets/js/admin-members.js` (Line 14-28)
```javascript
function openEditForm(member) {
    // member là object chứa dữ liệu member (được convert từ JSON)
    // Ví dụ: {user_id: 2, full_name: 'Hoa', email: 'hoa@gmail.com', username: 'hoa123', status: 'active'}
    
    // Thay đổi tiêu đề form thành "Edit Member"
    document.getElementById('formTitle').textContent = 'Edit Member';
    document.getElementById('formSubtitle').textContent = 'Update member information';
    
    // Set formMethod = 'edit' để sau submit sẽ gọi /admin/member/edit
    document.getElementById('formMethod').value = 'edit';
    
    // Điền user_id vào hidden field (để biết chỉnh sửa member nào)
    document.getElementById('userId').value = member.user_id;
    
    // Điền dữ liệu hiện tại vào các input field
    document.getElementById('fullName').value = member.full_name;
    document.getElementById('email').value = member.email;
    document.getElementById('username').value = member.username;
    
    // Ẩn field password (khi edit không cần nhập lại password)
    document.getElementById('passwordGroup').style.display = 'none';
    document.getElementById('password').required = false;
    
    // Hiển thị form popup
    showFormPopup();
}
```

#### Step 2: User chỉnh sửa và bấm Save → JavaScript submit form
(Xem Step 2 của "Thêm thành viên", JavaScript xây dựng URL `/admin/member/edit`)

#### Step 3: Controller xử lý UPDATE
**File:** `app/controllers/AdminController.php` (Line 76-116)
```php
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy user_id từ form (để biết update member nào)
    $userId = $_POST['user_id'] ?? '';
    
    // Lấy dữ liệu mới từ form
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // Kiểm tra xem user_id có không
    if (empty($userId)) {
        header('Location: ' . BASE_URL . '/admin/members?error=no_user_id');
        exit;
    }
    
    // Gọi hàm updateMember trong model
    $data = [
        'fullname' => $fullname,
        'email' => $email
    ];
    
    $result = $userModel->updateMember($userId, $data);
    
    // Nếu update thành công
    if ($result) {
        header('Location: ' . BASE_URL . '/admin/members');
        exit;
    } else {
        header('Location: ' . BASE_URL . '/admin/members?error=update_failed');
        exit;
    }
}
```

**Giải thích:**
- `$_POST['user_id']` → Lấy user_id từ hidden input (để biết update ai)
- `if (empty($userId))` → Kiểm tra xem user_id có hợp lệ không
- `updateMember()` → Gọi model để cập nhật database

#### Step 4: Model cập nhật DATABASE
**File:** `app/models/User.php` (Line 156-167)
```php
public function updateMember($id, $data)
{
    // Chuẩn bị câu lệnh UPDATE
    // SET full_name = ? → Cập nhật cột full_name
    // SET email = ? → Cập nhật cột email
    // WHERE user_id = ? → Chỉ update record có user_id này
    $stmt = $this->db->prepare("
        UPDATE users 
        SET full_name = ?, email = ? 
        WHERE user_id = ?
    ");
    
    // Thực thi với dữ liệu
    return $stmt->execute([
        $data['fullname'],  // Giá trị mới cho full_name
        $data['email'],     // Giá trị mới cho email
        $id                 // user_id (dùng để xác định record)
    ]);
}
```

**SQL thực tế:**
```sql
UPDATE users 
SET full_name = 'John Doe Updated', email = 'newemail@gmail.com' 
WHERE user_id = 2;
```

**Kết quả:** Record với user_id=2 được cập nhật, full_name và email thay đổi trong database.

---

### **4. KHÓA/MỞ KHÓA TÀI KHOẢN (TOGGLE STATUS)**

#### Step 1: User bấm nút Lock/Unlock
**File:** `app/views/admin/members.php` (Line 64-68)
```html
<!-- Link khóa/mở khóa -->
<a href="<?= BASE_URL ?>/admin/member/toggle/<?= $m['user_id'] ?>" 
   class="<?= $m['status'] === 'active' ? 'btn-lock' : 'btn-unlock' ?>">
    <i class="bi <?= $m['status'] === 'active' ? 'bi-lock-fill' : 'bi-unlock-fill' ?>"></i>
</a>
```

**Giải thích:**
- `<?= BASE_URL ?>/admin/member/toggle/<?= $m['user_id'] ?>` → URL link: `/admin/member/toggle/2` (nếu user_id=2)
- Nếu status='active' → hiển thị nút lock (để khóa)
- Nếu status='locked' → hiển thị nút unlock (để mở khóa)

#### Step 2: Controller xử lý toggle
**File:** `app/controllers/AdminController.php` (Line 118-126)
```php
if ($action === 'toggle' && !empty($id)) {
    // $id là user_id truyền từ URL (/admin/member/toggle/{id})
    
    // Lấy thông tin member hiện tại từ database
    $member = $userModel->getUserById($id);
    
    if ($member) {
        // Kiểm tra status hiện tại
        // Nếu status='active' → đổi thành 'locked'
        // Nếu status='locked' → đổi thành 'active'
        $newStatus = $member['status'] === 'active' ? 'locked' : 'active';
        
        // Gọi model để cập nhật status
        $userModel->updateMemberStatus($id, $newStatus);
    }
    
    // Redirect về danh sách members
    header('Location: ' . BASE_URL . '/admin/members');
    exit;
}
```

**Giải thích logic:**
1. Lấy thông tin member hiện tại (bao gồm status)
2. Nếu status hiện tại = 'active' → đổi thành 'locked'
3. Nếu status hiện tại = 'locked' → đổi thành 'active'
4. Cập nhật status vào database

#### Step 3: Model cập nhật status
**File:** `app/models/User.php` (Line 169-177)
```php
public function updateMemberStatus($id, $newStatus)
{
    // Chuẩn bị câu lệnh UPDATE chỉ cập nhật status
    $stmt = $this->db->prepare("
        UPDATE users 
        SET status = ? 
        WHERE user_id = ?
    ");
    
    // Thực thi
    return $stmt->execute([$newStatus, $id]);
}
```

**SQL thực tế:**
```sql
UPDATE users 
SET status = 'locked' 
WHERE user_id = 2;
```

---

## 🔐 BẢO MẬT (SECURITY)

### 1. **Authentication Check** (Kiểm tra quyền)
**File:** `app/controllers/AdminController.php` (Line 4-10)
```php
public function __construct()
{
    // Kiểm tra:
    // 1. $_SESSION['user_id'] tồn tại (user đã login)
    // 2. $_SESSION['role'] === 'admin' (user là admin)
    // Nếu không thỏa → redirect tới login page
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
```
**Vì sao?** Chỉ admin mới được vào trang member management.

### 2. **Password Hashing** (Mã hóa password)
**File:** `app/models/User.php` (Line 140)
```php
$hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
```
**Vì sao?** Không lưu password plain text. Nếu database bị hack, password vẫn an toàn.

### 3. **Prepared Statement** (Chống SQL Injection)
```php
$stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
```
**Vì sao?** `?` placeholder không cho phép input độc hại thực thi SQL.

### 4. **htmlspecialchars()** (Chống XSS)
```php
<?= htmlspecialchars($m['full_name']) ?>
```
**Vì sao?** Convert các ký tự đặc biệt HTML thành entity, chống script injection.

---

## 📊 TÓMMẶT: Luồng dữ liệu TOÀN BỘ

```
┌─────────────────────────────────────────────────────────────────┐
│                         USER INTERFACE                           │
│                    (HTML Form in Browser)                        │
└────────────────────────┬────────────────────────────────────────┘
                         │ (Form Submit)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                    JAVASCRIPT (Frontend)                         │
│              (admin-members.js)                                  │
│  - openAddForm() / openEditForm()                               │
│  - xây dựng URL POST request                                    │
└────────────────────────┬────────────────────────────────────────┘
                         │ (POST /admin/member/add or /edit)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                     ROUTER (Backend)                             │
│              (app/core/Router.php)                              │
│  - Parse URL: /admin/member/add → AdminController->member('add')│
└────────────────────────┬────────────────────────────────────────┘
                         │ (Call method)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                   CONTROLLER (Business Logic)                    │
│              (app/controllers/AdminController.php)              │
│  - Kiểm tra request method (GET/POST)                          │
│  - Lấy dữ liệu từ $_POST                                       │
│  - Gọi Model method                                             │
│  - Xử lý redirect                                               │
└────────────────────────┬────────────────────────────────────────┘
                         │ (Call $userModel->method())
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                      MODEL (Data Layer)                          │
│                 (app/models/User.php)                           │
│  - Chuẩn bị SQL query                                           │
│  - Hash password (nếu cần)                                      │
│  - Execute query → INSERT/UPDATE/SELECT                         │
└────────────────────────┬────────────────────────────────────────┘
                         │ (Execute SQL)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                       DATABASE                                   │
│                    (MariaDB/MySQL)                              │
│                   (Bảng: users)                                 │
│  - Lưu/Update/Select dữ liệu                                   │
└────────────────────────┬────────────────────────────────────────┘
                         │ (Return result)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                      MODEL (Return)                              │
│              Trả về true/false hoặc array dữ liệu              │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                   CONTROLLER (Return)                            │
│         Xử lý kết quả, redirect hoặc render view               │
└────────────────────────┬────────────────────────────────────────┘
                         │ (Redirect or View)
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                         VIEW (Frontend)                          │
│                   (members.php)                                 │
│  - Nhận dữ liệu từ Controller                                  │
│  - Hiển thị HTML table với dữ liệu                             │
│  - JavaScript ready để xử lý event tiếp theo                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## 💡 ĐIỂM QUAN TRỌNG CẦN GIẢI THÍCH CHO THẦY:

### 1. **Dữ liệu từ đâu?**
- Từ **DATABASE (bảng users)**
- Lấy bằng **SQL query** (SELECT, INSERT, UPDATE)
- Sử dụng **PDO** (PHP Data Objects) để kết nối

### 2. **Dữ liệu lưu ở đâu?**
- Tất cả lưu ở **bảng users** trong **database**
- Cột: user_id, username, password, full_name, email, role, status

### 3. **Session dùng cho gì?**
- Chỉ dùng để **kiểm tra quyền hạn** (admin hay member?)
- Không lưu data members (vì members thay đổi thường xuyên)
- `$_SESSION['user_id']` và `$_SESSION['role']` được set khi **login thành công**

### 4. **Lấy data từ form như thế nào?**
- Dùng `$_POST['field_name']` để lấy dữ liệu từ form
- Ví dụ: `$_POST['fullname']` lấy giá trị từ `<input name="fullname">`

### 5. **Password an toàn không?**
- ✅ **An toàn** vì dùng `password_hash(PASSWORD_BCRYPT)`
- Hash là một chiều, không thể reverse
- Khi login, dùng `password_verify()` để so sánh

---

## 🔄 VÍ DỤ THỰC TẾ: Chỉnh sửa member

**User action:**
1. Click "Edit" trên member "Hoa"
2. Form popup hiện ra với dữ liệu cũ
3. Sửa email từ "hoa@gmail.com" → "newhoa@gmail.com"
4. Click "Save"

**Behind the scenes:**

**Bước 1:** View gửi dữ liệu
```
POST /admin/member/edit
Data: {
    user_id: 3,
    fullname: "Lê Thị Hoa",
    email: "newhoa@gmail.com"
}
```

**Bước 2:** Controller nhận
```php
$userId = '3';
$fullname = 'Lê Thị Hoa';
$email = 'newhoa@gmail.com';
```

**Bước 3:** Model xây dựng SQL
```sql
UPDATE users 
SET full_name = 'Lê Thị Hoa', email = 'newhoa@gmail.com' 
WHERE user_id = 3;
```

**Bước 4:** Database thực thi
- Record với user_id=3 được cập nhật
- Cột email từ 'hoa@gmail.com' → 'newhoa@gmail.com'

**Bước 5:** Redirect về danh sách
- User quay lại trang danh sách members
- Thấy email mới của Hoa đã cập nhật ✅

---

## 📝 SCHEMA BẢNG USERS CHI TIẾT

```
┌──────────┬─────────────┬──────────┬──────────────────────────────┐
│ Column   │ Type        │ Key      │ Description                  │
├──────────┼─────────────┼──────────┼──────────────────────────────┤
│user_id   │ INT(11)     │ PRIMARY  │ ID duy nhất (auto increment) │
│username  │ VARCHAR(255)│ UNIQUE   │ Tên đăng nhập (dùng login)  │
│password  │ VARCHAR(255)│          │ Password (mã hóa BCRYPT)     │
│full_name │ VARCHAR(150)│          │ Tên đầy đủ của user          │
│email     │ VARCHAR(150)│ UNIQUE   │ Email của user               │
│role      │ ENUM        │          │ 'admin' hoặc 'member'        │
│status    │ ENUM        │          │ 'active' hoặc 'locked'       │
└──────────┴─────────────┴──────────┴──────────────────────────────┘

status = 'active':   Tài khoản hoạt động, có thể login
status = 'locked':   Tài khoản bị khóa, KHÔNG thể login
```

---

Bây giờ bạn đã hiểu rõ từng dòng code và luồng dữ liệu! Chúc bạn review code tốt! 🎉
