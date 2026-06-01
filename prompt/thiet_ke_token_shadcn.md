# Tích Hợp Phong Cách Thiết Kế Shadcn UI Vào Dự Án NEKOPARA

Ý tưởng tích hợp phong cách của **Shadcn UI** (được xây dựng trên nền tảng Tailwind CSS và bộ màu Radix) là một sự lựa chọn xuất sắc. Shadcn đại diện cho ngôn ngữ thiết kế "Anti-Slop" đỉnh cao: tối giản, tinh tế, sử dụng các dải màu trung tính (Neutral Colors), viền siêu mảnh, đổ bóng mịn (Soft Shadows) và các góc bo tròn đồng bộ.

Dưới đây là cẩm nang hướng dẫn cách tích hợp trực tiếp **Design Tokens của Shadcn UI** vào mã nguồn thuần HTML/CSS/Bootstrap hiện tại của dự án mà không cần cài đặt các thư viện React phức tạp.

---

## I. Khởi Tạo Hệ Thống CSS Variables (Design Tokens) Theo Shadcn UI

Chúng ta sẽ khai báo toàn bộ dải màu HSL (Hue, Saturation, Lightness) của Shadcn UI vào đầu file CSS chung (`css/mystyle.css` hoặc trong khối `<style>` chung):

```css
/* Tích hợp Design Tokens chuẩn Shadcn UI (Dòng Slate/Zinc) */
:root {
  --background: 0 0% 100%;
  --foreground: 224 71.4% 4.1%;
  
  --card: 0 0% 100%;
  --card-foreground: 224 71.4% 4.1%;
  
  --popover: 0 0% 100%;
  --popover-foreground: 224 71.4% 4.1%;
  
  --primary: 220.9 39.3% 11%;
  --primary-foreground: 210 20% 98%;
  
  --secondary: 220 14.3% 95.9%;
  --secondary-foreground: 220.9 39.3% 11%;
  
  --muted: 220 14.3% 95.9%;
  --muted-foreground: 220 8.9% 46.1%;
  
  --accent: 220 14.3% 95.9%;
  --accent-foreground: 220.9 39.3% 11%;
  
  --destructive: 0 84.2% 60.2%;
  --destructive-foreground: 210 20% 98%;
  
  --border: 220 13% 91%;
  --input: 220 13% 91%;
  --ring: 224 71.4% 4.1%;
  
  --radius: 0.5rem; /* 8px bo tròn tiêu chuẩn */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
}

/* Chế độ Dark Mode tương ứng của Shadcn UI */
.dark-theme {
  --background: 224 71.4% 4.1%;
  --foreground: 210 20% 98%;
  
  --card: 224 71.4% 4.1%;
  --card-foreground: 210 20% 98%;
  
  --popover: 224 71.4% 4.1%;
  --popover-foreground: 210 20% 98%;
  
  --primary: 210 20% 98%;
  --primary-foreground: 220.9 39.3% 11%;
  
  --secondary: 215 27.9% 16.9%;
  --secondary-foreground: 210 20% 98%;
  
  --muted: 215 27.9% 16.9%;
  --muted-foreground: 217.9 10.6% 64.9%;
  
  --accent: 215 27.9% 16.9%;
  --accent-foreground: 210 20% 98%;
  
  --border: 215 27.9% 16.9%;
  --input: 215 27.9% 16.9%;
  --ring: 216 34% 17%;
}
```

---

## II. Cách Áp Dụng Thực Tế Vào Mã Nguồn Giao Diện

Khi đã khai báo các biến số trên, ta ánh xạ trực tiếp chúng vào các phần tử giao diện chính để đạt được diện mạo tối giản, sang trọng:

### 1. Nút bấm phong cách Shadcn UI (`Button Component`)
Nút của Shadcn có thiết kế phẳng tinh tế, viền mảnh sắc nét, phản hồi hover mượt mà:

```css
.shadcn-btn-primary {
  background-color: hsl(var(--primary));
  color: hsl(var(--primary-foreground));
  border: 1px solid transparent;
  border-radius: var(--radius);
  padding: 8px 16px;
  font-weight: 500;
  font-size: 14px;
  box-shadow: var(--shadow-sm);
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.shadcn-btn-primary:hover {
  background-color: hsl(var(--primary) / 0.9);
  transform: translateY(-1px);
}

.shadcn-btn-outline {
  background-color: transparent;
  color: hsl(var(--foreground));
  border: 1px solid hsl(var(--border));
  border-radius: var(--radius);
  padding: 8px 16px;
  font-weight: 500;
  font-size: 14px;
  transition: all 0.2s ease;
}

.shadcn-btn-outline:hover {
  background-color: hsl(var(--accent));
  color: hsl(var(--accent-foreground));
}
```

### 2. Hộp Nhập Liệu Tinh Tế (`Input Component`)
Hộp nhập liệu Shadcn sử dụng viền mờ trung tính và hiển thị vòng phát sáng mỏng (`ring`) khi được focus vào:

```css
.shadcn-input {
  width: 100%;
  background-color: transparent;
  color: hsl(var(--foreground));
  border: 1px solid hsl(var(--border));
  border-radius: var(--radius);
  padding: 10px 14px;
  font-size: 14.5px;
  outline: none;
  transition: all 0.2s ease-in-out;
}

.shadcn-input:focus {
  border-color: hsl(var(--ring));
  box-shadow: 0 0 0 2px hsl(var(--ring) / 0.15);
}

.shadcn-input::placeholder {
  color: hsl(var(--muted-foreground));
}
```

### 3. Thẻ Card Cao Cấp (`Card Component`)
Card phong cách Shadcn rũ bỏ toàn bộ thiết kế viền đen thô cứng, thay vào đó là viền xám nhạt siêu mảnh và đổ bóng cực mịn:

```css
.shadcn-card {
  background-color: hsl(var(--card));
  color: hsl(var(--card-foreground));
  border: 1px solid hsl(var(--border));
  border-radius: var(--radius);
  box-shadow: var(--shadow-sm);
  padding: 24px;
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.shadcn-card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.shadcn-card-title {
  font-size: 18px;
  font-weight: 600;
  line-height: 1.2;
  margin-bottom: 8px;
  letter-spacing: -0.02em;
}

.shadcn-card-description {
  font-size: 14px;
  color: hsl(var(--muted-foreground));
}
```

---

## III. Các Bước Tích Hợp Dễ Dàng Vào Dự Án Hiện Tại

1.  **Bước 1**: Nhúng đoạn khai báo `:root` ở phần **I** vào đầu file [mystyle.css](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/css/mystyle.css).
2.  **Bước 2**: Thay thế các class Bootstrap truyền thống thành class phong cách Shadcn UI tại các vị trí cần nâng cấp (Ví dụ: Đổi class `btn btn-primary` thành `shadcn-btn-primary`, đổi class `card shadow-lg` thành `shadcn-card`).
3.  **Bước 3**: Đồng bộ hóa dải bo góc bằng cách áp dụng biến `--radius` cho toàn bộ ảnh đại diện, khung viền để đảm bảo giao diện đồng nhất tuyệt đối về mặt hình khối.

Tài liệu này đã được lưu trữ trong thư mục `@[prompt]` của bạn tại:
*   [thiet_ke_token_shadcn.md](file:///c:/laragon/www/animal_php_myadmin/animal_php_myadmin/prompt/thiet_ke_token_shadcn.md)
