# 🧾 Payroll Management System

A modern and extensible payroll management web application built with **Laravel 11**, **Filament v3**, and **MySQL**. This system is designed to streamline employee management, attendance tracking, salary computation, and payslip generation in a professional environment.

---

## 🚀 Features

### 1. 👥 Employee Management
- Add, edit, and delete employees
- Assign employees to departments or services
- Manage contract types (CDI, CDD, Interim)

### 2. ⏱️ Attendance Management
- Record arrival and departure times
- Automatic calculation of hours and days worked
- Manage absences, sick leaves, late arrivals, and vacations

### 3. 💰 Payroll & Salary Calculation
- Support for fixed, hourly, and daily wages
- Deduct salaries for unjustified absences
- Manage bonuses, overtime, and penalties
- Auto-salary calculation based on presence data

### 4. 📅 Leave Management
- Submit and approve leave requests
- Track remaining leave balances
- Handle sick leaves and other absences

### 5. 📄 Payslip Management
- Automatically generate payslips based on attendance and payroll rules
- Export payslips as PDF
- Access salary history for each employee

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Admin Panel**: Filament v3
- **Database**: MySQL
- **PDF Generation**: (Laravel DomPDF / Snappy PDF - TBC)
- **Authentication**: Laravel Breeze / Filament Auth

---

## 🔧 Installation

```bash
git clone https://github.com/Domiynsbelhanda/payroll-app.git
cd payroll-app

composer install
cp .env.example .env
php artisan key:generate

# Configure your .env DB settings

php artisan migrate
php artisan filament:install --panels
php artisan make:filament-panel AdminPanel
php artisan make:filament-user
