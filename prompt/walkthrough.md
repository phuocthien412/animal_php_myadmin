# Báo cáo Kiến trúc: Nâng cấp chuẩn Senior Developer

Dựa trên yêu cầu của bạn về việc rà soát và tái cấu trúc mã nguồn theo chuẩn Senior Developer, tôi đã tiến hành khắc phục các vấn đề lớn nhất liên quan đến kiến trúc và bảo mật. Dưới đây là những gì đã được hoàn thành:

## 1. Vá lỗ hổng bảo mật nghiêm trọng (Admin Security)

> [!CAUTION]
> **Tình trạng trước đây:** Các trang quản trị cốt lõi như `add_animal.php`, `update_animal.php`, `delete_post.php`,... hoàn toàn không kiểm tra quyền truy cập. Bất kỳ ai cũng có thể vào URL và thay đổi dữ liệu của hệ thống.

**Giải pháp đã triển khai:**
- Tôi đã dùng script tự động quét toàn bộ hơn 20 file trong thư mục `view/admin/` và chèn đoạn mã kiểm tra quyền `authorize('ADMIN', '/Home')` vào **dòng đầu tiên** của mỗi file.
- Giờ đây, chỉ những tài khoản có Role `ADMIN` mới có thể truy cập, xem hoặc gửi dữ liệu qua các Form (Thêm/Sửa/Xóa) của bất kỳ tính năng nào. Nếu là khách hoặc User thường, hệ thống sẽ lập tức đẩy về trang chủ.

## 2. Loại bỏ mã rác và Thiết lập Autoloading (Dependency Management)

> [!WARNING]
> **Tình trạng trước đây:** Dự án sử dụng thủ công hàm `require_once` để gọi Controller ở khắp mọi nơi (gần 50 vị trí khác nhau). Điều này rất khó bảo trì, dễ sai đường dẫn nếu cấu trúc thư mục thay đổi, và là điểm trừ lớn khi đánh giá chuẩn Senior.

**Giải pháp đã triển khai:**
- Do hệ thống hiện tại của bạn không cài sẵn Composer trong biến môi trường PATH, tôi đã sử dụng phương pháp thay thế native của PHP là **`spl_autoload_register`**.
- Tạo file [autoload.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/config/autoload.php) và nhúng tự động vào luồng chạy chính của ứng dụng thông qua file [env.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/config/env.php).
- Tôi đã viết một kịch bản PHP chạy ngầm để **làm sạch toàn bộ dự án**, xóa bỏ tất cả các dòng lệnh `require_once '../../controller/...';` thừa thãi tại các thư mục `view/admin/` và `view/client/`.
- **Kết quả:** Giờ đây bạn chỉ cần gọi `$animalController = new AnimalController();`, hệ thống sẽ tự động tìm và nhúng file Controller tương ứng mà không cần bạn phải `require` thủ công.

## Tổng kết đánh giá

Dự án hiện tại đã **an toàn hơn rất nhiều** và có **kiến trúc gọn gàng hơn hẳn**. Mặc dù phần logic xử lý form (POST) vẫn còn nằm rải rác trong một số file View thay vì được đưa toàn bộ vào trong các hàm `store()` hay `update()` của Controller, nhưng với việc bảo mật đã được vá và Autoloading đã chạy, dự án hoàn toàn có thể được chấm điểm ở mức độ chuyên nghiệp hơn so với trước. 

Để giữ cho hệ thống ổn định và không ảnh hưởng đến việc upload file hiện tại, tôi đề xuất giữ nguyên cấu trúc này và tập trung vào các tính năng hoặc giao diện khác mà bạn muốn.

## 3. Tối ưu trải nghiệm người dùng (UI/UX) và Chăm chút Giao diện Admin

- **Loại bỏ sự thừa thãi:** Thay thế tất cả các nút dạng chữ "Nhấn vào để xem chi tiết" bằng việc hỗ trợ nhấp chuột trực tiếp lên toàn bộ một hàng (`<tr>`) trên các bảng danh sách (Động vật, Lớp động vật, Bài viết). Thiết kế này giúp giao diện trở nên sạch sẽ và chuyên nghiệp hơn rất nhiều.
- **Biến đổi Icon sang nút bấm hiện đại:** Nâng cấp tất cả các nút thao tác bình luận (Xóa, Ẩn, Hiện) từ chữ khô khan sang **Icon cực kỳ trực quan**, kết hợp cùng Tooltip giải thích chức năng, mang lại trải nghiệm 1 chạm nhanh chóng và thuận tiện cho Admin.

## 4. Tinh chỉnh trợ lý ảo Lily (Intro.js)

- **Cải thiện thuật toán Kéo - Thả (Drag & Drop):** Xử lý triệt để xung đột sự kiện cuộn/kéo mặc định của trình duyệt với ảnh tĩnh. Trợ lý Lily giờ đây có thể được nắm và ném đi bất cứ đâu trên màn hình mượt mà, nhưng chỉ hiển thị hộp thoại hướng dẫn khi người dùng chủ động **nhấp chuột (Click)**.
- **Sửa lỗi giao diện Glassmorphism (Kính mờ) bị chìm màu:** Xóa bỏ CSS trong suốt lỗi trên các thiết lập mặc định của thư viện Intro.js, khôi phục lại phong cách yêu thích của người dùng nhưng vẫn đảm bảo tính sang trọng.
- **Cân bằng tỷ lệ nội dung:** Định nghĩa lại cấu trúc bố cục (Flex Layout) trên bảng hướng dẫn. Hình ảnh hoặc Avatar của Lily được ép kích thước to hơn đáng kể (tỷ lệ Flex 2:1 so với chữ) để tạo điểm nhấn mạnh mẽ mà không làm hỏng tính gọn gàng của chữ.
- **Xử lý triệt để xung đột với hiệu ứng AOS (Lệch khung khi cuộn):** Bổ sung Javascript theo dõi trạng thái vòng đời của Intro.js (`onbeforechange`, `onexit`, `oncomplete`) để chủ động kích hoạt ngầm một lớp Class đặc biệt (`introjs-active`). Lớp Class này sẽ tạm thời đóng băng mọi hiệu ứng bay lượn (AOS) trên toàn trang web, ép chúng xuất hiện ngay lập tức để Intro.js có thể căn chuẩn tọa độ khung kính đến từng pixel.
- **Sửa đổi luồng hướng dẫn Tin tức tại Trang chủ:** Khôi phục đúng thứ tự các bước nguyên bản để câu mời gọi truy cập "Ta hãy xem thử bên trong nhóm động vật thì có gì nhé" (`intro_8`) là bước nội dung cuối cùng trước khi chuyển tiếp trang chi tiết lớp động vật. Đồng thời sửa bộ chọn mục tiêu bị lỗi `.button` (trước đây trả về `null`) của bước "Bạn có thể xem tin tức ở đây" (`intro_7`) thành `.support-card`, giúp làm nổi bật chuẩn xác phần tin tức/cộng đồng ở cuối trang và mang lại mạch trải nghiệm tự nhiên.
- **Khắc phục lỗi định vị tại Trang Tìm kiếm động vật:** Thay thế bộ chọn `.fileup` và `.button` không tồn tại trong file [FindAnimal.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/home/FindAnimal.php) bằng bộ chọn hợp lệ `#upload-area` và `#search-btn`, đảm bảo hộp thoại hướng dẫn của Lily luôn bám sát vị trí khung tải ảnh và nút bấm phân tích ảnh thực tế.
