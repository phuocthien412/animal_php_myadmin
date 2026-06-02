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

## 6. Tổng Hợp Thay Đổi - Cố Định Giao Diện Admin & Refactor (Mới nhất)

### Các thay đổi đã thực hiện
- **Sửa lỗi CSS Modal Xác nhận:**
  - Sửa lỗi modal xác nhận (Confirm Modal) luôn tự động hiển thị khi vừa load trang do CSS `.confirm-modal { display: flex; }` ghi đè thuộc tính `hidden` của HTML.
  - Chỉnh lại CSS sử dụng `.confirm-modal { display: none; }` và `.confirm-modal.is-open { display: flex; }` kết hợp với class JS để toggle bật/tắt chính xác.

- **Sửa lỗi mất định dạng CSS toàn bộ trang:**
  - **Nguyên nhân:** Các trang `add_animal.php`, `update_animal.php` và `update_classanimal.php` khai báo trùng lặp khung HTML (`<!DOCTYPE html><html><head><body>`) trong khi đã include `headerAdmin.php` (file này cũng chứa bộ khung y hệt). Việc này khiến trình duyệt loại bỏ thẻ `<head>` chứa CSS.
  - **Khắc phục:** Xóa bỏ bộ khung HTML dư thừa ở đầu và thẻ đóng `</body></html>` ở cuối trong các file này.

- **Refactor (Tối ưu code) Component Validate:**
  - Gom toàn bộ logic kiểm tra dung lượng file (tối đa 10MB) rải rác trong các file Controller/View vào một Component dùng chung tại `view/admin/components/file_validator.php`.
  - Tích hợp sử dụng trong `add_animal.php`, `update_animal.php`, `update_classanimal.php` và `profile.php`.
  - Khắc phục lỗi mất ảnh hiện tại khi người dùng cập nhật thông tin chữ (do sai lệch tên biến `$_POST` với thẻ `<input type="hidden">` sinh ra từ `file_uploader.php`).

- **Đa ngôn ngữ (i18n):**
  - Tích hợp hàm dịch `__()` vào trong thông báo lỗi của `file_validator.php`.
  - Bổ sung 2 biến dịch `msg_files_size_exceeded` và `msg_file_size_exceeded` vào cả hai file cấu hình ngôn ngữ `config/lang/vi.php` và `config/lang/en.php`.

## 7. Tổng Hợp Thay Đổi - Nâng Cấp Hệ Thống Thông Báo (Shadcn UI Toast)

### Các thay đổi đã thực hiện
- **Gỡ bỏ hệ thống Alert tĩnh lỗi thời:**
  - Xóa bỏ các khối `<div class="alert-admin success/danger">` tĩnh được nhúng thủ công và tốn diện tích ở đầu 7 trang admin (`animal-admin.php`, `classanimal-admin.php`, `user-admin.php`, `post-admin.php`, `comment-admin.php`, `dashboard.php`, `profile.php`).

- **Tích hợp Global Toast chuẩn Shadcn UI (`view/footerAdmin.php`):**
  - Khai báo bộ khung HTML Toast vào vị trí cố định ở góc trên bên phải (Top-Right) để tránh đè các thao tác dưới đáy trang.
  - Viết hệ thống CSS chuẩn Shadcn UI cho Toast bao gồm: bóng đổ (box-shadow), bo góc, hiệu ứng trượt vào (`slideInToast`), mờ dần (`fadeOutToast`), và thanh thời gian đếm ngược (progress bar).
  - Viết hàm Javascript toàn cục `window.showToast(message, type)` để gọi thông báo ở mọi nơi nếu cần thiết. Hàm sẽ kích hoạt hiệu ứng thanh chạy trong 4 giây rồi tự xóa DOM của Toast để giải phóng bộ nhớ.

- **Cơ chế Auto-trigger (Tự động bắt thông báo) & Fix Bug Lặp Lại:**
  - Lập trình PHP quét thẳng vào các biến `$_GET['success']`, `$_GET['error']`, `$_SESSION['success']` và `$_SESSION['error']`. Nếu phát hiện có thông báo, PHP sẽ tự sinh ra đoạn script chèn vào trang để tự động gọi `window.showToast`.
  - Bổ sung logic JavaScript (`window.history.replaceState`) dùng để tự động xóa sạch các tham số `?success=...` và `?error=...` khỏi thanh địa chỉ URL sau khi Toast hiện lên. Nhờ đó, người dùng khi bấm tải lại trang (F5) sẽ không bị dội lại các thông báo cũ một cách khó chịu.

## 8. Tổng Hợp Thay Đổi - Hoàn thiện tính năng Quản lý Lớp Động Vật & Đa Ngôn Ngữ

