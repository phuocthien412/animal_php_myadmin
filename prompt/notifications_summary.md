# Tóm tắt thay đổi — Hệ thống Thông báo (Notifications)

Ngày: 01/06/2026

## Mục tiêu
- Chuyển hệ thống thông báo sang log trung tâm chỉ dành cho admin.
- Ghi lại các hành động quan trọng (tạo/cập nhật/xóa/ẩn/hiện) từ controller để admin theo dõi.
- Cải thiện header dropdown và trang `Thông báo` để đọc từ bảng `notifications`.
- Giảm tải từ client-side SSE/polling bằng cách dùng bảng lưu log, sau này sẽ tối ưu realtime.

## Những thay đổi chính

### 1) Model + DB
- Thêm `model/Notification.php` — helper để ghi/đọc/đếm/đánh dấu đọc thông báo.
- Đã thêm DDL tạo bảng `notifications` vào `import_data.sql` (nếu chưa chạy, cần import thủ công):

  - Bảng `notifications` bao gồm: `id, type, action, title, message, link, target_type, target_id, actor_id, actor_name, actor_roles, meta_json, is_read, created_at`.

### 2) Controllers (gọi log)
- Các controller đã được instrumented để gọi `Notification::record(...)` khi có sự kiện:
  - `controller/PostController.php`
  - `controller/CommentController.php`
  - `controller/AnimalController.php`
  - `controller/ClassAnimalController.php`
  - `controller/ListAnimalController.php`
  - `controller/UserController.php`
  - `controller/RoleController.php`

  Mỗi lần tạo/cập nhật/xóa/ẩn/hiện sẽ ghi 1 row vào `notifications` với `type`, `action`, `title`, `message`, `link`, `target_type`, `target_id`, `meta`.

### 3) Endpoint & Views
- `admin/notifications.php`:
  - Chuyển sang đọc từ `Notification::getRecent()` thay vì scan posts/comments.
  - SSE: so sánh theo `id` (numeric) để phát dữ liệu mới; gửi tối đa 5 mục mới mỗi lần.
  - Khi mở trang (non-SSE), gọi `Notification::markAllAsRead()`.
- Views:
  - `view/headerAdmin.php`: dùng `Notification::getRecent(5)` và `Notification::getUnreadCount()` để hiển thị dropdown + badge.
  - `view/admin/notifications/notification-list.php`: render danh sách thông báo từ model (hỗ trợ `type` khác nhau: post, comment, animal, classanimal, user, role, ...).

### 4) CSS
- `css/admin.css`: sửa `.notification-icon` để đảm bảo nền mặc định và glyph hiển thị (đã thêm background mặc định, đảm bảo `i` màu trắng).

## Danh sách file đã sửa/ thêm (tương đối)
- Added: `model/Notification.php`
- Modified:
  - `admin/notifications.php`
  - `view/headerAdmin.php`
  - `view/admin/notifications/notification-list.php`
  - `css/admin.css`
  - `controller/PostController.php`
  - `controller/CommentController.php`
  - `controller/AnimalController.php`
  - `controller/ClassAnimalController.php`
  - `controller/ListAnimalController.php`
  - `controller/UserController.php`
  - `controller/RoleController.php`
- Schema: appended to `import_data.sql` (CREATE TABLE `notifications` ...)

## Hướng dẫn áp dụng (ngắn)
1. Import schema (nếu chưa có):

```bash
# từ thư mục dự án (ví dụ dùng mysql client)
mysql -u <user> -p <database> < import_data.sql
```

Hoặc đảm bảo PHP có quyền tạo bảng — `Notification::ensureTable()` sẽ cố tạo nếu kết nối DB cho phép.

2. Khởi động/refresh server PHP (nếu dùng built-in hoặc Laragon): reload trang admin để thấy badge và dropdown.

## Gợi ý bước tiếp theo (tùy chọn)
- Thêm API để đánh dấu từng thông báo là đã đọc (`POST /admin/notifications/mark-read?id=...`).
- Chuẩn hóa `type` khi gọi `Notification::record(...)` để đảm bảo class CSS khớp.
- Nâng cấp SSE sang pub/sub (Redis) để realtime hiệu quả hơn khi nhiều client.

---
Nếu bạn muốn mình cập nhật file README hoặc tạo migration script PHP/SQL riêng cho việc tạo bảng `notifications`, mình có thể làm tiếp.