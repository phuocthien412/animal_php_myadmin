# Tổng Hợp Thay Đổi Hệ Thống Đa Ngôn Ngữ (Bilingual Support i18n)

Tài liệu này tổng hợp toàn bộ các thay đổi được thực hiện để hoàn thành việc dịch song ngữ (Tiếng Việt & Tiếng Anh) cho 5 trang/đường dẫn cuối cùng theo yêu cầu của bạn.

---

## 1. Tổng Quan Các Tệp Thay Đổi

Hệ thống đa ngôn ngữ được xây dựng tập trung dựa trên cơ chế session `$_SESSION['lang']` và hàm trợ giúp `__($key)`. Các thay đổi lần này bao gồm:

*   **Tệp cấu hình ngôn ngữ (Từ điển)**:
    *   [vi.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/config/lang/vi.php) - Bổ sung các cụm từ Tiếng Việt.
    *   [en.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/config/lang/en.php) - Bổ sung các cụm từ Tiếng Anh tương ứng.
*   **Các View phía Client (Giao diện người dùng)**:
    *   [FindAnimal.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/home/FindAnimal.php) - Trang Nhận Diện Động Vật bằng AI.
    *   [posts-list.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/post/posts-list.php) - Trang Cộng Đồng Bài Viết.
*   **Các View phía Admin (Bảng quản trị)**:
    *   [animal-admin.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/animals/animal-admin.php) - Trang Quản lý Động vật.
    *   [comment-admin.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/comments/comment-admin.php) - Trang Quản lý Bình luận.
    *   [notification-list.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/notifications/notification-list.php) - Trang Trung tâm Thông báo.

---

## 2. Từ Khoá Ngôn Ngữ Mới Thêm Vào Từ Điển

Các cặp khóa-giá trị sau đã được nối vào cuối tệp `config/lang/vi.php` và `config/lang/en.php`:

### Phân hệ Tìm kiếm Động vật bằng AI (`FindAnimal`)
*   `find_animal_title`: Tiêu đề chính của thẻ tải ảnh.
*   `find_animal_subtitle`: Hướng dẫn tải lên ảnh để AI nhận diện.
*   `find_animal_drag_drop`: Khu vực kéo thả tệp tin.
*   `find_animal_or`: Ký tự phân cách "Hoặc".
*   `find_animal_choose_file`: Nút chọn tệp từ máy tính.
*   `find_animal_choose_other`: Nút chọn ảnh khác.
*   `find_animal_detect_search`: Nút kích hoạt nhận diện AI.
*   `find_animal_alert_upload`: Thông báo khi chưa tải ảnh.
*   `find_animal_tour_step1` đến `step5`: Các bước hướng dẫn tương tác bằng tiếng Việt/tiếng Anh thuộc thư viện `intro.js`.

### Phân hệ Bài viết Cộng đồng (`Posts`)
*   `posts_hero_subtitle`: Khẩu hiệu truyền cảm hứng chia sẻ cộng đồng.
*   `posts_show_my_posts`: Lựa chọn hiển thị riêng bài viết cá nhân.
*   `posts_create_post`: Nút mở hộp thoại tạo bài viết.
*   `posts_new_post_modal_title`: Tiêu đề hộp thoại đăng bài mới.
*   `posts_post_title_label`: Nhãn nhập tiêu đề.
*   `posts_title_placeholder`: Gợi ý nhập tiêu đề.
*   `posts_upload_image_label`: Nhãn tải lên hình ảnh.
*   `posts_add_post_btn`: Nút gửi bài viết.
*   `posts_discuss_now`: Nút thảo luận nhanh trên thẻ bài viết.

### Phân hệ Quản trị Động vật (`admin/animals`)
*   `admin_animals_title`: Tiêu đề thẻ meta trang quản trị động vật.
*   `admin_animals_manage`: Tiêu đề chính phần quản trị.
*   `admin_animals_list`: Tiêu đề bảng danh sách động vật.
*   `admin_animals_total_desc`: Dòng mô tả đếm tổng số lượng động vật hiện có.
*   `admin_animals_search_placeholder`: Gợi ý tìm kiếm động vật.
*   `table_avatar`: Cột ảnh đại diện.
*   `table_animal_name`: Cột tên động vật.
*   `table_class`: Cột tên lớp phân loại.
*   `table_introduction`: Cột giới thiệu sơ lược.
*   `admin_animals_empty`: Trạng thái bảng trống.
*   `admin_animals_class_label`: Nhãn phân loại kèm ID lớp.
*   `action_view_details` / `action_edit` / `action_delete`: Các chú giải chức năng.
*   `confirm_delete_animal`: Cảnh báo hộp thoại khi xoá động vật.

