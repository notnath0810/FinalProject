# Contact Tracing System
### Department of Computer Engineering — University of San Carlos

A full-stack web application for on-site visitor logging and health safety compliance, built with **PHP 8 + MySQL (XAMPP)**.

---

## Features

### Visitor Portal
| Feature | Description |
|---|---|
| **First-Time Registration** | Collects full name, complete address (barangay/city/province), contact number, and email. USC ID optional for guests. |
| **Returning Visitor Sign-In** | Look up existing record by USC ID or last name; confirm info, then log entry with one click. |
| **Sign-Out** | Find active visit by ID or name; confirm and timestamp departure. |

### Admin Panel
| Feature | Description |
|---|---|
| **Secure Login** | Session-based authentication with `session_regenerate_id()` on login. |
| **Visit Log Dashboard** | Full paginated table: name, ID, address, contact, time in/out, status badge. |
| **6-Way Search Filter** | Filter by city, barangay, province, ID number, name, or date — all using prepared statements. |
| **Visit History Modal** | Click any visitor name to open an AJAX modal showing their complete visit history with duration. |
| **Delete Visitor** | Permanently removes a visitor and all their logs (FK cascade) with a JS confirmation dialog. |

---

## Tech Stack

- **Backend:** PHP 8.x, MySQLi (prepared statements throughout)
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Frontend:** Vanilla HTML5, CSS3 (Flexbox, CSS variables), Vanilla JavaScript
- **Server:** Apache + MySQL via XAMPP
- **Security:** Prepared statements, `htmlspecialchars()` output escaping, session fixation prevention, no raw errors exposed

---

## Project Structure

```
contact_tracing/
├── index.php              ← Landing page (choose visitor type)
├── register.php           ← First-time visitor registration + sign-in
├── returning.php          ← Returning visitor lookup + sign-in
├── signout.php            ← Visitor sign-out flow
├── admin/
│   ├── login.php          ← Admin authentication
│   ├── dashboard.php      ← Logs table, search filters, delete, history modal
│   └── logout.php
├── includes/
│   ├── db.php             ← MySQLi connection + helper functions
│   ├── header.php         ← Shared HTML header + nav
│   └── footer.php         ← Shared HTML footer + script tag
├── assets/
│   ├── style.css          ← Full responsive stylesheet (deep blue #003366 theme)
│   └── script.js          ← Form validation, delete confirm, history modal (AJAX)
└── contact_tracing.sql    ← Full DB export: DROP/CREATE + 7 sample visitors + logs
```

---

## Installation (XAMPP)

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) with Apache and MySQL running
- PHP 8.0 or higher

### Steps

**1. Import the database**
```
phpMyAdmin → Import → select contact_tracing.sql → Go
```
Or via MySQL CLI:
```bash
mysql -u root -p < contact_tracing.sql
```

**2. Copy the project**
```
C:\xampp\htdocs\contact_tracing\
```

**3. Verify DB config** (edit if needed)
```
contact_tracing/includes/db.php
```
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // set your MySQL password if any
define('DB_NAME', 'contact_tracing_db');
```

**4. Open in browser**
```
http://localhost/contact_tracing/
```

---

## Admin Credentials

| Field | Value |
|---|---|
| URL | `http://localhost/contact_tracing/admin/login.php` |
| Username | `admin` |
| Password | `coedept2024` |

---

## Database Schema

```sql
visitors (
  visitor_id     INT PK AUTO_INCREMENT,
  id_number      VARCHAR(50) UNIQUE NULL,   -- NULL for guests
  first_name     VARCHAR(100) NOT NULL,
  middle_name    VARCHAR(100),
  last_name      VARCHAR(100) NOT NULL,
  barangay       VARCHAR(100) NOT NULL,
  city           VARCHAR(100) NOT NULL,
  province       VARCHAR(100) NOT NULL,
  contact_number VARCHAR(20)  NOT NULL,
  email          VARCHAR(150) NOT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

visit_logs (
  log_id      INT PK AUTO_INCREMENT,
  visitor_id  INT NOT NULL → FK visitors(visitor_id) ON DELETE CASCADE,
  time_in     DATETIME NOT NULL,
  time_out    DATETIME NULL       -- NULL = visitor still on-premises
)
```

---

## Security Notes

- All user-input queries use **MySQLi prepared statements** with `bind_param`
- All output is escaped with `htmlspecialchars()` via the `e()` helper
- Admin session uses `session_regenerate_id(true)` on login to prevent fixation
- Errors are logged server-side only — no raw PHP/MySQL errors shown to users
- Admin credentials are hardcoded constants; replace with `password_hash` / `password_verify` for production

---

## Sample Data

The SQL file includes **7 sample visitors** (mix of USC ID holders and guests) with **15 visit log entries** spanning multiple dates — some open (signed in), some completed (signed out).

---

*Built for the Department of Computer Engineering, University of San Carlos — Contact Tracing & Health Safety Compliance*
