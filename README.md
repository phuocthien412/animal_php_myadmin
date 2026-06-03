# 🐾 NEKOPARA - Bách khoa toàn thư Động vật 🌍

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

Nekopara là một nền tảng quản lý và khám phá kiến thức thế giới động vật. Hệ thống được xây dựng trên kiến trúc **MVC (Model - View - Controller)** thuần bằng PHP, không sử dụng framework, kết hợp giao diện tối giản hiện đại (Shadcn / Nature Theme).

---

## 💻 Yêu cầu hệ thống (System Requirements)
- **PHP**: Phiên bản `8.1` hoặc mới hơn.
- **Database**: MySQL `8.0+` hoặc MariaDB `10.4+`.
- **Web Server**: Apache hoặc Nginx (Khuyến nghị sử dụng **Laragon** hoặc **XAMPP**).
- Cần bật extension `pdo_mysql` trong `php.ini`.

---

## 📂 Cấu trúc mã nguồn (Folder Structure)

Dự án áp dụng mô hình chuẩn MVC phân tách logic rõ ràng:

```text
animal_php_myadmin/
├── config/                 # Cấu hình môi trường (Database, BASE_URL)
│   ├── env.php             # File load biến môi trường động
│   └── lang/               # Cấu hình Đa ngôn ngữ (i18n) - vi.php, en.php
├── controller/             # Lớp trung gian xử lý logic (Controllers)
│   ├── BaseController.php  # Lớp cha xử lý Database, Session, Auth, Redirect
│   ├── AnimalController.php
│   └── UserController.php  ...
├── database/               # Chứa tệp tin khởi tạo CSDL
│   └── import_data.sql     # File mã nguồn SQL chính
├── images/                 # Tài nguyên hình ảnh tĩnh & Uploads
│   ├── Posts/              # (Thư mục tự động tạo khi người dùng đăng bài)
│   └── Animal/             # (Thư mục upload ảnh động vật)
├── lib/                    # Các thư viện bổ trợ (i18n.php, Bootstrap)
├── model/                  # Đại diện cấu trúc dữ liệu Entity
├── prompt/                 # Báo cáo tiến độ & Ghi chú (tong_hop_chinh_sua.html)
├── view/                   # Giao diện người dùng (Client & Admin)
│   ├── admin/              # Dashboard quản trị viên
│   └── client/             # Giao diện khách (Trang chủ, Đăng nhập, Cộng đồng)
├── autoload.php            # Tự động import (require) các class động
└── index.php               # Điểm neo điều hướng trang chủ
```

---

## 🚀 Hướng dẫn Cài đặt & Chạy Source (How to Run)

1. **Tải mã nguồn:** Clone repository hoặc giải nén mã nguồn vào thư mục root của web server.
   - Laragon: `C:\laragon\www\animal_php_myadmin\`
   - XAMPP: `C:\xampp\htdocs\animal_php_myadmin\`
2. **Khởi động Server:** Mở Laragon/XAMPP, khởi động dịch vụ **Apache** và **MySQL**.
3. **Cấu hình đường dẫn (Tuỳ chọn):**
   - Truy cập vào file `config/env.php`. Biến `BASE_URL` đã được cấu hình tự động nhận diện domain hiện tại. Bạn hiếm khi phải sửa tệp này trừ khi thiết lập trên hosting thực tế.
4. **Truy cập dự án:** Mở trình duyệt và truy cập:
   ```text
   http://localhost/animal_php_myadmin/animal_php_myadmin/
   ```

---

## 🗄️ Hướng dẫn Import Cơ sở dữ liệu (Import Data)

Hệ thống cần có Database để hoạt động (báo lỗi màn hình trắng / lỗi PDO nếu thiếu CSDL).

1. Truy cập vào **phpMyAdmin** qua đường dẫn `http://localhost/phpmyadmin`.
2. Tạo một cơ sở dữ liệu mới (New Database) với tên là **`animal_db`** (Collation: `utf8mb4_unicode_ci`).
3. Chọn cơ sở dữ liệu `animal_db` vừa tạo.
4. Chuyển sang tab **Import (Nhập)**.
5. Bấm `Choose File` và chọn tệp **`import_data.sql`** nằm trong thư mục `database/` của dự án.
6. Bấm **Go (Thực hiện)** để hoàn tất việc nhập bảng và dữ liệu mẫu.

---

## 🔑 Tài khoản Test mặc định
Sau khi Import DB thành công, bạn có thể sử dụng các tài khoản có sẵn dưới đây:

| Vai trò | Tên Đăng Nhập | Mật khẩu (Password) | Ghi chú |
| :--- | :--- | :--- | :--- |
| **Quản trị viên** (Admin) | `admin` | `123456` | Toàn quyền thêm sửa xoá |
| **Người dùng** (Client) | `client` | `123456` | Chỉ xem, viết bài cộng đồng |

---

## ✨ Điểm nổi bật về Kiến trúc (Highlights)
- **Bảo mật Upload:** Áp dụng `.gitignore` và `.gitkeep` chuyên nghiệp để ngăn rác tải lên GitHub.
- **Hệ màu Nature / Shadcn:** UI/UX thiết kế phẳng, bắt mắt, tập trung sử dụng Glassmorphism và CSS Variables.
- **Bảo vệ Routes Admin:** Bọc 100% các route nhạy cảm thông qua phương thức `$authController->authorize('ADMIN', '/Login')` tại `BaseController.php`.
- **Đa ngôn ngữ (i18n):** Hệ thống có thể chuyển đổi linh hoạt qua hàm `__('key_name')`.