### Các thay đổi đã thực hiện
- **Đa ngôn ngữ (i18n) cho tính năng Thêm/Sửa Động vật:**
  - Bổ sung thêm các khóa (keys) vào file `config/lang/vi.php` và `config/lang/en.php`.
  - Thay thế toàn bộ chữ cứng (hardcode) bằng lệnh `__()` trong file `add_animal.php` và `update_animal.php`.

- **Hoàn thiện tính năng Thêm Lớp Động vật (ClassAnimal):**
  - Cập nhật file `.htaccess` định tuyến đường dẫn `admin/classanimals/add`.
  - Khởi tạo file `add_classanimal.php` tuân thủ đúng kiến trúc hiện hành và tích hợp module Upload ảnh/video chung.
  - Bổ sung nút "+ Thêm lớp động vật" cho trang danh sách `classanimal-admin.php`.

- **Hoàn thiện tính năng Xóa Lớp Động vật (ClassAnimal):**
  - Cập nhật tệp `.htaccess` định tuyến đường dẫn `admin/classanimals/delete/id`.
  - Tạo trang `delete.php` ở `view/admin/classanimals/` để xử lý logic xóa. Đã bắt lỗi chặt chẽ, nếu Lớp đang có chứa động vật thì sẽ ngăn chặn hành vi xóa và hiển thị thông báo lỗi bằng Toast.
  - Bổ sung nút "Xóa" tích hợp hệ thống xác nhận Shadcn Modal.

## 9. Tổng Hợp Thay Đổi - Khắc phục lỗi trang Thông báo và Đa ngôn ngữ động nhật ký
- **Sửa lỗi hiển thị trang Trung tâm thông báo trống:**
  - Nạp trực tiếp model [Notification.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/model/Notification.php) trong tệp [notification-list.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/notifications/notification-list.php).
  - Tự động nạp dữ liệu bằng cách gọi hàm `Notification::getAll(100)` nếu danh sách rỗng để tránh trường hợp trang trống khi được điều hướng qua RewriteRule.
  - Tự động đánh dấu tất cả thông báo là đã đọc bằng cách gọi `Notification::markAllAsRead()` ngay khi Admin truy cập trang, giúp cập nhật lại chính xác số lượng thông báo chưa đọc trên menu trên cùng.
- **Đa ngôn ngữ hóa (i18n) động cho các bản ghi từ Controller & Bình luận ẩn:**
  - Nâng cấp hàm dịch `__()` trong tệp [i18n.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/lib/i18n.php), bổ sung từ điển dịch thuật tĩnh kết hợp regex pattern matching để tự động chuyển ngữ các nội dung nhật ký tiếng Việt động lưu trữ trong cơ sở dữ liệu (ví dụ: *Vừa thêm loài "Sư tử"* -> *Added species "Sư tử"*) sang tiếng Anh khi chọn ngôn ngữ `'en'`.
  - Bao bọc toàn bộ đầu ra tiêu đề, nội dung và hành động thông báo trong `__()` tại [headerAdmin.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/headerAdmin.php) và [notification-list.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/notifications/notification-list.php).
  - Bao bọc hiển thị nội dung bình luận `chat_data` trong `__()` ở cả phía Client ([post-detail.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/client/posts/post-detail.php), [fetch-comments.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/client/posts/fetch-comments.php)) và Admin ([comment-admin.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/posts/comment-admin.php), [view_post.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/posts/view_post.php)) để tự động dịch cụm từ ẩn comment `[Đã ẩn bởi quản trị]` thành `[Hidden by admin]`.

## 10. Tổng Hợp Thay Đổi - Hiển thị ràng buộc và Vô hiệu hóa Xóa Lớp động vật
- **Cải tiến danh sách Lớp động vật ở Admin:**
  - Viết thêm hàm đếm số lượng loài `getAnimalsCountByClassId($id)` trong [ClassAnimalController.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/controller/ClassAnimalController.php).
  - Thêm cột **"Số lượng loài" (Animal Count)** vào bảng danh sách lớp động vật tại [classanimal-admin.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/classanimals/classanimal-admin.php), hiển thị số lượng loài hiện thuộc từng lớp bằng nhãn Badge trực quan.
  - Vô hiệu hóa nút **Xóa (Delete)** (đặt `opacity: 0.5`, con trỏ chuột `not-allowed`) đối với các lớp động vật có số lượng loài liên kết > 0.
  - Tích hợp sự kiện JavaScript trên nút xóa bị vô hiệu hóa, khi bấm sẽ hiển thị thông báo Toast cảnh báo lỗi màu đỏ ngay lập tức: *"Không thể xóa lớp động vật này vì đang chứa động vật!"* thay vì hiển thị modal xác nhận xóa như thông thường.
  - Duy trì kiểm tra an toàn ở backend tại [delete.php](file:///C:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/classanimals/delete.php) để ngăn chặn truy cập xóa trực tiếp qua URL.

