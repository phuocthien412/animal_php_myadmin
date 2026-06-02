# Kế hoạch triển khai nâng cấp giao diện tải tệp tin và giới hạn dung lượng 10MB

Tài liệu này chi tiết hóa kế hoạch nâng cấp các trường tải lên tệp tin (Choose Files) trong khu vực Admin của dự án Nekopara sang phong cách **Shadcn/Radix UI** hiện đại, kết hợp hỗ trợ xem ảnh trực quan và ràng buộc giới hạn dung lượng **10MB** ở cả phía máy khách (Client) và máy chủ (Server).

## User Review Required

> [!IMPORTANT]
> - **Giao diện hiện đại (Shadcn-style Upload Zones):** Các input dạng tệp tin mặc định của trình duyệt ("Choose File") sẽ được ẩn đi. Thay thế bằng các phân vùng kéo thả (Drag-and-Drop Zones) được bo góc mềm mại, viền nét đứt thanh lịch, tích hợp biểu tượng tương ứng và hỗ trợ kéo thả tệp.
> - **Xem trước tệp đa phương tiện trực quan (Interactive Previews):** Khi người dùng chọn hình ảnh hoặc video, hệ thống lập tức hiển thị một thẻ xem trước (Preview Card) dạng ô vuông bo góc, có nút xóa nhanh góc trên và thanh tên tệp mờ ở dưới, loại bỏ hoàn toàn hiển thị chữ thô kệch mặc định.
> - **Giới hạn 10MB:** Ràng buộc chặt chẽ 10MB ở cả JS (ngăn chặn sớm và thông báo trực quan qua Alert) và PHP (bảo vệ tuyệt đối ở server qua thuộc tính `size` của `$_FILES`).

## Proposed Changes

### 1. Thành phần Style chung (Admin CSS)
#### [MODIFY] [admin.css](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/css/admin.css)
- Đã được cập nhật thành công ở bước trước, chứa toàn bộ các lớp thiết kế `.shadcn-upload-zone`, `.upload-preview-container`, `.upload-preview-card`, `.remove-btn`, và `.file-name`.

### 2. Form thêm mới động vật
#### [MODIFY] [add_animal.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/animals/add_animal.php)
- **Server-side (PHP):** Bổ sung đoạn kiểm tra dung lượng `$_FILES['...']['size'] > 10MB` ở ngay đầu POST handler và báo lỗi bằng tiếng Việt chuyên nghiệp.
- **Client-side (HTML/JS):** Thay thế 4 trường tải ảnh bằng cấu trúc `.shadcn-upload-zone` hiện đại, cập nhật hàm JavaScript kiểm tra kích thước sớm và kết xuất hình ảnh/video xem trước động.

### 3. Form chỉnh sửa động vật
#### [MODIFY] [update_animal.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/animals/update_animal.php)
- **Server-side (PHP):** Tích hợp kiểm tra 10MB cho tệp đại diện mới, ảnh nơi sinh sống mới, mã QR mới và các ảnh bộ sưu tập mới.
- **Client-side (HTML/JS):** Nâng cấp giao diện tải tệp tương tự và nhúng hàm `validateAndPreviewFile` để xử lý kiểm tra sớm.

### 4. Form chỉnh sửa lớp động vật
#### [MODIFY] [update_classanimal.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/classanimals/update_classanimal.php)
- **Server-side (PHP):** Thêm ràng buộc 10MB đối với video/ảnh nền lớp động vật.
- **Client-side (HTML/JS):** Cấu hình phân vùng kéo thả và hỗ trợ xem trước hình ảnh hoặc video động.

### 5. Form hồ sơ cá nhân Admin
#### [MODIFY] [profile.php](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/view/admin/profile.php)
- **Server-side (PHP):** Thêm kiểm tra 10MB đối với ảnh đại diện.
- **Client-side (HTML/JS):** Áp dụng thiết kế kéo thả ảnh đại diện cực kỳ mượt mà.

---

## Verification Plan

### Kiểm thử tự động & thủ công
1. **Kiểm thử kích thước file nhỏ (< 10MB):** Đảm bảo ảnh tải lên thành công, hiển thị xem trước mượt mà, lưu vào CSDL và đĩa với tên tệp chuẩn hóa an toàn.
2. **Kiểm thử kích thước file lớn (> 10MB):**
   - Thử chọn một file ảnh có dung lượng lớn hơn 10MB: Phải hiển thị cảnh báo ngay lập tức trên màn hình bằng tiếng Việt và làm sạch (reset) input.
   - Trường hợp vượt qua client: Thử bypass client để submit lên server -> Server PHP phải bắt được và chặn yêu cầu, không cho phép lưu file lớn vào đĩa.
