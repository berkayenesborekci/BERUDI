# BERUDI - Installation Guide

## 🚀 Quick Start Guide

Follow these step-by-step instructions to set up and run the BERUDI Premium Audi Car Rental System on your local machine.

## 📋 Prerequisites

Before starting, make sure you have:
- **XAMPP** (Apache + MySQL + PHP) installed
- **Web Browser** (Chrome, Firefox, Safari, etc.)
- **Text Editor** (optional, for customization)

## 🛠️ Installation Steps

### Step 1: Download XAMPP
If you don't have XAMPP installed:
1. Go to [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
2. Download XAMPP for Windows
3. Install with default settings

### Step 2: Start XAMPP Control Panel
```bash
# Open XAMPP Control Panel
Start-Process "C:\xampp\xampp-control.exe"
```

### Step 3: Start Required Services
In XAMPP Control Panel:
1. Click **Start** button next to **Apache**
2. Click **Start** button next to **MySQL**
3. Wait until both show green "Running" status

### Step 4: Copy Project Files
Copy all project files to XAMPP's web directory:
```bash
# Copy project to htdocs
xcopy "C:\path\to\your\project\*" "C:\xampp\htdocs\berudi\" /E /I /Y
```

### Step 5: Create Database
Open your terminal/command prompt and run:
```bash
# Create database
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS berudi_db;"

# Import database structure and sample data
& "C:\xampp\mysql\bin\mysql.exe" -u root berudi_db -e "source C:/xampp/htdocs/berudi/database.sql"
```

### Step 6: Verify Database Setup
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Check that `berudi_db` database exists
3. Verify these tables are created:
   - `users` (User accounts)
   - `vehicles` (Car inventory)
   - `reservations` (Booking records)

### Step 7: Launch Application
Open your web browser and navigate to:
```
http://localhost/berudi
```

## 🔐 Admin Setup

### Create Your First Admin User
1. Go to: `http://localhost/berudi/register.php`
2. Register a new account (e.g., username: `admin`, password: `admin123`)
3. Open phpMyAdmin: `http://localhost/phpmyadmin`
4. Navigate to `berudi_db` → `users` table
5. Find your user and change `is_admin` from `0` to `1`
6. Now you can access admin panel: `http://localhost/berudi/admin`

## 🧪 Testing the Application

### User Features Test:
- ✅ Visit homepage: `http://localhost/berudi`
- ✅ Register new account
- ✅ Login with credentials
- ✅ Browse available vehicles
- ✅ Make a reservation
- ✅ View your reservations
- ✅ Cancel a reservation

### Admin Features Test:
- ✅ Access admin panel: `http://localhost/berudi/admin`
- ✅ View dashboard statistics
- ✅ Add new vehicles
- ✅ Edit existing vehicles
- ✅ Approve/cancel reservations
- ✅ View all users

## 🌐 URLs Reference

| Feature | URL |
|---------|-----|
| Homepage | `http://localhost/berudi` |
| Login | `http://localhost/berudi/login.php` |
| Register | `http://localhost/berudi/register.php` |
| Vehicle Catalog | `http://localhost/berudi/vehicles.php` |
| Admin Panel | `http://localhost/berudi/admin` |
| phpMyAdmin | `http://localhost/phpmyadmin` |

## ⚙️ Configuration

### Database Settings
Default database configuration in `config.php`:
```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'berudi_db');
```

### Customize Settings
If needed, modify these values in `config.php`:
- Change database credentials
- Update server settings
- Configure error reporting

## 🎨 Sample Data

The installation includes sample Audi vehicles:
- **Audi RS6 Avant** (2023) - $2,500/day
- **Audi RS7 Sportback** (2023) - $2,800/day
- **Audi RS e-tron GT** (2023) - $3,000/day
- **Audi R8 V10** (2023) - $3,500/day

## 🚨 Troubleshooting

### Common Issues:

**Apache won't start:**
- Check if port 80 is in use
- Try changing Apache port in XAMPP

**MySQL won't start:**
- Check if port 3306 is in use
- Restart XAMPP as administrator

**Database connection error:**
- Verify MySQL is running
- Check database credentials in `config.php`

**Page not found (404):**
- Ensure files are in `C:\xampp\htdocs\berudi\`
- Check file permissions

**No vehicles showing:**
- Run database import again
- Check if `vehicles` table has data

## 🔄 Reset Installation

To start fresh:
1. Stop Apache and MySQL in XAMPP
2. Delete `C:\xampp\htdocs\berudi\` folder
3. Drop `berudi_db` database in phpMyAdmin
4. Follow installation steps again

## 📞 Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all installation steps were followed
3. Check XAMPP error logs in `C:\xampp\apache\logs\`

## 🎯 Next Steps

After successful installation:
1. Customize the vehicle catalog
2. Add your own vehicle images
3. Configure email notifications (optional)
4. Set up production environment (for live deployment)

---

**Congratulations! 🎉 Your BERUDI Car Rental System is now running!**

*Visit `http://localhost/berudi` to start using the application.* 
