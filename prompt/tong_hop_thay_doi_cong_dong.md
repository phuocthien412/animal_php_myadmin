# Tổng hợp các thay đổi và nâng cấp giao diện Cộng Đồng

Tài liệu này tổng hợp toàn bộ các tính năng mới, bản vá lỗi, và nâng cấp thiết kế (UI/UX) đã được thực hiện trong đợt cập nhật gần nhất cho module Cộng đồng (Posts & Comments).

## 1. Nâng cấp Tính năng Tương tác (Likes & Share)

- **Hệ thống Like Bình luận (Comment Likes):** 
  - Khởi tạo bảng dữ liệu mới để theo dõi lượt thích bình luận của người dùng.
  - Cập nhật `CommentController.php` để xử lý logic thêm/bớt lượt thích và đếm tổng lượt thích.
  - Tạo endpoint AJAX `like-comment.php` để xử lý mượt mà.
  - Giao diện (UI): Thay thế chữ "Like" thô cứng thành **Biểu tượng Thumbs-up (👍)** hiện đại, tự động đổi sang màu xanh (Primary) khi người dùng đã thích, và hiển thị số lượng lượt thích.

- **Hệ thống Like Bài viết (Post Likes):**
  - Khởi tạo bảng dữ liệu mới cho lượt thích bài viết.
  - Cập nhật `PostController.php` và tạo endpoint AJAX `like-post.php`.
  - Bổ sung nút **Like** to và rõ ràng vào phần dưới của giao diện chi tiết bài viết, lưu giữ lượt thích vĩnh viễn theo tài khoản.

- **Chức năng Chia sẻ (Share):**
  - Tinh gọn menu xổ xuống của nút Chia sẻ, chỉ giữ lại tùy chọn **"Sao chép liên kết"**.
  - Tích hợp JavaScript (Clipboard API) để khi nhấn vào nút này, đường dẫn bài viết hiện tại sẽ tự động được sao chép vào bộ nhớ đệm của người dùng và hiển thị thông báo "Đã sao chép!".

## 2. Nâng cấp Thiết kế & UI/UX (Premium Glassmorphism)

- **Trang Danh sách Bài viết (`posts-list.php`):**
  - Lột xác hoàn toàn giao diện danh sách bài viết sang phong cách **Kính mờ cao cấp (Premium Glassmorphism)**.
  - **Thanh điều khiển (Header Bar):** Loại bỏ hoàn toàn hình nền tối thô cứng, làm cho cụm nút "Hiển thị bài viết của tôi" và "Tạo bài viết" lơ lửng, hoàn toàn trong suốt trên nền ảnh phong cảnh. Nút "Tạo bài viết" được trang bị dải màu Gradient rực rỡ và hiệu ứng phóng to, phát sáng khi trỏ chuột.
  - **Thẻ Bài viết (Post Cards):** Bỏ nền trắng và viền đen cũ. Thay bằng nền tối mờ (backdrop-filter blur), chữ trắng sang trọng, các góc bo tròn mềm mại (20px). Ảnh bìa bài viết ép tỷ lệ chuẩn (`object-fit: cover`) tránh bị méo và có hiệu ứng phóng to (zoom in) mượt mà khi di chuột vào.

- **Trang Chi tiết Bài viết (`post-detail.php`):**
  - Sửa lỗi khoảng cách (padding) bị sát viền ở bình luận đầu tiên bằng cách đưa thuộc tính `p-4` ra ngoài thẻ cha (`#commentsWrapper`), giúp khung bình luận luôn cân đối kể cả khi vừa được tải lên bằng JavaScript AJAX.

## 3. Khắc phục Sự cố & Sửa lỗi (Bug Fixes)

- **Lỗi 404 Không tìm thấy Hình ảnh/CSS (Hardcoded Path Issue):**
  - Phát hiện và quét sạch toàn bộ các đường dẫn tĩnh cũ (Ví dụ: `/animal_php/images/...` và `/animal_php/css/...`) trong mã nguồn của `post-detail.php` và `posts-list.php`.
  - Thay thế đồng loạt bằng biến động hệ thống `<?= $base ?>` để tương thích hoàn toàn với cấu trúc thư mục mới (`animal_php_myadmin/animal_php_myadmin`), sửa dứt điểm lỗi Avatar không hiển thị (nekoparalogo.png).

- **Dọn dẹp thư mục `prompt/` & Lỗi Trắng Trang (Fatal Error):**
  - Dọn dẹp hoàn toàn thư mục `prompt`, chỉ giữ lại các file tài liệu Markdown (`.md`).
  - Khắc phục sự cố trắng trang do ghi đè nhầm file backup cũ từ thư mục `prompt` bằng cách sử dụng **Git Restore** khôi phục nguyên bản 5 file hệ thống (như `view/home/index.php`, `view/header.php`, `css/mystyle.css`...). Giao diện kép và lỗi thiếu file cấu hình `env.php` đã được xử lý triệt để.
