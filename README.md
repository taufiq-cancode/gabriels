# Application Setup Guide

Follow these steps to set up and run the application on your local system.

## Prerequisites

- PHP installed on your system (minimum version PHP 8.2+ for Laravel 11).
- Composer installed to manage dependencies.
- MySQL.

## Instructions

### 1. Clone the Application from GitHub

First, clone the repository from GitHub:

```bash
git clone https://github.com/taufiq-cancode/orderMS.git
```

Navigate to the project folder:

```bash
cd /path/to/orderMS
```

### 2. Install Dependencies

Run Composer to install all required dependencies:

```bash
composer install
```

### 3. Move Product Images

To display product images, you need to move the `products` folder to the `storage/app/public` directory:

```bash
mv products storage/app/public/
```

To ensure that the `storage` directory is linked to the public folder to serve these images, create the symbolic link by running:

```bash
php artisan storage:link
```

### 4. Set Up Environment Variables

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

### 5. Generate Application Key

Generate the application key, which is used by Laravel for encryption:

```bash
php artisan key:generate
```

### 6. Set Up the Database

If you haven't already, create a new database in MySQL or your chosen database management system with the name specified in the `.env` file.

Run the migrations to set up the database schema:

```bash
php artisan migrate
```

Run the seeders to populate the database with sample data:

```bash
php artisan db:seed
```

### 7. Run the Application

Start the Laravel development server:

```bash
php artisan serve
```

By default, the application will be accessible at `http://localhost:8000`.

Admin login email: admin@example.com  
Admin login password: 1q2w3e4r

---