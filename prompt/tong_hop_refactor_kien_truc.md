# Tổng hợp Tái cấu trúc Kiến trúc Hệ thống (Senior Developer Standard)

Tài liệu này tổng hợp lại các công việc "hạng nặng" đã thực hiện nhằm chuẩn hóa mã nguồn của dự án NEKOPARA, đưa kiến trúc từ mức cơ bản lên chuẩn của một Senior Developer. Trọng tâm là việc giải quyết vấn đề tái sử dụng code (DRY), bảo mật phân quyền (Authorization), và quản lý dependency (Autoloading).

## 1. Xây dựng lớp nền tảng BaseController (Chuẩn MVC)
- **Vấn đề:** Các file xử lý và Controller trước đây đều tự kết nối cơ sở dữ liệu và lặp lại rất nhiều dòng code phản hồi JSON/Redirect.
- **Giải pháp:** 
  - Tạo `BaseController.php` để khởi tạo kết nối Database tập trung một lần.
  - Cung cấp các phương thức tiện ích dùng chung: `jsonSuccess()`, `jsonError()`, `redirect()`, và `authorize()`.
  - Toàn bộ 7 Controllers (`Animal`, `User`, `Comment`, `ClassAnimal`, `Post`, `Role`, `ListAnimal`) đều được kế thừa (`extends BaseController`), giúp mã nguồn gọn gàng và dễ dàng thay đổi cấu hình DB trong tương lai.

## 2. Vá Lỗ Hổng Bảo Mật và Phân Quyền (Admin Security)
- **Vấn đề:** Trong thư mục `view/admin/`, chỉ có các file `delete.php` được gọi check phân quyền. Các trang quan trọng như `add_animal.php`, `update_animal.php`, `dashboard.php` lại hoàn toàn "mở cửa". Bất kỳ ai biết link đều có thể thêm/sửa dữ liệu mà không cần là Admin.
- **Giải pháp:**
  - Viết kịch bản (Script) tự động rà quét và cấy mã bảo mật `$authController->authorize('ADMIN', '/Home');` vào **dòng đầu tiên** của TẤT CẢ các file xử lý trong khu vực Admin (hơn 20 file).
  - Khắc phục xung đột `session_start()` ở `profile.php` do gọi 2 lần (lần 1 trong `authorize()`, lần 2 trong file gốc). 

## 3. Triển khai Hệ thống Autoloading (Dependency Management)
- **Vấn đề:** Dự án sử dụng thủ công hàm `require_once` để gọi Controller ở gần 50 vị trí khác nhau trên toàn bộ Client và Admin. Việc này gây ra rác mã nguồn, khó quản lý đường dẫn và rất dễ lỗi khi di chuyển file.
- **Giải pháp:**
  - Vì máy tính không có sẵn Composer trong PATH, tôi đã tự thiết kế một hệ thống **Autoloading Nguyên bản** thông qua `spl_autoload_register`.
  - File `autoload.php` được cấu hình tự động quét các thư mục `controller/` và `model/`.
  - Tích hợp `autoload.php` vào luồng chạy chính tại `config/env.php`.
  - **Dọn dẹp mã nguồn:** Dùng script gỡ bỏ hàng loạt các dòng `require_once '../../controller/...';` thừa thãi trên toàn dự án.

## 4. Nâng cấp Giao diện và Trải nghiệm Người dùng (Client UI/UX)
Bên cạnh kiến trúc, phần nhìn cũng được nâng cấp theo thẩm mỹ hiện đại (chuẩn Shadcn):
- **Search Bar:** Cải tiến nút tìm kiếm với đường cong mượt mà, hiệu ứng xoay (rotation) nhẹ, và icon tinh tế hơn.
- **Footer (RGB & Glassmorphism):** Xóa bỏ thiết kế cũ nhàm chán, thay bằng Footer nền tối cao cấp với hiệu ứng đèn Neon RGB chạy ngang, các nút hover nổi bật và mang đậm chất công nghệ/đẹp mắt.

---

### Đánh Giá Tương Lai
Dự án hiện tại đã đạt độ an toàn và ổn định rất cao về mặt lõi kiến trúc. Các bước tiếp theo có thể cân nhắc là:
1. Đưa toàn bộ Logic xử lý POST (Form Submissions) đang nằm trong các file View (như `add_animal.php`) vào hẳn các hàm `store()`, `update()` trong Controller để đạt chuẩn MVC tuyệt đối 100%.
2. Cài đặt các thư viện Front-end qua npm/yarn nếu muốn làm các tính năng SPA (Single Page Application).
