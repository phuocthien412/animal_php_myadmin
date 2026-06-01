# Tổng Hợp Các Chỉnh Sửa Giao Diện

Dưới đây là danh sách toàn bộ các file đã được chỉnh sửa để khắc phục lỗi giao diện, lỗi responsive và lỗi khoảng cách:

## 1. File CSS Chính (`css/mystyle.css`)
- **Fix lỗi lệch ảnh bìa**: Chỉnh sửa `.explore .card .front img` và `.explore .card .back img` từ `object-fit: cover;` thành `object-fit: contain;` hoặc `max-height` để ảnh không bị tràn và lệch.
- **Thêm tính năng Responsive cho Card**: Sửa width của các card thành `width: 100%; max-width: 280px;` thay vì fix cứng `240px` để không bị đè lên nhau trên điện thoại.
- **Fix khoảng cách nút (Button)**: Chỉnh `margin: 200px auto;` thành `margin: 20px auto;` cho `.button` để khắc phục lỗi cột chứa nút "Community!" bị kéo dài khổng lồ.
- **Tối ưu hóa các Phân khu (Sections)**: Xóa các padding khổng lồ (`padding: 10% 0`) và thêm `min-height: 100vh; display: flex; align-items: center;` cho các section (`.home`, `.about`, `.explore`, `.support`, v.v.) để biến chúng thành các khung hình tràn màn hình hoàn hảo. Khi cuộn chuột hoặc bấm anchor link, trang sẽ tự động snap và căn giữa nội dung một cách chuyên nghiệp.

## 2. File Trang Chủ (`view/home/index.php`)
- Cấu trúc lại lưới Bootstrap (thay thế các class cũ bằng `d-flex`, `justify-content-center`, `align-items-center`).
- Thay đổi `mb-5` và `fs-4` thành các chỉ số hợp lý hơn để tránh việc chữ quá to và khoảng cách giữa các hàng quá xa.
- Giữ nguyên cấu trúc Row 1 (Logo + Text 1) và Row 2 (Text 2 + Video) nhưng đưa vào trong các container được căn giữa theo chiều dọc.

## 3. Các File Danh Sách (`view/classanimal/list_classanimals.php` & `view/animal/animals-list.php`)
- Đồng bộ hiệu ứng thẻ bài (Flip Card): Sửa tên lớp `.container` thành `.flip-container` để không bị xung đột với lưới của Bootstrap.
- Xóa các inline-style (style cứng trong HTML) gây vỡ bố cục trên mobile.
- Thêm cơ chế ép tải lại CSS (Cache Buster) `?v=<?= time() ?>` để trình duyệt của người dùng luôn cập nhật thiết kế mới nhất.

## 4. File Header (`view/header.php`)
- Thay đổi link CSS thành: `<link rel="stylesheet" href="<?= $base ?>/css/mystyle.css?v=<?= time() ?>" />` để triệt tiêu hoàn toàn lỗi kẹt bộ nhớ đệm (cache) trên trình duyệt client.

## 5. Tổng Hợp Thay Đổi Ngày Hôm Nay - Thông Báo Admin

### Mục tiêu
- Sửa lại thanh thông báo trên header admin.
- Tạo trang notification riêng cho admin.
- Bỏ realtime/SSE tạm thời vì gây lag, sau đó chuyển header sang kiểu hiển thị gọn hơn.

### Các thay đổi đã thực hiện
- **Sửa route thông báo:**
	- Tạo route sạch `/admin/notifications/` bằng file `admin/notifications/index.php`.
	- Giữ SSE endpoint cũ ở `admin/notifications.php` để phục vụ dữ liệu nếu cần.

- **Sửa lỗi include path:**
	- Fix lại đường dẫn `require_once` trong `admin/notifications.php` cho đúng cấp thư mục.
	- Fix lại include `headerAdmin.php` và `footerAdmin.php` trong `view/admin/notifications/notification-list.php`.

- **Tối ưu header notification:**
	- Đổi dropdown header sang hiển thị theo kiểu action ngắn gọn, không còn preview nội dung bài viết/bình luận.
	- Chỉ giữ các action như “Đã tạo bài viết”, “Đã thêm bình luận” và thời gian.
	- Cải thiện giao diện dropdown theo kiểu feed rõ ràng hơn, icon tròn bên trái, nội dung xếp dọc, panel rộng và thoáng hơn.

- **Tạm tắt realtime:**
	- Bỏ `EventSource` trên trang notification để tránh load lặp và đỡ lag.
	- Trang notification giờ hiển thị dữ liệu hiện có, không tự chèn thêm item realtime.

- **Fix lỗi dữ liệu comment:**
	- Đổi key comment từ `id_comment` sang fallback an toàn `id_cmt ?? id_comment` để tránh warning.

### Kết quả
- Header notification không còn hiển thị chữ dính nhau kiểu cũ.
- Trang notification mở đúng route và không còn lỗi include.
- Giao diện thông báo gọn hơn, tập trung vào action thay vì nội dung dài.

### Ghi chú
- Nếu sau này cần bật realtime lại, nên làm theo hướng cache/cursor để chỉ gửi phần thay đổi thay vì polling toàn bộ dữ liệu liên tục.