### Phân hệ Quản trị Bình luận (`admin/comments`)
*   `admin_comments_title`: Tiêu đề thẻ meta quản lý bình luận.
*   `admin_comments_manage`: Tiêu đề chính trang quản lý.
*   `admin_comments_list`: Danh sách bình luận.
*   `admin_comments_total_desc`: Mô tả đếm số lượng bình luận.
*   `admin_comments_search_placeholder`: Gợi ý tìm kiếm theo nội dung bình luận.
*   `table_commenter`: Cột tên người bình luận.
*   `table_post_num`: Cột liên kết ID bài viết.
*   `table_content`: Cột chứa nội dung bình luận.
*   `table_time`: Cột hiển thị thời gian đăng.
*   `admin_comments_empty`: Trạng thái trống của bình luận.
*   `admin_comments_post_label`: Nhãn bài viết kèm ID.
*   `action_delete_comment`: Chú giải xoá bình luận.

### Phân hệ Trung tâm Thông báo (`admin/notifications`)
*   `admin_notifications_center`: Nhãn hiển thị trung tâm thông báo trong thanh breadcrumb.
*   `admin_notifications_live_feed`: Nhãn tiêu đề Dòng thời gian trực tiếp (Live feed).
*   `admin_notifications_subtitle`: Tiêu đề mô tả tính năng gom thông báo.
*   `admin_notifications_description`: Mô tả chi tiết hoạt động SSE tự động cập nhật thông báo gốc.
*   `admin_notifications_back_dash`: Nút dẫn trở lại bảng điều khiển (Dashboard).
*   `admin_notifications_list_title`: Tiêu đề danh sách thông báo.
*   `admin_notifications_recent_items`: Số mục thông báo gần nhất hiển thị.
*   `admin_notifications_showing_data`: Nhãn chỉ rõ đang kết xuất dữ liệu hiện có.
*   `admin_notifications_empty` / `empty_desc`: Trạng thái khi chưa phát sinh thông báo mới nào.
*   `notif_type_post` / `comment` / `animal` / `classanimal` / `user` / `role`: Các nhãn phân loại thông báo động hiển thị trên từng badge.

---

## 3. Chi Tiết Thay Đổi Tại Các View

Toàn bộ các chuỗi văn bản Tiếng Việt thô (hardcoded) trước đây đã được thay thế hoàn toàn bằng cú pháp PHP gọi hàm dịch:

```html
<!-- Ví dụ thay thế chuỗi tiêu đề động vật -->
- <h1 class="textclassanimalName">Động vật </h1>
+ <h1 class="textclassanimalName"><?= __('animal_title') ?></h1>
```

### Các điểm xử lý đặc biệt:
1.  **Dùng biến động (`sprintf`)**: Đối với các chuỗi hiển thị số lượng bản ghi biến động như "Tổng cộng X mục gần nhất", mã nguồn sử dụng:
    `<?= sprintf(__('admin_notifications_recent_items'), count($all_notifications)) ?>`
    Giúp cấu trúc câu từ của cả hai ngôn ngữ được sắp xếp chính xác.
2.  **Badge Phân loại thông báo động**: Loại thông báo được truyền động thông qua khóa ghép:
    `<?php echo htmlspecialchars(__('notif_type_' . $notificationType)); ?>`
    Đảm bảo khi phát sinh loại thông báo mới, badge của nó hiển thị đúng ngôn ngữ được chọn.
3.  **Hộp thoại xác nhận Javascript**: Để tránh xung đột ký tự nháy đơn trong JavaScript khi hiển thị hộp thoại cảnh báo:
    `onclick="return confirm('<?= htmlspecialchars(__('confirm_delete_comment'), ENT_QUOTES) ?>')"`

---

## 4. Cách thức Kiểm tra và Xác minh

1.  Truy cập vào ứng dụng tại [http://localhost/animal_php_myadmin/animal_php_myadmin/Home](http://localhost/animal_php_myadmin/animal_php_myadmin/Home).
2.  Tại thanh điều hướng trên cùng phía bên phải, click vào hộp chọn ngôn ngữ để chuyển sang **English** (hoặc **Tiếng Việt**).
3.  Truy cập tuần tự vào 5 đường dẫn sau để theo dõi sự thay đổi:
    *   Trang Nhận Diện Động Vật: `/FindAnimal`
    *   Trang Diễn Đàn Bài Viết: `/Posts`
    *   Trang Quản trị Động vật: `/admin/animals`
    *   Trang Quản trị Bình luận: `/admin/comments`
    *   Trang Quản trị Thông báo: `/admin/notifications/`
4.  Tại mỗi trang, tiến hành đổi qua lại hai ngôn ngữ để kiểm tra tính nhất quán của giao diện, các nút chức năng, gợi ý tìm kiếm, hộp thoại cảnh báo, và hướng dẫn trợ lý ảo.
