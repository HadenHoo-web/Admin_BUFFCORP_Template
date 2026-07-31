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
- Tên, thứ tự và liên kết sidebar lấy nguyên từ `tbl_function_menu`; muốn đổi nhãn thân thiện cần chỉnh trong màn **Quản lý menu** để back-end và giao diện luôn đồng bộ.
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

- Sidebar dùng đúng kích thước file HTML: mở 236px, thu gọn 68px; khi thu gọn chỉ còn logo và icon danh mục cha.
- Chữ menu cha/con dùng đúng tỷ lệ 12/11px; bấm danh mục cha khi đang thu gọn sẽ mở lại sidebar và hiển thị danh mục con.
- Bảng legacy được ép nền trắng, header xanh nhạt, chữ dữ liệu 12px và card bo 12px như file demo; màu nền inline cũ không còn ghi đè giao diện.
- Các cột icon rời đã gộp thành một cột **Thao tác**; đã kiểm tra bảng có 2, 3, 4 và 5 nút trên mỗi dòng.
- Trạng thái phổ biến được hiển thị dạng badge xanh, vàng, đỏ hoặc xám; mọi nút icon có `title` và `aria-label`.

## Đồng bộ giao diện HTML vào source

- Topbar đã bổ sung nút sáng/tối, tìm kiếm chức năng, modal bảng lương và drawer thông báo theo bố cục file HTML.
- Mobile menu, lớp phủ và thao tác đóng menu đã được thêm ở breakpoint 820px; desktop vẫn giữ chức năng thu gọn sidebar.
- Dashboard Admin, KPI Content, KPI Kinh doanh, KPI tổng hợp và sơ đồ công ty được ép về cùng font Manrope, nền xanh-trắng, card, bảng và bộ lọc của file HTML.
- Dashboard tổng thể vẫn dùng dữ liệu thật: 13 card, 3 cột ở màn hình desktop hiện tại, không thay các truy vấn hoặc đường dẫn back-end.
- Icon danh mục con được ánh xạ theo chức năng; kiểm tra trực tiếp có 78 icon và không còn icon vòng tròn mặc định trên sidebar hiện tại.
- Form Website giữ 34 trường, submit về `main.php`, đúng route `common_lists/website`, chữ đen và không có thanh cuộn dọc nội bộ.
- Đã kiểm tra trực tiếp chế độ tối, drawer thông báo, modal bảng lương, tìm kiếm chức năng, thu gọn/mở lại sidebar, Dashboard KPI Content và bảng Website 417 bản ghi; không có lỗi console.

## Phần chủ động giữ khác dữ liệu demo

- Số liệu, thông báo, lương, quyền menu và nhãn người dùng lấy từ database/API thật nên không dùng các con số minh họa cố định trong file HTML.
- Chat hỗ trợ giữ đúng hành vi demo phía trình duyệt; chưa có API lưu hội thoại hoặc nhắn tin giữa tài khoản.
- `config.local.php`, database backup và log runtime chỉ dùng local, không đưa lên GitHub.

## Cây menu và phân quyền

- Màn **Quản lý menu** giữ cấu trúc cha–con từ `tbl_function_menu`, hiển thị số mục con và sửa liên kết mở cấp con để không còn URL dạng `#=id`.
- Màn **Phân quyền người dùng** giữ nguyên field `dung{member_id}`, bộ lọc phòng ban và route `permission_save`; chỉ sửa bố cục form hợp lệ và đồng bộ giao diện BUFFCORP.

## Hoàn tất đồng bộ toàn hệ thống

- Đã kiểm tra trực tiếp đủ 65/65 route trong file demo: tất cả dùng shell BUFFCORP, đúng layout riêng hoặc card danh sách/form và không còn trang in mã nguồn PHP.
- Tiêu đề và trạng thái menu nay đối chiếu cả `option`, `mode`, `category`, `cid`; các route dùng chung module không còn chọn nhầm màn hình.
- Đã đổi PHP short tag sang `<?php` tại 14 module để chạy ổn định khi `short_open_tag` tắt; đây là thay đổi tương thích back-end, không đổi dữ liệu hay route.
- Form gửi mail, đổi mật khẩu, cấu hình và thư viện ảnh đã giữ nguyên tên field/submit back-end nhưng được trình bày theo giao diện mới, không có thanh cuộn dọc nội bộ.
- Trang **Đi Forum** mặc định tải 100 dòng, giới hạn 500 dòng và gom truy vấn đếm liên kết con để tránh treo giao diện; dữ liệu vẫn lấy từ database thật.
- Getpass hiện báo token ngoài hệ thống không hợp lệ và Cuttpw trả HTTP 401; không thay token hoặc thông tin xác thực trong lần cập nhật giao diện này.

## Đồng bộ cây menu database

- Đã bỏ danh sách 8 nhóm viết cứng trong JavaScript trước đây vì lớp này xóa và đổi tên cây menu do back-end sinh ra.
- Sidebar hiện hiển thị đúng 7 danh mục cha và 65 danh mục con từ `tbl_function_menu`, giữ nguyên tên, `priority`, `link` và quyền trong `tbl_permission`.
- Route trùng nhau ở nhiều nhánh được phân biệt bằng tham số `menu`; chỉ đúng một mục và một danh mục cha được mở/đánh dấu.
- **Tổng quan / Dashboard tổng thể** và **Quản lý Tin tức** vẫn được giữ riêng vì đây là hai chức năng đã bổ sung trước đó, không phải bản ghi trong `tbl_function_menu`.
