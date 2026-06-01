# Kiến Trúc Hệ Thống Chuẩn Senior Developer — NEKOPARA

Tài liệu này cung cấp một bản thiết kế (Architectural Blueprint) và lộ trình tái cấu trúc (Refactoring Plan) cho dự án PHP hiện tại theo các chuẩn mực phát triển phần mềm chuyên nghiệp. Mục tiêu là tối ưu hóa khả năng tái sử dụng mã nguồn, đơn giản hóa quản lý API và đảm bảo hệ thống dễ dàng mở rộng, bảo trì trong tương lai.

---

## I. Phân Tích Thực Trạng Hệ Thống Hiện Tại

Dựa trên việc kiểm tra toàn bộ mã nguồn của các lớp Controller (`AnimalController`, `UserController`,...) và Model (`Animal.php`, `User.php`,...), chúng tôi nhận diện các điểm hạn chế cần được cải tiến dưới góc nhìn Senior Developer:

1.  **Trùng lặp mã khởi tạo kết nối (Database Tight Coupling)**:
    *   Mỗi Controller đều tự thực hiện `new Database()` và `$database->getConnection()` trong hàm khởi tạo (`__construct`). Điều này vi phạm nguyên lý **DRY (Don't Repeat Yourself)**. Nếu thay đổi cấu hình kết nối hoặc chuyển sang cơ chế quản lý kết nối tập trung (như Singleton hay Dependency Injection), nhà phát triển phải sửa thủ công ở tất cả các Controller.
2.  **Controller gánh vác quá nhiều trách nhiệm (Fat Controller / Mixed DAL)**:
    *   Các Controller hiện tại đang chứa trực tiếp các câu truy vấn SQL thô (`INSERT`, `SELECT *`, `UPDATE`...). Điều này trộn lẫn tầng logic nghiệp vụ (Business Logic) với tầng truy xuất dữ liệu (Data Access Layer - DAL).
    *   Mỗi khi nghiệp vụ thay đổi (ví dụ: chuyển từ MySQL sang PostgreSQL hoặc bổ sung cơ chế lưu bộ đệm Redis/Memcached), toàn bộ Controller sẽ bị ảnh hưởng trực tiếp.
3.  **Lặp lại logic nghiệp vụ phụ trợ (Boilerplate Log Code)**:
    *   Thao tác ghi chép nhật ký hoạt động (`Notification::record(...)`) được viết thủ công, lặp lại tại tất cả các phương thức tạo, sửa, xóa của từng thực thể.
4.  **Các phản hồi dạng thô (Lack of API/JSON Response Standards)**:
    *   Hệ thống phản hồi dữ liệu qua AJAX và điều hướng trang (Redirect) đang được triển khai ad-hoc (riêng lẻ từng file xử lý ở thư mục `view/admin/...`), thiếu một chuẩn đầu ra đồng bộ cho các API Endpoint.

---

## II. Đề Xuất Mô Hình Kiến Trúc Chuẩn Senior Developer

Để giải quyết các vấn đề trên, hệ thống cần được tái cấu trúc sang mô hình **MVC nâng cao kết hợp Repository Pattern & Service Layer** phối hợp với bộ điều hợp phản hồi API tập trung.

Sơ đồ luồng dữ liệu chuẩn hóa:
```
[Client/AJAX Request] ──> [BaseController / Router] ──> [Controller]
                                                            │
   ┌────────────────────────────────────────────────────────┘
   ▼
[Service Layer] (Xử lý logic phức tạp, kiểm tra nghiệp vụ)
   │
   ▼
[Repository Layer] (Chuyên trách truy vấn dữ liệu từ DB thông qua Base Model)
   │
   ▼
[Database (PDO/MySQL)]
```

### 1. Xây dựng Lớp Cơ Sở `BaseController` và `BaseModel`

#### A. Tối ưu kết nối và nghiệp vụ chung với `BaseModel`
Thay vì để các Model trống rỗng (Plain Objects), ta xây dựng một lớp cơ sở `BaseModel` chứa các phương thức tương tác CSDL chung để loại bỏ hoàn toàn việc viết SQL thô trong mã nguồn thông thường:

```php
<?php
// file: model/BaseModel.php
abstract class BaseModel {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
```

#### B. Module hóa phản hồi bằng `BaseController` và `ApiResponse`
Để quản lý tốt các API, chúng ta xây dựng một bộ phản hồi dữ liệu chuẩn hóa (Response Wrapper) dưới dạng JSON cho client, giúp dễ dàng sửa đổi định dạng chung toàn hệ thống tại một nơi duy nhất.

```php
<?php
// file: lib/ApiResponse.php
class ApiResponse {
    public static function json($success, $data = null, $message = '', $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'data'    => $data,
            'message' => $message,
            'timestamp' => time()
        ]);
        exit();
    }

    public static function success($data = null, $message = 'Success') {
        return self::json(true, $data, $message, 200);
    }

    public static function error($message = 'Error occurred', $statusCode = 400) {
        return self::json(false, null, $message, $statusCode);
    }
}
```

Mọi Controller nghiệp vụ sẽ kế thừa từ `BaseController` để sở hữu các hàm dịch tự động, kiểm tra bảo mật, định dạng JSON phản hồi:

```php
<?php
// file: controller/BaseController.php
abstract class BaseController {
    protected function responseSuccess($data = null, $message = '') {
        return ApiResponse::success($data, $message);
    }

    protected function responseError($message = '', $code = 400) {
        return ApiResponse::error($message, $code);
    }

    protected function authorize($role) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['roles']) || !in_array($role, $_SESSION['roles'])) {
            $this->responseError(__('msg_unauthorized'), 403);
        }
    }
}
```

---

## III. Ứng Dụng Quy Tắc Thiết Kế Trực Quan (Theo triết lý Anti-Slop / Taste-Skill)

Khi xây dựng giao diện frontend cho các phân hệ API của Client (ví dụ: khu vực phản hồi AJAX cho bình luận, kết quả tìm kiếm AI):

1.  **Chuyển động trơn tru (Fluent Motion)**:
    *   Tránh các hiệu ứng mở rộng, thu nhỏ giật cục. Luôn áp dụng thời gian chuyển đổi từ `0.3s - 0.5s` với các đường cong easing tự nhiên như `cubic-bezier(0.165, 0.84, 0.44, 1)` thay cho `ease` hoặc `linear` mặc định.
2.  **Thiết kế kính mờ & Phân cấp (Glassmorphism & Depth)**:
    *   Sử dụng phông nền màu tối kết hợp hiệu ứng mờ `backdrop-filter: blur(16px)` kết hợp với đường viền mỏng bán trong suốt `rgba(255, 255, 255, 0.08)` để tạo chiều sâu trực quan cao cấp, ngăn chặn việc tạo ra các khối giao diện phẳng và thô cứng (generic UI slop).
3.  **Tối ưu khoảng cách và cấu trúc thông tin (Visual Hierarchies)**:
    *   Sử dụng font chữ không chân tinh tế (như Outfit, Be Vietnam Pro), độ dày chữ (Font Weight) phân cấp rõ ràng (400 cho nội dung, 600-800 cho đề mục lớn).
    *   Khoảng cách (Padding/Margin) tuân thủ hệ số nhân của 8px (8px, 16px, 24px, 32px...) tạo sự cân đối thị giác cao cấp.

---

## IV. Lộ Trình Thực Hiện Tái Cấu Trúc (Refactoring Roadmap)

1.  **Giai đoạn 1: Chuẩn hoá tầng dữ liệu (Data Access Migration)**:
    *   Khởi tạo `BaseModel` và tạo các lớp kế thừa tương ứng (`UserModel`, `AnimalModel`, `CommentModel`).
    *   Chuyển các hàm xử lý SQL từ Controller vào trực tiếp các Model tương ứng hoặc các lớp Repository mới.
2.  **Giai đoạn 2: Module hoá cấu trúc phản hồi (Response Standardization)**:
    *   Tích hợp `BaseController` và `ApiResponse`.
    *   Refactor các endpoint gọi API AJAX hiện có (như bộ bình luận AJAX, cập nhật avatar trực tiếp, hệ thống tìm kiếm AI) để sử dụng `ApiResponse::success(...)` và `ApiResponse::error(...)`.
3.  **Giai đoạn 3: Tối ưu hoá tầng giao diện (Taste-Skill Integration)**:
    *   Áp dụng đồng bộ các chuẩn thiết kế mượt mà, RGB border, và bố cục kính mờ cao cấp cho tất cả các giao diện Client.
