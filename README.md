# 🚗 BERUDI - Premium Audi Sport Car Rental
A premium car rental management system built with PHP, MySQL, Js and Bootstrap. Designed for renting luxury Audi sports cars with a modern, responsive interface.

## ✨ Features

### 👤 For Customers
- **🔍 Browse Premium Vehicles**: View collection of high-end Audi sports cars
- **🎯 Advanced Search**: Find cars by model, price, and specifications  
- **📅 Easy Reservations**: Book vehicles with simple date selection
- **📱 Responsive Design**: Optimized for all devices
- **👤 User Dashboard**: Manage personal reservations and profile

### 👨‍💼 For Administrators  
- **📊 Dashboard**: Monitor users, vehicles, and reservations
- **👥 User Management**: View and manage registered customers
- **🚗 Vehicle Management**: Add, edit, and manage car inventory
- **✅ Reservation Control**: Approve, confirm, or cancel bookings
- **📈 Analytics**: Track system usage and performance

## 🛠️ Tech Stack

- **Backend**: PHP 7.4+, MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 4.5.2
- **Server**: Apache compatible
- **Database**: MySQL

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP/WAMP (for local development)

### Installation

1. **Download & Setup**
   ```bash
   # Download from GitHub or clone repository
   git clone https://github.com/berkayenesborekci/BERUDI.git
   cd BERUDI
   ```

2. **Database Setup**
   ```sql
   # Create database
   mysql -u root -p
   CREATE DATABASE berudi_db;
   USE berudi_db;
   source database.sql;
   ```

3. **Configure Database Connection**
   ```php
   // config.php
   $servername = "localhost";
   $username = "root";           
   $password = "";               
   $dbname = "berudi_db";
   ```

4. **Create Admin User**
   ```bash
   # Visit: http://localhost/BERUDI/create_admin.php
   # This creates admin account: admin / admin123
   ```

5. **Access Application**
   - **Main Website**: `http://localhost/BERUDI`
   - **Admin Panel**: `http://localhost/BERUDI/admin`

## 📁 Project Structure

```
BERUDI/
├── 📁 admin/                  # Admin panel files
│   ├── index.php             # Admin dashboard
│   ├── vehicles.php          # Vehicle management
│   ├── users.php             # User management
│   ├── reservations.php      # Reservation management
│   └── manage_reservation.php # Reservation actions
├── 📁 assets/                # Static assets
│   ├── 📁 css/
│   │   └── style.css         # Custom styles
│   ├── 📁 js/
│   │   ├── main.js           # Main functionality
│   │   ├── admin.js          # Admin panel scripts
│   │   └── validation.js     # Form validation
│   └── 📁 images/            # Vehicle images
├── 📁 includes/              # Shared components
│   └── functions.php         # Common functions
├── 📄 index.php              # Homepage
├── 📄 login.php              # User authentication
├── 📄 register.php           # User registration
├── 📄 vehicles.php           # Vehicle catalog
├── 📄 reserve.php            # Reservation system
├── 📄 reservations.php       # User reservations
├── 📄 database.sql           # Database schema
├── 📄 config.php             # Database configuration
└── 📄 create_admin.php       # Admin user creator
```

## 🔐 Security Features

- ✅ **Password Security**: Secure hashing with PHP's `password_hash()`
- ✅ **SQL Injection Prevention**: Prepared statements for all queries
- ✅ **Session Management**: Secure session-based authentication
- ✅ **Input Validation**: Server-side and client-side validation
- ✅ **Access Control**: Role-based permissions (Admin/User)
- ✅ **XSS Protection**: Output escaping with `htmlspecialchars()`

## 📱 Responsive Design

Fully responsive design tested and optimized for:
- 🖥️ **Desktop** (1200px and above)
- 📱 **Tablet** (768px - 1199px)  
- 📱 **Mobile** (Below 768px)

## 🗄️ Database Schema

### Core Tables
- **`users`**: Customer and administrator accounts
- **`vehicles`**: Car inventory with detailed specifications
- **`reservations`**: Booking management with status tracking

### Key Features
- **Foreign Key Constraints**: Data integrity maintenance
- **Auto Timestamps**: Created/updated tracking
- **Optimized Indexes**: Fast query performance

## ⚙️ Configuration

### Environment Setup
```php
// config.php - Database Configuration
$servername = "localhost";    // Database host
$username = "root";           // Database username
$password = "";               // Database password (empty for XAMPP)
$dbname = "berudi_db";        // Database name
```

### Admin Panel Access
1. Run `create_admin.php` to create admin user
2. Login credentials: `admin` / `admin123`
3. Access admin panel at `/admin/` directory

### Vehicle Image Configuration
- Place images in `assets/images/` directory
- Supported formats: JPG, PNG, WebP
- Recommended size: 800x600px
- Path format: `assets/images/vehicle-name.jpg`

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork** the project
2. **Create** feature branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your changes (`git commit -m 'Add AmazingFeature'`)
4. **Push** to branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Add comments for complex logic
- Test all features before submitting

## 🙏 Acknowledgments

- **Bootstrap Team** - For the responsive CSS framework
- **jQuery Community** - For the powerful JavaScript library
- **Audi** - For inspiring the premium automotive experience
- **PHP Community** - For the robust backend language
---
