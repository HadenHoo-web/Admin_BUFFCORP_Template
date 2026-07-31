# Đối chiếu giao diện demo và source local

Ngày kiểm tra: 31/07/2026

## Kết quả

- File demo có 65 mục giao diện, tương ứng 59 module PHP duy nhất.
- Source local có đủ 59/59 module PHP được file demo sử dụng.
- Không có module nào trong file demo bị thiếu source.
- Sidebar local chỉ hiển thị các mục mà tài khoản hiện tại được back-end cấp quyền.

## Chênh lệch cần lưu ý

- `pages/pages` và cây danh mục bài viết có trong back-end nhưng không có mục riêng trong file demo; giao diện mới giữ chức năng này trong nhóm **Nội dung**.
- `common_lists/admin_dashboard` không nằm trong menu dữ liệu cũ; giao diện mới bổ sung **Dashboard tổng thể** cho tài khoản quản trị bằng đúng route hiện có.
- `congno/congno` dùng chung ba màn hình: danh sách công nợ, Dashboard KPI Kinh Doanh và Dashboard KPI KT SEO.
- `common_lists/giaoviec` dùng chung màn hình danh sách và Dashboard KPI Content.
- `common_lists/thongtinhethong` dùng chung source cho Chính sách, Quy trình, Quy định và Biểu mẫu; phân biệt bằng `category`.
- Menu dữ liệu cũ có nhãn sai hoặc khó hiểu ở `customer/customer_type` và `common_lists/group`; giao diện chỉ đổi nhãn hiển thị, không đổi route hay dữ liệu.
- Chat hiện mới là giao diện phía trình duyệt, chưa có API lưu hội thoại hoặc gửi tin nhắn giữa tài khoản.
- Việc chuyển danh sách, bảng và form sang style demo được thực hiện ở layout chung; tên `input`, URL submit và quyền thao tác back-end được giữ nguyên.

## Lỗi đã phát hiện và sửa

- 76 màn hình danh sách dùng chung bảng `selector` trước đây tự cuộn và hiển thị toàn bộ dữ liệu; nay cùng dùng card, tìm nhanh, chọn 5/10/20 dòng và phân trang như file demo.
- Thanh lọc và nút thêm mới trước đây nằm tách rời; nay được gom vào một toolbar, nút làm mới dùng icon.
- Ảnh thao tác bị ẩn vẫn tạo ô trống có màu; nay liên kết ẩn cũng được loại khỏi bố cục.
- Form thông tin được đưa về lưới chung, tự ẩn dòng rỗng và không tạo thanh cuộn dọc bên trong.
- Bộ lọc màn **Khách hàng** gán nhầm `customer_type_id` trong khi trường thật là `customer_type`, gây lỗi JavaScript; đã sửa đúng ID trường.

## Đã kiểm tra trực tiếp

- Website: 417 bản ghi, mặc định 5 dòng, tìm nhanh và chọn 10 dòng hoạt động.
- Nhân viên: 54 bản ghi, 5 dòng/trang.
- Khách hàng: 5 bản ghi, không còn phát sinh lỗi JavaScript sau khi sửa bộ lọc.
- Forum SEO: 50 bản ghi, 5 dòng/trang.
- Chính sách: bảng rỗng vẫn giữ đúng card và trạng thái `Trang 1 / 1`.
- Form Website và form danh mục bài viết: giữ nguyên field back-end, không có thanh cuộn dọc nội bộ.
- Dashboard tổng thể: 13 card, giữ layout riêng và không bị bộ chuyển đổi bảng legacy can thiệp.

## Icon và database thật

- Đã quét 581 tham chiếu icon trong template, gồm 24 asset duy nhất; hiện không còn tham chiếu tới file bị thiếu.
- Ba nguồn hỏng `file_edit.png`, `button-create.gif`, `photo.png` đã được thay bằng asset sẵn có.
- Icon xem, sửa, xóa, lên, xuống, phân quyền và đổi mật khẩu trong bảng đã chuyển sang SVG chung, có màu theo chức năng.
- Database demo `admin_buffcorp` đã được sao lưu tại `backups/admin_buffcorp_demo_20260731_153240.sql`, SHA-256 `D3C80E8B34BDF2DA235F12CB0BE68AFE197323CBC76597CF7A6D0B28697A54FF`.
- Dữ liệu thật từ `D:\Extract apps\adbuff_1.sql` đã được nhập vào `admin_buffcorp_real` và cấu hình local đã chuyển sang database này.
- Database thật có 93 bảng, 626 khách hàng, 417 website và 55 nhân sự.

## Ép bố cục từ back-end

- `mainpage.php` hiện chuẩn hóa HTML module trước khi render, gắn layout `list` hoặc `form`, route và mode thật vào card chung.
- Đã bao phủ 76 template danh sách và 69 template form; JavaScript chỉ hoàn thiện toolbar, icon, tìm kiếm và phân trang trong card do back-end tạo sẵn.
- Dashboard, KPI và sơ đồ công ty có layout riêng được bỏ qua để không làm hỏng biểu đồ hoặc luồng tùy chỉnh.
- Đã sửa lỗi `mainpage.php` không khôi phục `$_REQUEST['option']` sau khi render sidebar; module phía sau nay nhận đúng route.
- Khi mở `main.php` trực tiếp, PHP truyền route/mode đã resolve để tiêu đề và trạng thái menu vẫn chọn đúng **Dashboard tổng thể**.
- Đã kiểm tra Website, Khách hàng, form Website, form danh mục bài viết và Dashboard tổng thể; không có card lồng, không mất field/action và không có lỗi console.

## Sidebar và bảng hiện đại

- Sidebar mở rộng tăng từ 236px lên 268px; màn hình vừa dùng 248px, trạng thái thu gọn 72px.
- Chữ menu cha/con tăng từ 12/11px lên 13/12px, vùng bấm menu cũng cao hơn.
- Bảng legacy được ép nền trắng, header xanh nhạt, chữ dữ liệu 12px và card bo 12px như file demo; màu nền inline cũ không còn ghi đè giao diện.
- Các cột icon rời đã gộp thành một cột **Thao tác**; đã kiểm tra bảng có 2, 3, 4 và 5 nút trên mỗi dòng.
- Trạng thái phổ biến được hiển thị dạng badge xanh, vàng, đỏ hoặc xám; mọi nút icon có `title` và `aria-label`.
