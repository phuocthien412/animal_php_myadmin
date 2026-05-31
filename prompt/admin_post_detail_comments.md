# Chi Tiết Bài Viết Admin Và Bình Luận

## Mục tiêu
- Làm trang chi tiết bài viết trong admin dễ theo dõi hơn.
- Thêm khu vực quản lý bình luận ngay bên dưới nội dung bài viết.

## Nội dung đã chỉnh
- Map đúng route `admin/posts/detail/{id}` về file chi tiết bài viết admin.
- Hiển thị danh sách bình luận ngay trong trang chi tiết.
- Chia bình luận thành 2 cột trên màn hình rộng để dễ nhìn hơn.
- Thêm các action cho bình luận:
  - Thêm bình luận mới.
  - Ẩn bình luận.
  - Bỏ ẩn bình luận.
  - Xoá bình luận.
  - Xoá tất cả bình luận của cùng một tài khoản.
- Tạo file handler POST riêng để xử lý các action này.

## Kết quả
- Admin có thể thao tác bình luận trực tiếp trong trang chi tiết.
- Phần nhìn được gọn hơn và dễ thao tác hơn.
