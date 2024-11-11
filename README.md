# Application Setup Guide

Follow these steps to set up and run this Laravel application on your local system.

## Prerequisites

- PHP installed on your system (minimum version depends on the Laravel version, e.g., PHP 8.2+ for Laravel 11).
- Composer installed to manage dependencies.
- MySQL.

## Instructions

### 1. Install Dependencies

Navigate to the extracted project folder using the terminal:

```bash
cd /path/to/laravel-project
```

Run Composer to install all required dependencies:

```bash
composer install
```

### 2. Set Up Environment Variables

Copy the `.env.example` file to create a new `.env` file:

```bash
cp .env.example .env
```

Open the `.env` file and configure your environment variables, especially the database settings:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Generate Application Key

Generate the application key, which is used by Laravel for encryption:

```bash
php artisan key:generate
```

### 4. Set Up the Database

If you haven't already, create a new database in MySQL or your chosen database management system with the name specified in the `.env` file.

Run the migrations to set up the database schema:

```bash
php artisan migrate
```

Run the seeders to populate the database with sample data:

```bash
php artisan db:seed
```

### 5. Run the Application

Start the Laravel development server:

```bash
php artisan serve
```

By default, the application will be accessible at `http://localhost:8000`.

Admin login email: admin@example.com
Admin login password: 1q2w3e4r
---