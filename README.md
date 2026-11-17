# SNEC Talents' Meet 2026-27 — College Fest Management System

A modern, centralized web application designed to manage college-level arts and cultural festivals with zero-friction tabulation, automated score calculation, transparent leaderboards, and isolated portals for participating groups.

Built with **Laravel 13**, **MySQL / SQLite**, **Blade**, and **Bootstrap 5**.

---

## 🎨 Theme & Visual Identity

- **Festival Title:** SNEC Talents' Meet 2026-27 (Edition 03)
- **Institution:** Shamsul Ulama Islamic & Arts College
- **Logo Asset:** `public/images/logo.png`
- **Official Palette:**
  - Teal / Mint: `#16b4a6` / `#0d9488` (Primary brand color)
  - Coral / Red: `#ea4949` (Accent & alert medals)
  - Gold / Yellow: `#f5a623` (1st Prize & Podium)
  - Lime / Green: `#8dc63f` (Status & Success)
  - Dark Navy: `#0f172a` (Admin navigation & cards)

---

## 🔑 Login Credentials

### 1. Super Administrator Portal (`/admin/login`)
- **Username:** `admin`
- **Password:** `admin123`
- **Capabilities:** Full control over sections, groups, members, categories, marks, programs, result entry, draft/publish workflow, and printable scoresheets.

### 2. Group Dedicated Portals (`/group/login`)
Each group has dedicated credentials that direct them to an isolated dashboard:

| Group Name | Section | Username | Password | Theme Color |
|---|---|---|---|---|
| **Emerald Hawks** | Bakalooriyya | `emerald` | `group123` | `#10b981` (Emerald Green) |
| **Ruby Knights** | Bakalooriyya | `ruby` | `group123` | `#ef4444` (Ruby Red) |
| **Sapphire Tigers** | Sanaviyya Ulya | `sapphire` | `group123` | `#3b82f6` (Sapphire Blue) |
| **Topaz Warriors** | Sanaviyya Ulya | `topaz` | `group123` | `#f59e0b` (Topaz Gold) |

---

## 🚀 Key Modules & Features

### 1. Dual-Portal Architecture
- **Super Admin Panel (`/admin/*`):** Central command for festival organizers.
- **Dedicated Group Portals (`/group/*`):** Isolated views for each group. Group users cannot view or modify other groups' internal information.
- **Public Front-End (`/`):** Live responsive leaderboard with 3D podium for top 3 groups, category breakdowns, schedule, and certified published results.

### 2. Automated Category-Based Mark Calculation
- Categories have independently configurable points for 1st, 2nd, and 3rd prizes (e.g., Cat A: 10/7/5, Cat B: 15/10/5).
- When an administrator selects a program and award position, the system automatically fetches the correct points without manual calculation errors.
- Administrators can override marks if special circumstances occur.

### 3. Result Integrity & Duplicate Prevention
- A database-level unique constraint prevents assigning the same prize position multiple times to the same program.
- Draft results remain completely invisible to group users and public scoreboards until explicitly published.
- Bulk-publish tool allows administrators to publish all draft results simultaneously when ready.

### 4. Deterministic Tie-Breaker Engine
- Rankings are calculated deterministically:
  1. **Total Points** (Highest wins)
  2. **1st Prize Count** (Tie-breaker 1)
  3. **2nd Prize Count** (Tie-breaker 2)
  4. **3rd Prize Count** (Tie-breaker 3)

### 5. Official Printable Scoresheets & CSV Export
- Clean, printable PDF-ready scoresheet with signoff blocks for Arts Convener, Tabulation Controller, and Principal (`/admin/rankings/print`).
- Instant CSV export of all enrolled student members.

---

## 💻 Local Quickstart

### Prerequisites
- PHP 8.2+
- Composer
- SQLite or MySQL

### Setup
```bash
# Clone or navigate to the project directory
cd college-fest

# Install dependencies (already prepared)
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed demo data
php artisan migrate:fresh --seed

# Start the local development server
php artisan serve
```

Access the application in your browser:
- Public Live Site: `http://localhost:8000`
- Group Login: `http://localhost:8000/group/login`
- Super Admin Login: `http://localhost:8000/admin/login`

---

## 🧪 Running Automated Tests

Run the full PHPUnit feature test suite covering authentication, data isolation, automated mark calculation, duplicate prevention, and publication:

```bash
php vendor/phpunit/phpunit/phpunit tests/Feature/CollegeFestTest.php
```

---

## 🌐 Hostinger & Production Deployment

1. Upload the project files to your server (e.g. Hostinger hPanel).
2. Set document root to the `public/` directory.
3. In `.env`, switch database connection to MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_mysql_database
   DB_USERNAME=your_mysql_user
   DB_PASSWORD=your_mysql_password
   ```
4. Run migrations:
   ```bash
   php artisan migrate --seed --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
