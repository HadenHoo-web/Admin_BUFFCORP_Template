<h1 align="center">Admin BUFFCORP Template</h1>

<p align="center">
  <strong>PHP Admin CMS nội bộ cho quản lý vận hành, nhân sự, khách hàng, nội dung, công nợ, KPI và tích hợp Zalo OA.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-legacy%20CMS-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Apache-.htaccess-D22128?style=for-the-badge&logo=apache&logoColor=white" alt="Apache">
  <img src="https://img.shields.io/badge/Teamwork-GitHub-181717?style=for-the-badge&logo=github&logoColor=white" alt="GitHub">
</p>

---

## Tổng quan

Admin BUFFCORP Template là source code quản trị viết bằng PHP thuần, dùng MySQL và chạy tốt nhất trên Apache có bật `mod_rewrite`. Dự án tập trung vào các nghiệp vụ quản trị nội bộ như quản lý thành viên, phân quyền, khách hàng, công nợ, sản phẩm, bài viết, giao việc, KPI, chấm công, bảng lương, thông báo quá hạn và tích hợp Zalo OA.

> Lưu ý: thư mục chính của admin trong source đang đặt tên là `bootrap`. Đây là tên hiện có của dự án, không phải lỗi chính tả trong README.

## Tính năng chính

| Nhóm chức năng | Mô tả |
| --- | --- |
| Dashboard quản trị | Trang tổng quan cho admin và nhân sự nội bộ |
| Thành viên và phân quyền | Quản lý tài khoản, vai trò, quyền tạo/sửa/xóa/duyệt/xuất bản |
| Khách hàng và bán hàng | Quản lý khách hàng, loại khách hàng, bán hàng và báo cáo |
| Công việc nội bộ | Giao việc, nhiệm vụ, xác nhận, nghỉ phép, KPI, bảng lương |
| Nội dung website | Trang nội dung, danh mục, banner, FAQ, contact, newsletter |
| Kho và sản phẩm | Quản lý loại kho, sản phẩm, màu sắc, quà tặng, xuất dữ liệu |
| SEO và ads | Quản lý traffic, keyword, forum, profile, bookmark, campaign |
| API nội bộ | Đồng bộ dữ liệu, nhận webhook, cập nhật chấm công, realtime salary |
| Cron job | Kiểm tra quá hạn, thông báo sắp hết hạn, gửi log vận hành |
| Tích hợp ngoài | Zalo OA, Google reCAPTCHA, GetPass, CuttPW |

## Công nghệ sử dụng

| Thành phần | Công nghệ |
| --- | --- |
| Backend | PHP thuần |
| Database | MySQL |
| Web server | Apache + `.htaccess` |
| Template | Template engine nội bộ trong `bootrap/includes/template.php` |
| Editor/File manager | CKEditor, CKFinder, KCFinder |
| Chart/UI asset | Bootstrap, Chart.js, CanvasJS |
| Tích hợp bảo mật | Google reCAPTCHA v2 |

## Cấu trúc thư mục

```text
.
├── admin_name.php              # Điều hướng đăng nhập admin qua /adminkh
├── api/                        # API nội bộ và endpoint đồng bộ
├── bootrap/
│   ├── config.php              # Cấu hình dự án, database, API token
│   ├── common.php              # Bootstrap hệ thống, include config/db/library
│   ├── index.php               # Entry admin
│   ├── login.php               # Xử lý đăng nhập admin
│   ├── main.php                # Khung xử lý module admin
│   ├── cron/                   # Cron job kiểm tra hạn/thông báo
│   ├── db/                     # Driver database
│   ├── includes/               # Thư viện lõi
│   ├── modules/                # Các module nghiệp vụ
│   ├── templates/              # Giao diện admin
│   ├── uploads/                # File upload/runtime
│   └── zalo/                   # Tích hợp Zalo OA
├── images/                     # Asset public
├── hk_config_vn.ini            # File log/đếm legacy
└── .htaccess                   # Rewrite URL, HTTPS, route /adminkh
```

## Yêu cầu môi trường

- PHP 7.4 trở lên được khuyến nghị cho môi trường local.
- MySQL hoặc MariaDB.
- Apache có bật `mod_rewrite`.
- PHP extensions thường dùng: `mysqli`, `curl`, `json`, `mbstring`, `gd`, `zip`.
- Quyền ghi cho các thư mục runtime: `bootrap/logs`, `bootrap/uploads`, `bootrap/zalo/logs`, `bootrap/zalo/storage`.

