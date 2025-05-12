# Laravel Filament HR Management

A Human Resource Management System (HRMS) built using **Laravel 11** and **FilamentPHP**. This application is designed to streamline HR processes such as employee records, leave tracking, and role-based access control with Filament Shield.

## Features

- Employee Data Management  
- Leave Management System  
- Role & Permission Management (via Filament Shield) 
- Admin Dashboard with Filament v3  

## Tech Stack

- Laravel 11  
- Filament v3  
- Filament Shield  
- PHP 8.2+  
- MySQL / MariaDB  
- Tailwind CSS  
- Livewire  

## Installation

Follow these steps to set up the project:

```bash
# 1. Clone the repository
git clone https://github.com/ridhopujiono/laravel-filament-leave-management-system.git
cd laravel-filament-leave-management-system

# 2. Install PHP dependencies
composer install

# 3. Set up the environment
cp .env.example .env
php artisan key:generate

# 4. Run database migrations
php artisan migrate

# 5. Create initial Filament admin user
php artisan make:filament-user

# 6. Assign super-admin role (Filament Shield)
php artisan shield:super-admin

# 7. Generate permissions for all resources
php artisan shield:generate --all

# 8. Create symbolic link for storage access
php artisan storage:link

# 9. Start the development server
php artisan serve
