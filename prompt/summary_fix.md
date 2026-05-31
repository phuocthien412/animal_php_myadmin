1. 🎨 Nâng cấp giao diện (UI/UX)
Trang Đăng nhập (Login):
Thiết kế lại hoàn toàn với chủ đề "Thiên nhiên" (Nature theme).
Layout chia 2 cột hiện đại: Cột trái là hình ảnh/màu sắc thương hiệu với animation lá rơi mượt mà, cột phải là form đăng nhập tối giản, tinh tế.
Trang Quản trị (Admin Panel):
Chuyển đổi từ thanh điều hướng ngang (top navbar) sang Sidebar dọc chuyên nghiệp hơn, giống các hệ thống quản trị hiện đại.
Thiết kế Dashboard (Bảng điều khiển) trực quan với:
Các thẻ thống kê (Stat cards) hiển thị tổng số: Người dùng, Động vật, Bài viết, Bình luận.
Tích hợp Chart.js để vẽ biểu đồ trực quan (Biểu đồ cột cho số lượng động vật theo lớp, biểu đồ tròn cho phân bổ vai trò người dùng).
Nâng cấp giao diện tất cả các bảng dữ liệu (User, Animal, Post, Comment, ClassAnimal) cho đồng bộ với thiết kế mới.
Cải thiện giao diện phân quyền (editRole) với dạng checkbox dễ thao tác hơn.
2. ⚙️ Xử lý lỗi hệ thống & Đường dẫn (Routing)
Fix lỗi 404 Not Found: Sửa lỗi định tuyến do .htaccess thiếu cấu hình RewriteBase và bắt lỗi phân biệt hoa/thường (case-sensitive).
Hệ thống biến môi trường .env:
Tạo file .env để quản lý BASE_URL linh hoạt. Giờ đây, dù bạn chạy code trên Laragon, XAMPP, hay host thật, bạn chỉ cần mở file .env sửa đúng 1 dòng đường dẫn gốc là toàn bộ trang web (từ hình ảnh, link, đến các form submit) sẽ tự động chạy đúng, không bị lỗi sai đường dẫn nữa.
Bổ sung file mẫu .env.example để người khác dễ cài đặt.
Đưa .env vào .gitignore để bảo mật thông tin cấu hình.
Sửa toàn bộ logic Redirect: Các chức năng Đăng nhập (login_process.php), Đăng xuất (logout.php), và Xoá (Delete) đều đã được cập nhật để chuyển hướng chính xác dựa vào BASE_URL cấu hình trong .env.
Tóm lại: Dự án không chỉ đẹp hơn, chuyên nghiệp hơn nhờ thiết kế UI mới, mà còn chạy mượt mà, ít lỗi và cực kỳ dễ dàng cấu hình đường dẫn khi chuyển môi trường nhờ hệ thống .env mới thêm vào.