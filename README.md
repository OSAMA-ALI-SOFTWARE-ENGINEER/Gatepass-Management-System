# Gatepass Management System

PHP/MySQL web application that streamlines the process of registering, tracking, and reporting on visitors and vehicles entering a guarded facility (campus, office, hostel, etc.). The UI is built on a Bootstrap 4 admin theme and ships with ready-made screens for day-to-day gate operations.

---

## Key Features
- Dashboard metrics for total, today, and yesterday visitor and vehicle counts plus quick links into detail views.
- Visitor registry with add/edit forms, photo upload, enter/exit timestamps, remarks, search, filters for "inside" versus "exited today", and soft validation for missing date columns.
- Vehicle register with similar CRUD, enter/exit tracking, inline AJAX updates (`vehicle-update.php`), and per-row activity logging (`storage/logs/vehicle-update.log`).
- Department and designation masters for maintaining lookup values used throughout visitor records.
- Reports between dates (`bwdates-reports.php`) to audit visits over a chosen window.
- QR scanning workflow (`scan_page.php`) powered by [html5-qrcode](https://github.com/mebjas/html5-qrcode) to fetch visitor details on scan, with hits optionally logged to `scan_log`.
- Account administration: login, forgot/reset password, change password, and profile update for the admin user (`tbladmin`).

---

## Technology Stack
- PHP 7.4+ with the `mysqli` extension
- MySQL or MariaDB (configure your own database name during setup)
- Apache or any PHP-capable web server (XAMPP/LAMP/WAMP recommended for local development)
- Bootstrap 4.1, Font Awesome, jQuery, and other UI assets vendored under `vendor/`
- Optional online dependency: `html5-qrcode` (loaded from CDN in `scan_page.php`)

---

## Project Layout
- `css/` - Theme stylesheets bundled with the admin template
- `images/` - Backgrounds and illustrative assets
- `includes/` - Shared PHP includes (`dbconnection.php`, `header.php`, `sidebar.php`, `fetch_data.php`)
- `js/` - Template JavaScript for Bootstrap, charts, helpers
- `storage/logs/` - Runtime logs (for example `vehicle-update.log`)
- `uploads/` - Visitor photo uploads (ensure write permission)
- `vendor/` - Front-end libraries (Bootstrap, FontAwesome, etc.)
- `*.php` - Page controllers (dashboard, forms, reports, API endpoints)
- `README.md`

---

## Prerequisites
1. PHP 7.4 or newer (tested up to PHP 8.2) with `mysqli` enabled.
2. MySQL 5.7 / 8.0 or MariaDB equivalent.
3. Composer is **not** required; dependencies are already vendored.
4. Writable `uploads/` and `storage/logs/` directories for file storage and logging.
5. Internet access if you plan to use the QR code scanning page (loads `html5-qrcode` from a CDN).

---

## Installation and Setup
1. Clone or copy the project into your web server's document root (for example `c:\xampp\htdocs\gatepass-main`).
2. Create the database (choose any name you prefer; `gatepass_db` is used here as an example):
   ```sql
   CREATE DATABASE gatepass_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Import schema/data:
   - If you already have an export, import it into your chosen database.
   - Otherwise, create the tables listed in the [Database Overview](#database-overview) section manually (the column names are inferred from the codebase).
4. Configure database credentials by editing `includes/dbconnection.php`:
   ```php
   $con = mysqli_connect("localhost", "root", "", "your_database_name");
   ```
   Adjust the host, username, password, and database name to match your environment.
5. Ensure writable directories:
   ```bash
   chmod -R 775 uploads storage/logs   # Linux or macOS example
   ```
   On Windows, confirm the IIS/Apache user has modify rights.
6. Start your web server (for example Apache via XAMPP Control Panel) and navigate to `http://localhost/gatepass-main/`.
7. Create an admin account if the database is empty (see below).

---

## Database Overview
| Table | Purpose | Key Columns Used In Code |
| ----- | ------- | ------------------------ |
| `tbladmin` | Stores the admin account that can log in. | `ID`, `AdminName`, `UserName`, `Password` (MD5 hash), `MobileNumber`, `Email` |
| `tblvisitor` | Master table for visitor profiles and visit logs. | `ID`, `FullName`, `rollno`, `Deptartment`, `des`, `MobileNumber`, `Email`, `Address`, `CNIC`, `photo`, `EnterDate`, `ExitDate`, `remark` |
| `vehicles` | Registered vehicles linked to visitors or employees. | `id`, `fullname`, `vnumber`, `EnterDate`, `ExitDate` |
| `departments` | Department catalog used in visitor forms. | `id`, `dep` |
| `designations` | Designation/role catalog used in visitor forms. | `id`, `designation` |
| `scan_log` | Optional audit trail for QR scans. | `id`, `rollno`, `created_at` (add a timestamp column for better tracing) |

> Note: The application defensively adds `EnterDate` and `ExitDate` columns at runtime if they are missing to avoid fatal errors. It is still best to define them up-front in your schema.

---

## Seed an Admin Account
Insert at least one administrator so you can sign in:
```sql
INSERT INTO tbladmin (AdminName, UserName, MobileNumber, Email, Password)
VALUES ('Site Admin', 'admin', '03001234567', 'admin@example.com', MD5('ChangeMe123!'));
```
You can later change the password from within the UI (`change-password.php`).

---

## Daily Workflow
- Visitor intake  
  - Use `visitors-form.php`, `studententry.php`, `teacherentry.php`, or `class4entry.php` to create new profiles.  
  - `save_visitor.php` handles photo uploads into `uploads/` and stores the relative path in `tblvisitor.photo`.  
  - `visitor-enter.php` and `visitor-exit.php` stamp entry or exit times; `visitor-update.php` lets you amend dates manually.

- Vehicle tracking  
  - Add vehicles via `addvehicle.php`, manage them in `managevehicle.php`.  
  - Inline edit uses AJAX (`vehicle-update.php`) and logs every request to `storage/logs/vehicle-update.log`.  
  - Entry/exit buttons call `vehicle-enter.php` and `vehicle-exit.php`.

- Reports and auditing  
  - `bwdates-reports.php` -> `bwdates-reports-details.php` provides between-dates filtering on `tblvisitor.EnterDate`.  
  - `manage-newvisitors.php` and `managevehicle.php` include status filters (`All`, `Currently Inside`, `Exited Today`) plus message banners driven by the `msg` query parameter.

- QR scanning  
  - Visit `scan_page.php`, allow camera access, and scan a code containing `tblvisitor.ID`.  
  - `includes/fetch_data.php` returns JSON used to populate the result panel.  
  - Make sure `scan_log` exists if you want to retain scan history (the script attempts to insert into it).

---

## Logging and File Storage
- Uploads: Images land in `uploads/`. The application will create the folder on demand, but pre-creating it with appropriate permissions is safer.
- Logs: Runtime diagnostics append to `storage/logs/vehicle-update.log`. You can add more log files as needed; keep the directory writable.

---

## Development Notes
- Error reporting is suppressed across most pages (`error_reporting(0);`). Enable it temporarily while debugging:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- SQL queries are currently composed manually. For production hardening consider parameterised queries (prepared statements) and stronger password hashing (for example `password_hash()`).
- Front-end libraries live under `vendor/`. Replace or upgrade them by dropping in newer versions as required; no build pipeline is involved.
- `scan_page.php` includes the QR library from a CDN. Bundle it locally if offline access is required.

---

## Troubleshooting
- Blank pages or redirects back to login: Confirm `$_SESSION['cvmsaid']` is being set after login. Sessions require `session.save_path` to be writable.
- "Connection Fail" errors: Verify credentials in `includes/dbconnection.php` and ensure MySQL is running.
- Image upload failures: Check `uploads/` permissions and PHP's `upload_max_filesize` / `post_max_size`.
- QR scanner not loading: Ensure the device has a camera and the page is served over HTTP or HTTPS with camera permissions granted.
- Missing columns: The code attempts to auto-add `EnterDate` and `ExitDate`, but other columns (for example `rollno`, `photo`) must exist. Revisit the [Database Overview](#database-overview) table if you see SQL errors.

---

## Next Steps
- Add a `database/` folder with a SQL dump for easy bootstrapping.
- Replace MD5 password storage with PHP's `password_hash()` / `password_verify()`.
- Implement role-based access control if guards or clerks require separate logins.
- Expand reporting (CSV export, vehicle statistics, etc.) as needed.

---

## License
No explicit license is provided. Treat this project as "all rights reserved" until the authors specify otherwise.
