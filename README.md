# Alamanda Houseboat Booking System

A Laravel-based houseboat booking system for Tasik Kenyir. Features include user authentication, package management, booking management, coupon discounts, invoice/receipt generation, and admin dashboard.

## Features

- **User Authentication**: Login and registration for guests
- **Package Management**: Admin can create and manage houseboat packages (Full Board, Semi Board, Room Only)
- **Booking System**: Guests can book packages with date selection and guest management
- **Coupon System**: Admin can create discount coupons (percentage or fixed amount)
- **Payment Tracking**: Upload payment proof, admin confirmation, receipt generation
- **Invoice/Receipt Generation**: PDF generation for invoices and receipts
- **Admin Dashboard**: Manage bookings, users, packages, coupons, and generate reports
- **Role-Based Access**: Admin, Staff, and User roles with appropriate permissions
- **Guest Reviews**: Users can submit reviews for completed bookings

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Laravel 10

## Installation Steps

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd alamanda
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file with your database credentials:

```env
DB_DATABASE=alamanda
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed the Database (Optional)

```bash
php artisan db:seed
```

### 7. Link Storage

Create symbolic link for publicly accessible files:

```bash
php artisan storage:link
```

### 8. Create Directories

Ensure the following directories exist and are writable:

```bash
mkdir -p storage/app/public/payment_proofs
mkdir -p storage/app/public/receipts
mkdir -p public/storage
```

### 9. Build Assets

```bash
npm run build
```

### 10. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Access

After installation, create an admin user via database or seeder:

- **Admin Email**: admin@example.com
- **Admin Password**: (set during seeding or create manually)

## Project Structure

```
alamanda/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Api/            # API controllers
│   │   │   ├── AuthController.php
│   │   │   ├── BookingController.php
│   │   │   ├── PaymentController.php
│   │   │   └── ...
│   │   └── Middleware/
│   ├── Models/                 # Eloquent models
│   └── ...
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin views
│   │   ├── user/               # User/guest views
│   │   ├── layouts/            # Layout templates
│   │   └── components/         # Blade components
│   └── ...
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes
└── public/                     # Public files
```

## Main Routes

### Public Routes
- `/` - Redirect to home
- `/home` - Home page
- `/alamanda_home` - Home page view
- `/login` - Login page
- `/register` - Registration page

### User Routes (Authenticated)
- `/booking` - Create booking
- `/my-bookings` - View bookings
- `/edit_profile` - Edit profile
- `/payment/{booking}` - Payment page
- `/invoice/{booking}` - View invoice
- `/receipt/{id}` - View receipt

### Admin Routes
- `/admin/dashboard` - Admin dashboard
- `/admin/admin_dashboard` - Admin management
- `/admin/users` - User management
- `/admin/bookings` - Booking management
- `/admin/packages` - Package management
- `/admin/coupons` - Coupon management
- `/admin/reports` - Generate reports

## Configuration

### Storage Links

After `php artisan storage:link`, place files in:
- Logo: `public/storage/pic/logo_alamanda.png`
- Payment proofs: `storage/app/public/payment_proofs/`
- Receipts: `storage/app/public/receipts/`

### Coupon Configuration

Coupons can be:
- **Percentage**: e.g., 10% discount
- **Fixed Amount**: e.g., RM 50 discount
- **One-time use** or **Multiple use**
- With **usage limit** and **expiry date**

## Troubleshooting

### Storage Link Issues

If images/files don't load:

```bash
php artisan storage:link
# Or manually create symlink in public/storage -> ../storage/app/public
```

### Permission Issues

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Asset Build Issues

```bash
rm -rf node_modules
npm install
npm run build
```

## License

This project is proprietary software.

## Support

For support, email: alamandahouseboatkenyir@gmail.com