## Cài đặt local

### 1. Clone source

```bash
git clone https://github.com/<your-organization>/<your-repository>.git
cd <your-repository>
```

### 2. Cấu hình web server

Khuyến nghị dùng XAMPP, Laragon, MAMP hoặc Apache virtual host.

Ví dụ virtual host:

```apache
<VirtualHost *:80>
    ServerName admin-buffcorp.local
    DocumentRoot "/path/to/Admin_BUFFCORP_Template"

    <Directory "/path/to/Admin_BUFFCORP_Template">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Sau đó trỏ host local:

```text
127.0.0.1 admin-buffcorp.local
```

### 3. Tạo database

Tạo database MySQL cho dự án:

```sql
CREATE DATABASE admin_buffcorp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Import file SQL backup của team nếu có. Source hiện tại không kèm file `.sql`, nên khi onboarding cần xin file database mẫu hoặc backup mới nhất từ người phụ trách backend/database.

### 4. Cấu hình database và secret

Mở file:

```text
bootrap/config.php
```

Cập nhật các biến:

```php
$dbhost   = 'localhost';
$dbname   = 'admin_buffcorp';
$dbuser   = 'root';
$dbpasswd = '';

define('RECAPTCHA_SITE_KEY', 'your_recaptcha_site_key');
define('RECAPTCHA_SECRET_KEY', 'your_recaptcha_secret_key');
define('CUTTPW_API_URL', 'your_cuttpw_api_url');
define('CUTTPW_API_TOKEN', 'your_cuttpw_api_token');
define('GETPASS_API_URL', 'your_getpass_api_url');
define('GETPASS_API_TOKEN', 'your_getpass_api_token');
```

Nếu dùng Zalo OA, cấu hình thêm:

```text
bootrap/zalo/config/zalo.php
```

### 5. Cấp quyền thư mục ghi

Trên Linux/macOS:

```bash
chmod -R 775 bootrap/logs bootrap/uploads bootrap/zalo/logs bootrap/zalo/storage
```

### 6. Truy cập admin

Sau khi Apache chạy, mở:

```text
http://admin-buffcorp.local/adminkh
```

Hoặc truy cập trực tiếp:

```text
http://admin-buffcorp.local/bootrap
```

Tài khoản đăng nhập lấy từ bảng `tbl_member`. Mật khẩu trong source được so sánh bằng `md5`, vì vậy dữ liệu tài khoản local cần đúng format hiện có của hệ thống.

## Cron job

Các cron chính nằm trong:

```text
bootrap/cron/
```

Ví dụ cấu hình cron trên server:

```cron
*/10 * * * * /usr/bin/php /path/to/project/bootrap/cron/cron_check_overdue.php >> /path/to/project/bootrap/zalo/logs/cron_stdout.log 2>&1
0 8 * * * /usr/bin/php /path/to/project/bootrap/cron/cron_check_expiry_notifications.php >> /path/to/project/bootrap/zalo/logs/cron_stdout.log 2>&1
```

Điều chỉnh lịch chạy theo nghiệp vụ thật của team.

## API endpoint

| Endpoint | Chức năng |
| --- | --- |
| `api/receive.php` | Nhận dữ liệu/webhook nội bộ |
| `api/sync.php` | Đồng bộ dữ liệu |
| `api/update_chamcong.php` | Cập nhật dữ liệu chấm công |
| `api/salary/realtime.php` | Dữ liệu lương realtime |
| `api/salary/realtime/index.php` | Endpoint realtime salary dạng thư mục |

Khi deploy production, nên giới hạn IP, thêm token xác thực hoặc firewall cho các endpoint nội bộ.

## Quy trình làm việc nhóm với GitHub

### Branch strategy

```text
main        # code ổn định/deploy production
develop     # nhánh tích hợp tính năng
feature/*   # tính năng mới
fix/*       # sửa bug
hotfix/*    # sửa gấp production
```

### Luồng làm việc đề xuất

```bash
git checkout develop
git pull origin develop

git checkout -b feature/ten-tinh-nang

# code, test local
git add .
git commit -m "feat: mo ta ngan gon tinh nang"

git push origin feature/ten-tinh-nang
```

Sau đó tạo Pull Request vào `develop`, ít nhất 1 người review trước khi merge.

### Quy ước commit

