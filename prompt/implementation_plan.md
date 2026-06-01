# Refactoring Codebase to "Senior Dev" Standards

Sau khi kiểm tra toàn bộ mã nguồn (cả Client và Admin), tôi nhận thấy dự án đã có những bước tiến lớn (như tạo `BaseController`, cải thiện giao diện bằng các thành phần Shadcn). Tuy nhiên, dưới góc nhìn của một Senior Developer, dự án **vẫn chưa hoàn toàn đạt chuẩn**. Còn một số điểm "nặng" cần phải giải quyết để dễ quản lý API, tái sử dụng mã và bảo mật tốt hơn.

## Open Questions
- Bạn có muốn cài đặt **Composer** để quản lý Autoloading chuẩn PSR-4 không? Đây là tiêu chuẩn bắt buộc của các dự án PHP hiện đại.
- Việc tách hoàn toàn logic xử lý (PHP) ra khỏi giao diện (HTML) ở các trang thêm/sửa có thể thay đổi cấu trúc file một chút, bạn có đồng ý để tôi tái cấu trúc lại các thư mục này không?

## Vấn đề hiện tại (Tại sao chưa chuẩn Senior)

> [!WARNING]
> **Lỗ hổng bảo mật nghiêm trọng:** Hiện tại chỉ có các file `delete.php` được bảo vệ bằng hàm `authorize('ADMIN')`. Các trang như `add_animal.php`, `update_animal.php` **không hề kiểm tra quyền**. Bất kỳ ai biết URL đều có thể truy cập và thực hiện thao tác Thêm/Sửa vào cơ sở dữ liệu.

1. **Thiếu Autoloading (Dependency Management):**
   - Hiện tại, mọi file đều phải gọi `require_once '../../controller/UserController.php';` thủ công. Rất khó bảo trì và dễ gây lỗi đường dẫn.
2. **Logic và View bị trộn lẫn (MVC chưa triệt để):**
   - Trong các file như `add_animal.php`, phần xử lý form (POST) và giao diện (HTML) nằm chung. Chuẩn Senior yêu cầu phần POST phải được xử lý bên trong một method của `AnimalController` (ví dụ: `store()`), sau đó mới redirect.
3. **Quản lý Routing (Định tuyến):**
   - Người dùng truy cập trực tiếp vào các file `.php` vật lý thay vì thông qua một luồng (Router). Mặc dù việc xây dựng lại toàn bộ Router có thể hơi tốn thời gian, nhưng ít nhất chúng ta phải gom logic xử lý vào Controller.

## Proposed Changes (Kế hoạch Refactor)

### 1. Triển khai Autoloading (Loại bỏ require_once)
#### [NEW] [composer.json](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/composer.json)
- Khởi tạo Composer với chuẩn PSR-4 autoloading cho thư mục `controller` và `model`.
- Xóa bỏ hàng trăm dòng `require_once` thừa thãi trên toàn bộ dự án.

### 2. Bảo mật toàn diện khu vực Admin
#### [MODIFY] Admin Action Scripts (`view/admin/*/*.php`)
- Cập nhật **TẤT CẢ** các trang (Thêm, Sửa, Dashboard) để gọi `$controller->authorize('ADMIN', '/Home');` ở ngay đầu file.
- Không để bất kỳ file nào trong thư mục `/admin/` có thể bị truy cập bởi User thường hoặc khách.

### 3. Đưa logic vào Controller (Chuẩn MVC)
#### [MODIFY] [AnimalController.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/controller/AnimalController.php) (và các controller khác)
- Tạo thêm các method chuyên biệt như `store()`, `update()`.
- Di chuyển đoạn mã xử lý file upload và insert Database từ `add_animal.php` sang `AnimalController::store()`.
- Lặp lại quy trình này cho User, Post, Comment.

## Verification Plan
### Automated & Manual Testing
- Chạy thử việc truy cập `add_animal.php` bằng tài khoản User thường -> Phải bị văng ra trang chủ với thông báo lỗi quyền.
- Test quá trình Autoloading: Đảm bảo mọi trang vẫn chạy bình thường sau khi xóa `require_once`.
- Thử thêm mới / cập nhật động vật để đảm bảo logic trong Controller hoạt động mượt mà.
