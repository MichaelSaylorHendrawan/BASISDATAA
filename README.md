# 🔐 Laravel Inertia Vue Authorization System

Welcome to a comprehensive **Role-Based Access Control (RBAC)** system built with Laravel 12 and Inertia Vue 3! This project demonstrates how to create a secure, scalable authorization system that's perfect for learning modern web development patterns.

## Live Demo
**Try it now:** [https://custom-authentication-authorization.laravelcs.com](https://custom-authentication-authorization.laravelcs.com)

Experience the complete authorization system in action!

## Screenshots

![Laravel inertia custom authorization](https://laravelcs.com/images/github/laravel-ineria-custom-authentication-authorization.png)

## 🚀 Quick Start Guide

### **Prerequisites**
- PHP 8.2+ 
- Laravel 12.x
- Node.js 18+ & npm
- MySQL 8.0+
- Vue 3.x

### **Installation Steps**

1. **Clone & Install Dependencies**
```bash
git clone https://github.com/mmrahmanwritescode/laravel-custom-inertia-authorization
cd laravel-custom-inertia-authorization
composer install
npm install
```

2. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
# Create a MySQL database (e.g., 'laravel_authorization')
# Update DB_DATABASE in .env with your database name
# Update DB_USERNAME and DB_PASSWORD with your MySQL credentials
```

4. **Run migrations with seeders**
```bash
php artisan migrate:fresh --seed
```

---

## Topgolf Database (Branches — Tiers — Members) 🔧

This project includes an optional **Topgolf** schema (three tables: `branches`, `membership_tiers`, `members`) and a seeder that can generate **500,000** dummy members efficiently.

**Setup notes:**
- Set DB connection to MySQL in `.env` (example):
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Topgolf
DB_USERNAME=root
DB_PASSWORD=
```
- If you want the fastest import use MySQL's `LOAD DATA LOCAL INFILE`. Ensure the server and client allow `local_infile` (MySQL option).

**How the seeder works:**
- `BranchSeeder` and `MembershipTierSeeder` create master data (a few rows).
- `MemberSeeder` generates `topgolf_members.csv` (500k rows) in `storage/app/` and then attempts `LOAD DATA LOCAL INFILE`. If that fails, it falls back to chunked multi-row inserts.

**Commands**
```bash
# Run only the Topgolf seeders (after setting DB env to use MySQL)
php artisan db:seed --class=BranchSeeder
php artisan db:seed --class=MembershipTierSeeder
# This will create the CSV and attempt fast bulk import (may require `local_infile` enabled)
php artisan db:seed --class=MemberSeeder
```

**Tip:** On slower environments you can reduce the `500000` value in `MemberSeeder` during testing.

---


5. **Build Assets & Start Development**
```bash
# Terminal 1: Frontend assets
npm run dev

# Terminal 2: Laravel server
php artisan serve
```

### **Default Login Credentials**
- **Admin**: admin@example.com / password
- **Staff**: staff@example.com / password

## 🔧 Troubleshooting

### **Common Issues**
- **Permission Denied**: Ensure user has the required permission for the route
- **Session Issues**: Clear browser cache and restart `php artisan serve`
- **Database Connection**: Verify MySQL credentials in `.env` file
- **Asset Issues**: Run `npm run build` for production or `npm run dev` for development

### 🔒 **Advanced Authorization Features**

#### **Multi-Level Permission System**
- **Roles**: Admin, Staff, Manager, Customer (fully customizable)
- **Permissions**: Granular control over every action (view, create, edit, delete)
- **Dynamic Assignment**: Users can have multiple roles and Roles can have multiple permissions 

#### **Smart Middleware Protection**
- **Session-based Authentication**: Secure login/logout system
- **Permission Middleware**: Route-level access control
- **Automatic Redirects**: Unauthorized users are gracefully handled

#### **Beautiful Admin Interface**
- **Role Management**: Visual permission assignment with checkbox interfaces
- **Permission Management**: Create custom permissions for your application
- **User Management**: Create, edit, delete users with role assignment
- **Product Management**: Example CRUD operations with permission controls

## 🎨 User Experience Highlights

### **Intuitive Permission Management**
- **Visual Permission Grid**: Check/uncheck permissions with instant preview
- **Smart Validation**: Prevents deletion of assigned roles/permissions
- **Real-time Feedback**: Success/error notifications for all actions
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile

### **Developer-Friendly Features**
- **Clean Architecture**: Follows Laravel best practices
- **Reusable Components**: Modular Vue components
- **Type Safety**: Proper validation and error handling
- **Database Seeders**: Pre-populated demo data for testing


## 🏗️ System Architecture

### **Database Design**
```
Users ←→ Roles ←→ Permissions
  │         │         │
  └─ Many-to-Many relationships with proper pivot tables
```

### **Key Models & Relationships**
- **User Model**: Enhanced with role/permission checking methods
- **Role Model**: Manages user groups with specific permission sets
- **Permission Model**: Individual access rights (view-user, edit-product, etc.)

### **Middleware Stack**
- `SessionAuthenticate`: Ensures user is logged in
- `PermissionMiddleware`: Checks specific permissions

### **Custom Route Structure**
Instead of Laravel's default resource routes, we use custom route definitions with granular permission middleware:

```php
// Custom approach for permission-specific middleware
Route::get('/', 'index')->middleware('permission:view-product');
Route::get('/create', 'create')->middleware('permission:create-product');
// vs standard Route::resource('products', ProductController::class);
```

### **Permission List Documentation**

### **Available Permissions**
```
Products: view-product, create-product, edit-product, delete-product
Users: view-user, create-user, edit-user, delete-user  
Roles: view-role, create-role, edit-role, delete-role
Permissions: delete-permission
```

## 📁 Project Structure

```
app/
├── Http/Controllers/     # CRUD operations for all resources
├── Http/Middleware/      # Authentication & authorization
├── Models/              # Eloquent models with relationships
database/
├── migrations/          # Database schema
├── seeders/            # Demo data for testing
resources/
├── js/Pages/           # Vue components for each feature
├── js/Components/      # Reusable UI components
├── js/Layout/          # Application layout
routes/
└── web.php             # Protected route definitions
```

## 🔍 Concepts Used

This project demonstrates:

### **Laravel Best Practices**
- Eloquent relationships and eager loading
- Form request validation
- Controllers with granular permission middleware set inside routes/web.php for respective methods

### **Vue 3 Patterns**
- Reactive data management
- Component communication
- Form handling with Inertia.js

### **Security Implementation**
- CSRF protection
- XSS prevention
- SQL injection protection
- Proper session management

## 🛡️ Security Features

- **Password Hashing**: BCrypt with proper salting
- **Session Security**: Regeneration on login/logout
- **CSRF Protection**: Built-in Laravel token validation
- **XSS Prevention**: Auto-escaping in templates
- **Mass Assignment Protection**: Fillable model attributes

---

## 🎯 Next Steps

Ready to explore? Here's what you can try:

1. **Login as admin** and explore the full feature set
2. **Create new roles** and see how permissions work
3. **Add new users** and test different access levels
4. **Customize the UI** to match your brand
5. **Extend the system** with your own features

Happy Lara coding! 🚀

---

⭐ **If this project helped you, please give it a star!** ⭐