| Prefix | Khi dùng |
| --- | --- |
| `feat:` | Thêm tính năng mới |
| `fix:` | Sửa lỗi |
| `refactor:` | Sắp xếp lại code, không đổi hành vi |
| `docs:` | Cập nhật tài liệu |
| `style:` | Sửa format, CSS, UI nhỏ |
| `chore:` | Việc phụ trợ, config, cleanup |

Ví dụ:

```bash
git commit -m "feat: them module quan ly khach hang"
git commit -m "fix: sua loi dang nhap admin"
git commit -m "docs: cap nhat huong dan cai dat local"
```

## Checklist trước khi push GitHub

- Không commit credential thật, API token, secret key production.
- Không commit database backup chứa dữ liệu khách hàng/nhân sự thật.
- Không commit log runtime trong `bootrap/logs` và `bootrap/zalo/logs`.
- Không commit file upload nhạy cảm trong `bootrap/uploads`.
- Kiểm tra lại `.htaccess` khi đổi domain hoặc deploy server mới.
- Test đăng nhập admin, phân quyền, upload file và các module vừa sửa.
- Nếu sửa cron/API, test bằng CLI hoặc Postman trước khi merge.

## Gợi ý `.gitignore`

Nên tạo `.gitignore` trước khi push public/private repo:

```gitignore
# OS/editor
.DS_Store
Thumbs.db
.idea/
.vscode/

# Runtime logs
bootrap/logs/*.log
bootrap/zalo/logs/*.log

# Runtime token/session storage
bootrap/zalo/storage/*.json

# Local uploads/backups
bootrap/uploads/*
!bootrap/uploads/.gitkeep

# Local config/backup
*.sql
*.bak
*.backup
*.zip
*.tar
*.gz
```

Nếu team cần version một số file mẫu trong `uploads`, hãy whitelist riêng từng file cần thiết.

## Bảo mật

Source hiện tại có các cấu hình nhạy cảm trong file PHP config. Trước khi đưa lên GitHub, team nên:

1. Rotate toàn bộ database password, reCAPTCHA secret, Zalo secret, CuttPW/GetPass token đã từng nằm trong source.
2. Tách config theo môi trường: local, staging, production.
3. Không public repository nếu còn dữ liệu nội bộ, log, file upload hoặc credential thật.
4. Giới hạn quyền truy cập GitHub theo team/project.
5. Bật branch protection cho `main` và yêu cầu Pull Request review.

## Deploy production

1. Pull source từ nhánh ổn định:

```bash
git pull origin main
```

2. Cập nhật cấu hình production trong `bootrap/config.php` và `bootrap/zalo/config/zalo.php`.
3. Đảm bảo Apache trỏ `DocumentRoot` về root project.
4. Bật HTTPS.
5. Cấp quyền ghi cho thư mục log/upload/storage.
6. Cấu hình cron job.
7. Test các đường dẫn chính:

```text
/adminkh
/bootrap
/api/sync.php
/api/update_chamcong.php
```

## Troubleshooting

| Lỗi | Cách kiểm tra |
| --- | --- |
| Trang trắng | Xem log tại `bootrap/logs/php_errors.log` |
| Không vào được `/adminkh` | Kiểm tra Apache `mod_rewrite` và `AllowOverride All` |
| Database connection error | Kiểm tra `$dbhost`, `$dbname`, `$dbuser`, `$dbpasswd` trong `bootrap/config.php` |
| Đăng nhập báo reCAPTCHA | Kiểm tra `RECAPTCHA_SITE_KEY` và `RECAPTCHA_SECRET_KEY` |
| Cron không chạy | Chạy thử `php bootrap/cron/<file>.php` bằng CLI và xem log |
| Zalo không gửi được | Kiểm tra `bootrap/zalo/config/zalo.php`, token storage và log `bootrap/zalo/logs/zalo_send.log` |

## Thành viên phát triển

| Vai trò | Phụ trách |
| --- | --- |
| Project owner | Quản lý repo, phân quyền GitHub, duyệt release |
| Backend/PHP | Module admin, API, cron, database |
| Frontend/Admin UI | Template, CSS, JS, form, bảng dữ liệu |
| QA/Tester | Test nghiệp vụ, regression test trước merge |
| DevOps | Server, Apache, HTTPS, cron, backup |

## License

Source code dùng cho mục đích nội bộ của BUFFCORP/team dự án. Nếu cần open-source hoặc chia sẻ cho bên thứ ba, hãy thống nhất license và loại bỏ toàn bộ dữ liệu/credential nội bộ trước khi publish.
